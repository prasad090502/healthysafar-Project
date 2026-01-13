<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class CustomerSubscriptions extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->session = session();
        helper(['url', 'form']);
    }

    protected function requireLogin()
    {
        if (! $this->session->get('isCustomerLoggedIn')) {
            return redirect()->to(site_url('customer/login'))
                ->with('error', 'Please login to manage your subscriptions.');
        }
        return null;
    }

    /* ===================== CHANGE ADDRESS ===================== */
    public function changeAddress(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $customerId = (int) $this->session->get('customer_id');
        $request    = service('request');

        // Fetch delivery + basic subscription info
        $delivery = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.end_date, s.customer_id, s.postponement_used, s.postponement_limit')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->where('d.id', $deliveryId)
            ->where('d.customer_id', $customerId)
            ->get()->getRowArray();

        if (! $delivery) {
            throw PageNotFoundException::forPageNotFound('Delivery not found');
        }

        // Fetch all customer addresses
        $addresses = $this->db->table('customer_addresses')
            ->where('customer_id', $customerId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        $currentAddressId = $delivery['override_address_id'] ?: $delivery['base_address_id'];

        $currentAddress = null;
        if ($currentAddressId) {
            $currentAddress = $this->db->table('customer_addresses')
                ->where('id', $currentAddressId)
                ->where('customer_id', $customerId)
                ->get()->getRowArray();
        }

        $errors = [];
        $success = null;

        if ($request->getMethod() === 'post') {
            $selectedId = (int) $request->getPost('address_id');

            // If no existing chosen, try to create new one
            if ($selectedId <= 0 && $request->getPost('new_name')) {
                $data = [
                    'customer_id'     => $customerId,
                    'name'            => trim($request->getPost('new_name')),
                    'phone'           => trim($request->getPost('new_phone')),
                    'alternate_phone' => trim($request->getPost('new_alternate_phone')),
                    'address_line1'   => trim($request->getPost('new_address_line1')),
                    'address_line2'   => trim($request->getPost('new_address_line2')),
                    'landmark'        => trim($request->getPost('new_landmark')),
                    'city'            => trim($request->getPost('new_city')),
                    'state'           => trim($request->getPost('new_state')),
                    'pincode'         => trim($request->getPost('new_pincode')),
                    'country'         => trim($request->getPost('new_country') ?: 'India'),
                    'address_type'    => $request->getPost('new_address_type') ?: 'home',
                    'is_default'      => 0,
                ];

                if ($data['name'] === '' || $data['phone'] === '' || $data['address_line1'] === '' || $data['city'] === '' || $data['state'] === '' || $data['pincode'] === '') {
                    $errors['new_address'] = 'Please fill all required fields of new address.';
                } else {
                    $this->db->table('customer_addresses')->insert($data);
                    $selectedId = (int) $this->db->insertID();
                }
            }

            if ($selectedId <= 0) {
                $errors['address_id'] = 'Please select an address or add a new one.';
            }

            if (empty($errors)) {
                // Update override_address_id for this delivery
                $this->db->table('subscription_deliveries')
                    ->where('id', $deliveryId)
                    ->where('customer_id', $customerId)
                    ->update(['override_address_id' => $selectedId]);

                $success = 'Delivery address updated successfully.';

                // Refresh data
                return redirect()->to(site_url('customer/orders'))
                    ->with('success', $success);
            }
        }

        return view('customer/subscription_change_address', [
            'delivery'       => $delivery,
            'addresses'      => $addresses,
            'currentAddress' => $currentAddress,
            'errors'         => $errors,
        ]);
    }

    /* ===================== CHANGE SLOT ===================== */
    public function changeSlot(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $customerId = (int) $this->session->get('customer_id');
        $request    = service('request');

        $row = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.customer_id, s.end_date, c.delivery_slots_json, c.cut_off_hour')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->join('subscription_plan_config c', 'c.subscription_plan_id = d.subscription_plan_id', 'left')
            ->where('d.id', $deliveryId)
            ->where('d.customer_id', $customerId)
            ->get()->getRowArray();

        if (! $row) {
            throw PageNotFoundException::forPageNotFound('Delivery not found');
        }

        $currentSlotKey = $row['override_slot_key'] ?: $row['base_slot_key'];
        $slots          = json_decode($row['delivery_slots_json'] ?? '[]', true) ?: [];

        $errors = [];

        if ($request->getMethod() === 'post') {
            $newSlot = (string) $request->getPost('slot_key');
            $valid   = false;

            foreach ($slots as $s) {
                if (($s['key'] ?? '') === $newSlot) {
                    $valid = true;
                    break;
                }
            }

            if (! $valid) {
                $errors['slot_key'] = 'Please select a valid delivery slot.';
            }

            if (empty($errors)) {
                $this->db->table('subscription_deliveries')
                    ->where('id', $deliveryId)
                    ->where('customer_id', $customerId)
                    ->update(['override_slot_key' => $newSlot]);

                return redirect()->to(site_url('customer/orders'))
                    ->with('success', 'Delivery slot updated successfully.');
            }
        }

        return view('customer/subscription_change_slot', [
            'delivery'       => $row,
            'slots'          => $slots,
            'currentSlotKey' => $currentSlotKey,
            'errors'         => $errors,
        ]);
    }

    /* ===================== ADD NOTE ===================== */
    public function addNote(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $customerId = (int) $this->session->get('customer_id');
        $request    = service('request');

        $delivery = $this->db->table('subscription_deliveries')
            ->where('id', $deliveryId)
            ->where('customer_id', $customerId)
            ->get()->getRowArray();

        if (! $delivery) {
            throw PageNotFoundException::forPageNotFound('Delivery not found');
        }

        $errors = [];

        if ($request->getMethod() === 'post') {
            $note = trim((string) $request->getPost('note'));

            if (strlen($note) > 1000) {
                $errors['note'] = 'Note can be maximum 1000 characters.';
            }

            if (empty($errors)) {
                $this->db->table('subscription_deliveries')
                    ->where('id', $deliveryId)
                    ->where('customer_id', $customerId)
                    ->update(['notes' => $note]);

                return redirect()->to(site_url('customer/orders'))
                    ->with('success', 'Delivery note saved successfully.');
            }
        }

        return view('customer/subscription_add_note', [
            'delivery' => $delivery,
            'errors'   => $errors,
        ]);
    }

    /* ===================== CHANGE MENU ===================== */
    public function changeMenu(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $customerId = (int) $this->session->get('customer_id');
        $request    = service('request');

        // Fetch delivery with subscription info
        $delivery = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.status AS subscription_status, s.customer_id')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->where('d.id', $deliveryId)
            ->where('s.customer_id', $customerId)
            ->get()->getRowArray();

        if (!$delivery) {
            throw PageNotFoundException::forPageNotFound('Delivery not found');
        }

        // Check if subscription is active
        if (strtolower($delivery['subscription_status'] ?? '') !== 'active') {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Menu changes are only allowed for active subscriptions.');
        }

        // Get delivery weekday
        $weekday = date('l', strtotime($delivery['delivery_date']));

        // Fetch available menus for this weekday (active only)
        $menus = $this->db->table('menus')
            ->where('weekday', $weekday)
            ->where('is_active', 1)
            ->orderBy('menu_name', 'ASC')
            ->get()->getResultArray();

        // Get current menu if assigned
        $currentMenu = null;
        if (!empty($delivery['menu_id'])) {
            $currentMenu = $this->db->table('menus')
                ->where('id', $delivery['menu_id'])
                ->get()->getRowArray();
        }

        $errors = [];
        $success = null;

        if ($request->getMethod() === 'post') {
            $menuId = $request->getPost('menu_id');

            if ($menuId === 'skip') {
                // Skip delivery
                $this->db->table('subscription_deliveries')
                    ->where('id', $deliveryId)
                    ->where('customer_id', $customerId)
                    ->update([
                        'menu_id' => null,
                        'status'  => 'skipped',
                        'notes'   => trim(($delivery['notes'] ?? '') . "\nMenu skipped for " . date('d M Y', strtotime($delivery['delivery_date']))),
                    ]);

                return redirect()->to(site_url('customer/orders'))
                    ->with('success', 'Delivery skipped successfully.');
            } elseif (empty($menuId)) {
                $errors['menu_id'] = 'Please select a menu or choose to skip the delivery.';
            } else {
                // Validate menu exists and is for correct weekday
                $selectedMenu = $this->db->table('menus')
                    ->where('id', $menuId)
                    ->where('weekday', $weekday)
                    ->where('is_active', 1)
                    ->get()->getRowArray();

                if (!$selectedMenu) {
                    $errors['menu_id'] = 'Selected menu is not available for this delivery day.';
                } else {
                    // Update delivery with new menu
                    $this->db->table('subscription_deliveries')
                        ->where('id', $deliveryId)
                        ->where('customer_id', $customerId)
                        ->update(['menu_id' => $menuId]);

                    return redirect()->to(site_url('customer/orders'))
                        ->with('success', 'Menu updated successfully for ' . date('d M Y', strtotime($delivery['delivery_date'])) . '.');
                }
            }
        }

        return view('customer/subscription_change_menu', [
            'delivery'   => $delivery,
            'menus'      => $menus,
            'currentMenu'=> $currentMenu,
            'weekday'    => $weekday,
            'errors'     => $errors,
            'success'    => $success,
        ]);
    }

    /* ===================== SKIP / POSTPONE ===================== */
    public function skip(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $customerId = (int) $this->session->get('customer_id');
        $request    = service('request');

        $row = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.end_date, s.postponement_used, s.postponement_limit, s.id AS subscription_id,
                      c.off_days_json, c.cut_off_hour')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->join('subscription_plan_config c', 'c.subscription_plan_id = d.subscription_plan_id', 'left')
            ->where('d.id', $deliveryId)
            ->where('d.customer_id', $customerId)
            ->get()->getRowArray();

        if (! $row) {
            throw PageNotFoundException::forPageNotFound('Delivery not found');
        }

        $subscriptionId   = (int) $row['subscription_id'];
        $endDate          = $row['end_date'];
        $postUsed         = (int) $row['postponement_used'];
        $postLimit        = (int) $row['postponement_limit'];
        $offDays          = json_decode($row['off_days_json'] ?? '[]', true) ?: [];

        if ($postUsed >= $postLimit) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'You have used all postpone credits for this subscription.');
        }

        $today    = new \DateTimeImmutable('today');
        $delDate  = new \DateTimeImmutable($row['delivery_date']);
        $minDate  = $delDate > $today ? $delDate : $today; // cannot pick before today
        $minDate  = $minDate->modify('+1 day'); // pick from tomorrow

        $end      = new \DateTimeImmutable($endDate);
        $maxDate  = $end->modify('+6 days'); // not more than 6 days after subscription end

        $errors = [];

        if ($request->getMethod() === 'post') {
            $newDateStr = $request->getPost('new_date');
            if (! $newDateStr) {
                $errors['new_date'] = 'Please select a date.';
            } else {
                try {
                    $newDate = new \DateTimeImmutable($newDateStr);

                    if ($newDate < $minDate || $newDate > $maxDate) {
                        $errors['new_date'] = 'Please choose a date between '
                            . $minDate->format('d M Y') . ' and ' . $maxDate->format('d M Y') . '.';
                    } else {
                        // Check off-day
                        $dayCode = strtoupper($newDate->format('D')); // e.g. MON, TUE, SUN
                        if (in_array($dayCode, $offDays, true)) {
                            $errors['new_date'] = 'Selected date is an off-day. Please choose another date.';
                        }
                    }
                } catch (\Exception $e) {
                    $errors['new_date'] = 'Invalid date selected.';
                }
            }

            if (empty($errors)) {
                // Mark current delivery as skipped
                $this->db->table('subscription_deliveries')
                    ->where('id', $deliveryId)
                    ->where('customer_id', $customerId)
                    ->update([
                        'status' => 'skipped',
                        'notes'  => trim(($row['notes'] ?? '') . "\nSkipped & postponed to " . $newDateStr),
                    ]);

                // Create new extended delivery
                $insertData = [
                    'subscription_id'       => $subscriptionId,
                    'subscription_plan_id'  => $row['subscription_plan_id'],
                    'user_id'               => $row['user_id'],
                    'customer_id'           => $customerId,
                    'delivery_date'         => $newDateStr,
                    'base_address_id'       => $row['override_address_id'] ?: $row['base_address_id'],
                    'override_address_id'   => null,
                    'base_slot_key'         => $row['override_slot_key'] ?: $row['base_slot_key'],
                    'override_slot_key'     => null,
                    'status'                => 'pending',
                    'notes'                 => $row['notes'],
                    'is_generated_extension'=> ($newDate > $end) ? 1 : 0,
                ];

                $this->db->table('subscription_deliveries')->insert($insertData);

                // Update subscription end_date if new date goes beyond it
                if ($newDate > $end) {
                    $this->db->table('subscriptions')
                        ->where('id', $subscriptionId)
                        ->update(['end_date' => $newDate->format('Y-m-d')]);
                }

                // Increase postponement_used
                $this->db->table('subscriptions')
                    ->where('id', $subscriptionId)
                    ->set('postponement_used', 'postponement_used + 1', false)
                    ->update();

                return redirect()->to(site_url('customer/orders'))
                    ->with('success', 'Delivery postponed to ' . $newDate->format('d M Y') . '.');
            }
        }

        return view('customer/subscription_skip', [
            'delivery' => $row,
            'minDate'  => $minDate->format('Y-m-d'),
            'maxDate'  => $maxDate->format('Y-m-d'),
            'errors'   => $errors,
        ]);
    }
}