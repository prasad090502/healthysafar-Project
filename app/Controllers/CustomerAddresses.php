<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CustomerAddresses extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->session = session();
        helper(['form', 'url']);
    }

    /**
     * POST /customer/addresses/store
     * - If called via AJAX → returns JSON
     * - If called via normal form (your checkout popup) → redirects
     *   based on redirect_to (checkout / addresses)
     */
    public function store()
    {
        // Must be logged in
        if (! $this->session->get('isCustomerLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to add an address.',
                ]);
            }

            return redirect()->to(site_url('login'))
                ->with('error', 'Please login to add an address.');
        }

        $customerId = (int) $this->session->get('customer_id');
        if ($customerId <= 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid customer session.',
                ]);
            }

            return redirect()->back()
                ->with('error', 'Invalid customer session.');
        }

        // IMPORTANT: where to go after saving?
        $redirectTo = (string) $this->request->getPost('redirect_to'); // "checkout" or ""

        // -------- Validation --------
        $validation = \Config\Services::validation();

        $rules = [
            'name'          => 'required|min_length[2]|max_length[150]',
            'phone'         => 'required|min_length[10]|max_length[20]',
            'address_line1' => 'required|max_length[255]',
            'city'          => 'required|max_length[100]',
            'state'         => 'required|max_length[100]',
            'pincode'       => 'required|max_length[10]',
            'address_type'  => 'required|in_list[home,office,other]',
        ];

        if (! $this->validate($rules)) {
            $errors = $validation->getErrors();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $errors,
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }

        // -------- Build data for insert --------
        $data = [
            'customer_id'     => $customerId,
            'name'            => (string) $this->request->getPost('name'),
            'phone'           => (string) $this->request->getPost('phone'),
            'alternate_phone' => (string) $this->request->getPost('alternate_phone'),
            'address_line1'   => (string) $this->request->getPost('address_line1'),
            'address_line2'   => (string) $this->request->getPost('address_line2'),
            'landmark'        => (string) $this->request->getPost('landmark'),
            'city'            => (string) $this->request->getPost('city'),
            'state'           => (string) $this->request->getPost('state'),
            'pincode'         => (string) $this->request->getPost('pincode'),
            'country'         => (string) ($this->request->getPost('country') ?: 'India'),
            'address_type'    => (string) ($this->request->getPost('address_type') ?: 'home'),
            'is_default'      => $this->request->getPost('is_default') ? 1 : 0,
        ];

        // Lat/long (nullable decimals)
        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');

        if ($lat !== null && $lat !== '') {
            $data['latitude'] = (float) $lat;
        }
        if ($lng !== null && $lng !== '') {
            $data['longitude'] = (float) $lng;
        }

        // If this one is default → reset old defaults for this customer
        if ($data['is_default'] === 1) {
            $this->db->table('customer_addresses')
                ->where('customer_id', $customerId)
                ->set('is_default', 0)
                ->update();
        }

        // -------- Insert into DB --------
        $this->db->table('customer_addresses')->insert($data);
        $newId = $this->db->insertID();

        if (! $newId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Could not save address. Please try again.',
                ]);
            }

            return redirect()->back()
                ->with('error', 'Could not save address. Please try again.');
        }

        // If someone calls via AJAX (not your current popup)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'    => true,
                'address_id' => $newId,
                'address'    => $data,
            ]);
        }

        // -------- NORMAL FORM FLOW (your modal) --------
        if ($redirectTo === 'checkout') {
            // Came from checkout popup → go back to checkout and pre-select this address
            return redirect()
                ->to(site_url('checkout') . '?addr=' . $newId)
                ->with('success', 'Address added successfully.');
        }

        // Default behaviour: go to addresses page
        return redirect()
            ->to(site_url('customer/addresses'))
            ->with('success', 'Address added successfully.');
    }
}