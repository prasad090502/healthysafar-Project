<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CustomerAccount extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['url', 'form']);
    }

    protected function requireLogin()
    {
        $session = session();
        if (! $session->get('isCustomerLoggedIn')) {
            return redirect()->to(site_url('customer/login'))
                ->with('error', 'Please login to access your account.');
        }
        return null;
    }

    /* ===================== PROFILE (VIEW) ===================== */
    public function profile()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');

        $customer = $this->db->table('customers')
            ->where('id', $customerId)
            ->get()->getRowArray();

        return view('customer/profile', [
            'customer' => $customer,
        ]);
    }

    /* ===================== ORDERS + SUBSCRIPTIONS LIST ===================== */
    public function orders()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');

        // --- ORDERS ---
        $orders = [];
        if ($this->db->tableExists('orders')) {
            $orders = $this->db->table('orders')
                ->where('customer_id', $customerId)
                ->orderBy('created_at', 'DESC')
                ->get()->getResultArray();
        }

        // --- SUBSCRIPTIONS + UPCOMING DELIVERIES ---
        $subscriptions      = [];
        $upcoming_count     = 0;
        $postponements_used = 0;

        if ($this->db->tableExists('subscriptions')) {
            $subRows = $this->db->table('subscriptions s')
                ->select('s.*, p.title AS plan_title, c.cut_off_hour, c.postponement_limit')
                ->join('subscription_plans p', 'p.id = s.subscription_plan_id', 'left')
                ->join('subscription_plan_config c', 'c.subscription_plan_id = s.subscription_plan_id', 'left')
                ->where('s.customer_id', $customerId)
                ->where('s.status', 'active')
                ->orderBy('s.created_at', 'DESC')
                ->get()->getResultArray();

            foreach ($subRows as $s) {
                $subId = (int) $s['id'];

                // delivered/pending counts + upcoming deliveries
                $delRows = $this->db->table('subscription_deliveries')
                    ->select('subscription_deliveries.*, menus.menu_name')
                    ->join('menus', 'menus.id = subscription_deliveries.menu_id', 'left')
                    ->where('subscription_id', $subId)
                    ->orderBy('delivery_date', 'ASC')
                    ->get()->getResultArray();

                $delivered = 0;
                $upcoming  = [];
                $today     = date('Y-m-d');

                foreach ($delRows as $d) {
                    if ($d['status'] === 'delivered') {
                        $delivered++;
                    }
                    if (
                        $d['delivery_date'] >= $today
                        && in_array($d['status'], ['pending', 'out_for_delivery'], true)
                    ) {
                        // If no menu assigned, get default menu for this weekday
                        if (empty($d['menu_id']) && !empty($d['delivery_date'])) {
                            $weekday = date('l', strtotime($d['delivery_date']));
                            $defaultMenu = $this->db->table('menus')
                                ->where('weekday', $weekday)
                                ->where('is_active', 1)
                                ->orderBy('id', 'ASC')
                                ->get()->getRowArray();

                            if ($defaultMenu) {
                                $d['menu_name'] = $defaultMenu['menu_name'];
                                // Update the delivery with the default menu_id
                                $this->db->table('subscription_deliveries')
                                    ->where('id', $d['id'])
                                    ->update(['menu_id' => $defaultMenu['id']]);
                            }
                        }

                        $upcoming[] = $d;
                    }
                }

                $totalDays = (int)($s['duration_days'] ?? 0);
                $remaining = max(0, $totalDays - $delivered);

                $subscriptions[] = [
                    'id'                  => $subId,
                    'status'              => $s['status'],
                    'plan_title'          => $s['plan_title'],
                    'start_date'          => $s['start_date'],
                    'end_date'            => $s['end_date'],
                    'duration_days'       => $totalDays,
                    'delivered_days'      => $delivered,
                    'remaining_days'      => $remaining,
                    'cut_off_hour'        => $s['cut_off_hour'],
                    'postponement_limit'  => $s['postponement_limit'],
                    'postponement_used'   => $s['postponement_used'],
                    'upcoming_deliveries' => $upcoming,
                ];

                $upcoming_count     += count($upcoming);
                $postponements_used += (int) $s['postponement_used'];
            }
        }

        return view('customer/orders_subscriptions', [
            'orders'             => $orders,
            'subscriptions'      => $subscriptions,
            'upcoming_count'     => $upcoming_count,
            'postponements_used' => $postponements_used,
        ]);
    }

    /* ===================== SINGLE ORDER VIEW ===================== */
   public function orderView(int $orderId)
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $session    = session();
    $customerId = (int) $session->get('customer_id');

    // Fetch order
    $order = $this->db->table('orders')
        ->where('id', $orderId)
        ->where('customer_id', $customerId)
        ->get()->getRowArray();

    if (! $order) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Order not found');
    }

    // Fetch order items
    $itemsRaw = [];
    if ($this->db->tableExists('order_items')) {
        $itemsRaw = $this->db->table('order_items')
            ->where('order_id', $orderId)
            ->get()->getResultArray();
    }

    // Filter out subscription items (we only show normal products here)
    $items = [];
    foreach ($itemsRaw as $row) {
        // if is_subscription column exists & true => skip
        if (isset($row['is_subscription']) && (int)$row['is_subscription'] === 1) {
            continue;
        }
        $items[] = $row;
    }

    return view('customer/order_view', [
        'order' => $order,
        'items' => $items,
    ]);
}

    /* ===================== SUBSCRIPTION ACTIONS ===================== */

    /**
     * Change delivery address for a specific subscription delivery.
     * URL: GET/POST customer/subscriptions/change-address/{deliveryId}
     */
    public function subscriptionChangeAddress(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');
        $request    = service('request');

        // Join delivery -> subscription to ensure it belongs to this customer
        $delivery = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.customer_id')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->where('d.id', $deliveryId)
            ->get()->getRowArray();

        if (! $delivery || (int)$delivery['customer_id'] !== $customerId) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Delivery not found.');
        }

        // Get all addresses of this customer
        $addresses = $this->db->table('customer_addresses')
            ->where('customer_id', $customerId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        if ($request->getMethod() === 'post') {
            $addressId = (int)$request->getPost('address_id');

            // Basic check that address belongs to this customer
            $addr = $this->db->table('customer_addresses')
                ->where('id', $addressId)
                ->where('customer_id', $customerId)
                ->get()->getRowArray();

            if (! $addr) {
                return redirect()->back()
                    ->with('error', 'Invalid address selected.');
            }

            // Update the delivery row
            $label = trim($addr['address_type'] . ' - ' . $addr['city']);

            $this->db->table('subscription_deliveries')
                ->where('id', $deliveryId)
                ->update([
                    'address_id'    => $addressId,
                    'address_label' => $label,
                ]);

            return redirect()->to(site_url('customer/orders') . '#subs-pane')
                ->with('success', 'Delivery address updated successfully.');
        }

        return view('customer/subscription_change_address', [
            'delivery'  => $delivery,
            'addresses' => $addresses,
        ]);
    }

    /**
     * Change delivery slot (simple label edit for now).
     * URL: GET/POST customer/subscriptions/change-slot/{deliveryId}
     */
    public function subscriptionChangeSlot(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');
        $request    = service('request');

        $delivery = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.customer_id')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->where('d.id', $deliveryId)
            ->get()->getRowArray();

        if (! $delivery || (int)$delivery['customer_id'] !== $customerId) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Delivery not found.');
        }

        if ($request->getMethod() === 'post') {
            $slotLabel = trim($request->getPost('slot_label'));

            if ($slotLabel === '') {
                return redirect()->back()
                    ->with('error', 'Please enter a valid slot.');
            }

            $this->db->table('subscription_deliveries')
                ->where('id', $deliveryId)
                ->update([
                    'slot_label'       => $slotLabel,
                    'override_slot_key'=> $slotLabel,
                ]);

            return redirect()->to(site_url('customer/orders') . '#subs-pane')
                ->with('success', 'Delivery slot updated successfully.');
        }

        return view('customer/subscription_change_slot', [
            'delivery' => $delivery,
        ]);
    }

    /**
     * Add / update customer note for a delivery.
     * URL: GET/POST customer/subscriptions/add-note/{deliveryId}
     */
    public function subscriptionAddNote(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');
        $request    = service('request');

        $delivery = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.customer_id')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->where('d.id', $deliveryId)
            ->get()->getRowArray();

        if (! $delivery || (int)$delivery['customer_id'] !== $customerId) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Delivery not found.');
        }

        if ($request->getMethod() === 'post') {
            $note = trim((string)$request->getPost('customer_note'));

            $this->db->table('subscription_deliveries')
                ->where('id', $deliveryId)
                ->update([
                    'customer_note' => $note,
                ]);

            return redirect()->to(site_url('customer/orders') . '#subs-pane')
                ->with('success', 'Note saved successfully.');
        }

        return view('customer/subscription_add_note', [
            'delivery' => $delivery,
        ]);
    }

    /**
     * Skip / postpone a delivery.
     * URL: GET customer/subscriptions/skip/{deliveryId}
     */
    public function subscriptionSkip(int $deliveryId)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');

        $delivery = $this->db->table('subscription_deliveries d')
            ->select('d.*, s.customer_id, s.postponement_used, c.postponement_limit')
            ->join('subscriptions s', 's.id = d.subscription_id', 'left')
            ->join('subscription_plan_config c', 'c.subscription_plan_id = s.subscription_plan_id', 'left')
            ->where('d.id', $deliveryId)
            ->get()->getRowArray();

        if (! $delivery || (int)$delivery['customer_id'] !== $customerId) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Delivery not found.');
        }

        $status = strtolower($delivery['status'] ?? '');
        if (! in_array($status, ['pending', 'out_for_delivery'], true)) {
            return redirect()->to(site_url('customer/orders') . '#subs-pane')
                ->with('error', 'This delivery can no longer be modified.');
        }

        $used  = (int)($delivery['postponement_used'] ?? 0);
        $limit = (int)($delivery['postponement_limit'] ?? 0);

        if ($limit > 0 && $used >= $limit) {
            return redirect()->to(site_url('customer/orders') . '#subs-pane')
                ->with('error', 'You have used all postpone credits for this plan.');
        }

        // Mark delivery as skipped
        $this->db->table('subscription_deliveries')
            ->where('id', $deliveryId)
            ->update(['status' => 'skipped']);

        // Increase postponement_used on subscription
        $this->db->table('subscriptions')
            ->where('id', $delivery['subscription_id'])
            ->set('postponement_used', 'postponement_used + 1', false)
            ->update();

        return redirect()->to(site_url('customer/orders') . '#subs-pane')
            ->with('success', 'Delivery skipped successfully.');
    }

    /* ===================== ADDRESSES LIST ===================== */
    public function addresses()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');

        $addresses = [];
        if ($this->db->tableExists('customer_addresses')) {
            $addresses = $this->db->table('customer_addresses')
                ->where('customer_id', $customerId)
                ->orderBy('is_default', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()->getResultArray();
        }

        return view('customer/addresses', [
            'addresses' => $addresses,
        ]);
    }

    /* ===================== EDIT PROFILE ===================== */

    public function editProfile()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $customerId = (int) $session->get('customer_id');

        $customer = $this->db->table('customers')
            ->where('id', $customerId)
            ->get()->getRowArray();

        return view('customer/profile_edit', [
            'customer' => $customer,
        ]);
    }

    public function updateProfile()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $request    = service('request');
        $customerId = (int) $session->get('customer_id');

        $data = [
            'name'    => trim($request->getPost('name')),
            'email'   => trim($request->getPost('email')),
            'contact' => trim($request->getPost('contact')),
        ];

        $errors = [];
        if ($data['name'] === '') {
            $errors['name'] = 'Name is required';
        }
        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }

        // Check for email duplicate
        $exists = $this->db->table('customers')
            ->where('email', $data['email'])
            ->where('id !=', $customerId)
            ->get()->getRowArray();

        if ($exists) {
            $errors['email'] = 'This email is already used by another account.';
        }

        if (! empty($errors)) {
            $customer = $this->db->table('customers')
                ->where('id', $customerId)
                ->get()->getRowArray();

            $customer['name']    = $data['name'];
            $customer['email']   = $data['email'];
            $customer['contact'] = $data['contact'];

            return view('customer/profile_edit', [
                'customer' => $customer,
                'errors'   => $errors,
            ]);
        }

        $this->db->table('customers')
            ->where('id', $customerId)
            ->update($data);

        // Update session name/email
        $session->set('customer_name', $data['name']);
        $session->set('customer_email', $data['email']);

        return redirect()->to(site_url('customer/profile'))
            ->with('success', 'Profile updated successfully.');
    }

    /* ===================== CHANGE PASSWORD ===================== */

    public function changePasswordForm()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('customer/change_password');
    }

    public function changePassword()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $session    = session();
        $request    = service('request');
        $customerId = (int) $session->get('customer_id');

        $currentPassword = (string) $request->getPost('current_password');
        $newPassword     = (string) $request->getPost('new_password');
        $confirmPassword = (string) $request->getPost('confirm_password');

        $errors = [];

        $customer = $this->db->table('customers')
            ->where('id', $customerId)
            ->get()->getRowArray();

        if (! $customer) {
            return redirect()->to(site_url('customer/login'))
                ->with('error', 'Account not found. Please login again.');
        }

        if (! password_verify($currentPassword, $customer['password_hash'])) {
            $errors['current_password'] = 'Current password is incorrect.';
        }

        if (strlen($newPassword) < 6) {
            $errors['new_password'] = 'New password must be at least 6 characters.';
        }

        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'New passwords do not match.';
        }

        if (! empty($errors)) {
            return view('customer/change_password', [
                'errors' => $errors,
            ]);
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->db->table('customers')
            ->where('id', $customerId)
            ->update(['password_hash' => $newHash]);

        return redirect()->to(site_url('customer/profile'))
            ->with('success', 'Password changed successfully.');
    }

    /* ===================== ADD / SAVE ADDRESS ===================== */

    public function addAddress()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('customer/address_form', [
            'address' => null,
        ]);
    }

    public function storeAddress()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $session    = session();
    $customerId = (int) $session->get('customer_id');
    $request    = service('request');

    // flag to know if the form was submitted from checkout page
    $fromCheckout = (bool) $request->getPost('from_checkout');

    $data = [
        'customer_id'     => $customerId,
        'name'            => trim($request->getPost('name')),
        'phone'           => trim($request->getPost('phone')),
        'alternate_phone' => trim($request->getPost('alternate_phone')),
        'address_line1'   => trim($request->getPost('address_line1')),
        'address_line2'   => trim($request->getPost('address_line2')),
        'landmark'        => trim($request->getPost('landmark')),
        'city'            => trim($request->getPost('city')),
        'state'           => trim($request->getPost('state')),
        'pincode'         => trim($request->getPost('pincode')),
        'country'         => trim($request->getPost('country') ?: 'India'),
        'address_type'    => $request->getPost('address_type') ?: 'home',
        // you said you do NOT want to make it default by force:
        'is_default'      => $request->getPost('is_default') ? 1 : 0,
        'latitude'        => $request->getPost('latitude') ?: null,
        'longitude'       => $request->getPost('longitude') ?: null,
    ];

    $errors = [];
    if ($data['name'] === '') {
        $errors['name'] = 'Name is required.';
    }
    if ($data['phone'] === '') {
        $errors['phone'] = 'Phone number is required.';
    }
    if ($data['address_line1'] === '') {
        $errors['address_line1'] = 'Address line 1 is required.';
    }
    if ($data['city'] === '' || $data['state'] === '' || $data['pincode'] === '') {
        $errors['city']    = $errors['city'] ?? 'City is required.';
        $errors['state']   = $errors['state'] ?? 'State is required.';
        $errors['pincode'] = $errors['pincode'] ?? 'Pincode is required.';
    }

    if (! empty($errors)) {
        // If request is AJAX → return JSON
        if ($request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $errors,
            ]);
        }

        // From checkout → go back to checkout with old input
        if ($fromCheckout) {
            return redirect()->to(site_url('checkout'))
                ->withInput()
                ->with('error', 'Please correct the highlighted address fields.');
        }

        // Normal address page flow
        return view('customer/address_form', [
            'address' => $data,
            'errors'  => $errors,
        ]);
    }

    // If this is set as default, reset others
    if ($data['is_default'] === 1) {
        $this->db->table('customer_addresses')
            ->where('customer_id', $customerId)
            ->update(['is_default' => 0]);
    }

    // Insert address
    $this->db->table('customer_addresses')->insert($data);
    $addressId = $this->db->insertID();

    // AJAX call (checkout using JS)
    if ($request->isAJAX()) {
        return $this->response->setJSON([
            'success'    => true,
            'address_id' => $addressId,
        ]);
    }

    // If posted from checkout as a normal form (no JS),
    // send user back to checkout and pre-select the new address.
    if ($fromCheckout) {
        return redirect()->to(site_url('checkout') . '?addr=' . $addressId)
            ->with('success', 'Address added successfully.');
    }

    // Normal redirect (Profile > Addresses page)
    return redirect()->to(site_url('customer/addresses'))
        ->with('success', 'Address added successfully.');
}

}