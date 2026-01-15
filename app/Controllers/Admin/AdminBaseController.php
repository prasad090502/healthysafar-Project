<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Base controller for all admin controllers
 * Provides authentication check using initController() method
 * (CI4 correct way - avoids constructor redirect issues)
 */
class AdminBaseController extends BaseController
{
    /**
     * Initialize controller
     * Called after __construct() but before the controller method
     * Perfect place for authentication checks in CI4
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Check admin authentication
        $session = session();
        if (!$session->get('isAdminLogged')) {
            // Store intended URL for redirect after login
            $intended = current_url();
            if ($intended !== site_url('login')) {
                $session->set('admin_intended_url', $intended);
            }

            // Redirect to admin login page (CI4 safe way)
            return redirect()->to(site_url('admin/login'))->send();
        }

        // Additional role check (though filter handles it, for redundancy)
        $this->checkAdminRole();
    }

    /**
     * Check if the logged-in user has admin role
     */
    protected function checkAdminRole()
    {
        $session = session();
        if (!$session->get('admin_role') || $session->get('admin_role') !== 'admin') {
            // Log out and redirect
            $session->remove(['admin_id', 'admin_name', 'admin_email', 'admin_role', 'isAdminLogged']);
            $session->destroy();
            redirect()->to(site_url('admin/login'))->with('error', 'Access denied. Admin role required.')->send();
            exit;
        }
    }
}
