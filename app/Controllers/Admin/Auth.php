<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $adminModel;
    protected $session;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->session = session();
        helper(['url', 'form']);
    }

    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // Always show login form - remove auto-redirect for testing
        return view('admin/auth/login');
    }

    /**
     * Handle admin login
     */
    public function login()
    {
        $request = service('request');
        $login = trim($request->getPost('login')); // username or email
        $password = $request->getPost('password');

        // Basic validation
        if (empty($login) || empty($password)) {
            return view('admin/auth/login', [
                'error' => 'Please provide both username/email and password.',
                'old' => ['login' => $login],
            ]);
        }

        // Get admin user
        $admin = $this->adminModel->getAdminByLogin($login);

        if (!$admin || !$this->adminModel->verifyPassword($admin, $password)) {
            return view('admin/auth/login', [
                'error' => 'Invalid login credentials.',
                'old' => ['login' => $login],
            ]);
        }

        // Set session data
        $this->session->set([
            'admin_id' => $admin['id'],
            'admin_name' => $admin['name'],
            'admin_email' => $admin['email'],
            'admin_role' => $admin['role'],
            'isAdminLogged' => true,
        ]);

        // Redirect to intended URL or dashboard
        $intended = $this->session->get('admin_intended_url');
        if ($intended) {
            $this->session->remove('admin_intended_url');
            return redirect()->to($intended);
        }

        return redirect()->to(site_url('admin/dashboard'));
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        $this->session->remove([
            'admin_id',
            'admin_name',
            'admin_email',
            'admin_role',
            'isAdminLogged',
        ]);
        $this->session->destroy();

        return redirect()->to(site_url('admin/login'))
            ->with('success', 'Logged out successfully.');
    }
}
