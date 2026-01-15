<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $session->start();

        // Check if admin is logged in
        if (!$session->get('isAdminLogged')) {
            // Get current URI
            $currentUri = uri_string();

            // Don't redirect if already on login or logout pages
            if ($currentUri === 'admin/login' || $currentUri === 'admin/logout') {
                return;
            }

            // Store intended URL for redirect after login
            $intended = current_url();
            if ($intended !== site_url('admin/login')) {
                $session->set('admin_intended_url', $intended);
            }

            // Redirect to login
            return redirect()->to(site_url('admin/login'));
        }

        // Check if user has admin role
        if (!$session->get('admin_role') || $session->get('admin_role') !== 'admin') {
            // Log out and redirect to login
            $session->remove(['admin_id', 'admin_name', 'admin_email', 'admin_role', 'isAdminLogged']);
            $session->destroy();
            return redirect()->to(site_url('admin/login'))->with('error', 'Access denied. Admin role required.');
        }

        // Optional: Check session timeout manually (CI handles it, but for explicit check)
        $expiration = config('Session')->expiration;
        if ($expiration > 0 && (time() - $session->get('__ci_last_regenerate')) > $expiration) {
            $session->remove(['admin_id', 'admin_name', 'admin_email', 'admin_role', 'isAdminLogged']);
            $session->destroy();
            return redirect()->to(site_url('admin/login'))->with('error', 'Session expired. Please log in again.');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
