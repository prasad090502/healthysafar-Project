<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerAuth extends BaseController
{
    protected $db;
    protected $customersTable = 'customers';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['url', 'form']);
    }

    /**
     * Remember the page from which user came (or explicit ?redirect=...)
     */
    protected function rememberIntendedUrl(): void
    {
        $session = session();
        $request = service('request');

        // 1) Highest priority: ?redirect=/some/path
        $redirect = $request->getGet('redirect');

        // 2) Otherwise, use HTTP_REFERER (previous page)
        if (empty($redirect)) {
            $referer = $request->getServer('HTTP_REFERER') ?? '';
            if ($referer) {
                $base = rtrim(site_url(), '/');

                // Only accept URLs from our own site
                if (strpos($referer, $base) === 0) {
                    $redirect = substr($referer, strlen($base));

                    // Normalize: ensure at least "/..."
                    if ($redirect === '' || $redirect[0] !== '/') {
                        $redirect = '/' . ltrim($redirect, '/');
                    }
                }
            }
        }

        // Avoid redirecting back to login/register themselves
        if (!empty($redirect)) {
            $loginUrl    = site_url('customer/login');
            $registerUrl = site_url('customer/register');

            if (
                str_starts_with(site_url($redirect), $loginUrl) ||
                str_starts_with(site_url($redirect), $registerUrl)
            ) {
                $redirect = null;
            }
        }

        if (!empty($redirect)) {
            $session->set('customer_intended_url', $redirect);
        }
    }

    /**
     * Get & clear intended URL from session
     */
    protected function pullIntendedUrl(): ?string
    {
        $session  = session();
        $intended = $session->get('customer_intended_url');
        $session->remove('customer_intended_url');

        // Fallback if something weird
        if (!is_string($intended) || trim($intended) === '') {
            return null;
        }

        return $intended;
    }

    public function showRegister()
    {
        // Store from where user came
        $this->rememberIntendedUrl();

        return view('auth/customer_register');
    }

    public function register()
    {
        $request = service('request');

        $data = [
            'name'     => trim($request->getPost('name')),
            'username' => trim($request->getPost('username')),
            'email'    => trim($request->getPost('email')),
            'contact'  => trim($request->getPost('contact')),
            'role'     => 'customer',
        ];
        $password      = $request->getPost('password');
        $passwordAgain = $request->getPost('password_confirm');

        // Basic validation (you can replace with CI4 Validation rules)
        $errors = [];

        if ($data['name'] === '')     $errors['name'] = 'Name is required';
        if ($data['username'] === '') $errors['username'] = 'Username is required';
        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }
        if ($password === '' || strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        if ($password !== $passwordAgain) {
            $errors['password_confirm'] = 'Passwords do not match';
        }

        if (! empty($errors)) {
            return view('auth/customer_register', [
                'errors' => $errors,
                'old'    => $data,
            ]);
        }

        // Check duplicate email/username
        $builder = $this->db->table($this->customersTable);
        $exists  = $builder->groupStart()
            ->where('email', $data['email'])
            ->orWhere('username', $data['username'])
            ->groupEnd()
            ->get()->getRowArray();

        if ($exists) {
            $errors['email']    = 'Email or username already in use';
            $errors['username'] = 'Email or username already in use';

            return view('auth/customer_register', [
                'errors' => $errors,
                'old'    => $data,
            ]);
        }

        $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);

        $builder->insert($data);

        // After registration, redirect to intended URL if any
        $intended = $this->pullIntendedUrl();

        return redirect()
            ->to($intended ?? site_url('customer/login'))
            ->with('success', 'Registration successful! Please log in.');
    }

    public function showLogin()
    {
        // Store from where user came
        $this->rememberIntendedUrl();

        return view('auth/customer_login');
    }

    public function login()
    {
        $request  = service('request');
        $login    = trim($request->getPost('login')); // username or email
        $password = $request->getPost('password');

        $builder = $this->db->table($this->customersTable);
        $user    = $builder->groupStart()
                ->where('email', $login)
                ->orWhere('username', $login)
            ->groupEnd()
            ->get()->getRowArray();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return view('auth/customer_login', [
                'error' => 'Invalid login credentials',
                'old'   => ['login' => $login],
            ]);
        }

        // Store in session
        $session = session();
        $session->set([
            'customer_id'        => $user['id'],
            'customer_name'      => $user['name'],
            'customer_email'     => $user['email'],
            'customer_role'      => $user['role'],
            'isCustomerLoggedIn' => true,
        ]);

        // Redirect back to intended URL (e.g. checkout page) if set
        $intended = $this->pullIntendedUrl();

        return redirect()->to($intended ?? site_url('/')); // fallback: home
    }

    public function logout()
    {
        session()->remove([
            'customer_id',
            'customer_name',
            'customer_email',
            'customer_role',
            'isCustomerLoggedIn',
            'customer_intended_url',
        ]);
        session()->destroy();

        return redirect()->to(site_url('customer/login'))
            ->with('success', 'Logged out successfully.');
    }
}
