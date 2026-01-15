<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------
// BASIC SITE PAGES
// ---------------------------------------------------------

// Home
$routes->get('/', 'Home::index');

// Dashboard (redirect to admin dashboard)
$routes->get('dashboard', 'Dashboard::index', ['namespace' => 'App\Controllers\Admin']);

// BLOCK direct access to /admin - must be defined BEFORE admin route group
$routes->get('admin', function () {
    // Block direct access to /admin root URL
    // Redirect to login page instead of showing 404
    return redirect()->to(site_url('login'));
});

// Static pages
$routes->get('about', 'Page::about');
$routes->get('contact', 'Home::contact');      // Or Page::contact if you prefer
$routes->get('contacts', 'Page::contacts');    // Extra contacts page

// Policies
$routes->get('terms-and-conditions', 'Page::terms');
$routes->get('privacy-policy',       'Page::privacy');
$routes->get('refund-policy',        'Page::refund');
$routes->get('shipping-policy',      'Page::shipping');

// ---------------------------------------------------------
// SHOP & PRODUCTS
// ---------------------------------------------------------

$routes->get('shop',                 'Shop::index');
$routes->get('product/(:segment)',   'Shop::details/$1');

// ---------------------------------------------------------
// CART
// ---------------------------------------------------------

$routes->get('cart',                 'Shop::cart');

// Add to cart (support both POST + optional GET)
$routes->post('cart/add/(:num)',     'Shop::addToCart/$1');
$routes->get('cart/add/(:num)',      'Shop::addToCart/$1'); // optional

$routes->post('cart/update',         'Shop::updateCart');
$routes->get('cart/remove/(:num)',   'Shop::removeFromCart/$1');

// ---------------------------------------------------------
// CUSTOMER AUTH
// ---------------------------------------------------------

$routes->get('customer/register',    'CustomerAuth::showRegister');
$routes->post('customer/register',   'CustomerAuth::register');

$routes->get('customer/login',       'CustomerAuth::showLogin');
$routes->post('customer/login',      'CustomerAuth::login');

$routes->get('customer/logout',      'CustomerAuth::logout');

// ---------------------------------------------------------
// CUSTOMER ACCOUNT (FRONTEND)
// ---------------------------------------------------------

$routes->group('customer', static function (RouteCollection $routes) {

    // Profile
    $routes->get('profile',             'CustomerAccount::profile');
    $routes->get('profile/edit',        'CustomerAccount::editProfile');
    $routes->post('profile/update',     'CustomerAccount::updateProfile');

    // Password
    $routes->get('password',            'CustomerAccount::changePasswordForm');
    $routes->post('password',           'CustomerAccount::changePassword');

    // Orders
    $routes->get('orders',              'CustomerAccount::orders');
    $routes->get('orders/view/(:num)',  'CustomerAccount::orderView/$1');

    // Addresses:
    // - Listing + dedicated add page still via CustomerAccount
    // - SAVING goes through CustomerAddresses::store
    $routes->get('addresses',           'CustomerAccount::addresses');
    $routes->get('addresses/add',       'CustomerAccount::addAddress');
    $routes->post('addresses/store',    'CustomerAddresses::store');

    // Subscription self-service (CustomerSubscriptions controller)
    $routes->match(['get', 'post'], 'subscriptions/change-address/(:num)', 'CustomerSubscriptions::changeAddress/$1');
    $routes->match(['get', 'post'], 'subscriptions/change-slot/(:num)',    'CustomerSubscriptions::changeSlot/$1');
    $routes->match(['get', 'post'], 'subscriptions/change-menu/(:num)',    'CustomerSubscriptions::changeMenu/$1');
    $routes->match(['get', 'post'], 'subscriptions/add-note/(:num)',       'CustomerSubscriptions::addNote/$1');
    $routes->match(['get', 'post'], 'subscriptions/skip/(:num)',           'CustomerSubscriptions::skip/$1');

    // Optional: subscription overview page
    $routes->get('subscriptions/view/(:num)', 'CustomerSubscriptions::view/$1');
});

// ---------------------------------------------------------
// CHECKOUT (FRONTEND)
// ---------------------------------------------------------

$routes->get('checkout',                     'Checkout::index');
$routes->post('checkout/place',              'Checkout::placeOrder');
$routes->post('checkout/razorpay-success',   'Checkout::razorpaySuccess');
$routes->post('checkout/razorpay-failed',    'Checkout::razorpayFailed');

// ---------------------------------------------------------
// FRONTEND SUBSCRIPTION CATALOG
// ---------------------------------------------------------

$routes->get('subscriptions',                'Subscriptions::index');
$routes->get('subscriptions/cart-preview',   'Subscriptions::cartPreview');
$routes->get('subscriptions/(:segment)',     'Subscriptions::show/$1');
$routes->post('subscriptions/add-to-cart',   'Subscriptions::addToCart');

// ---------------------------------------------------------
// AUTHENTICATION ROUTES
// ---------------------------------------------------------

// Customer authentication routes
$routes->get('customer/login',       'CustomerAuth::showLogin');
$routes->post('customer/login',      'CustomerAuth::login');
$routes->get('customer/logout',      'CustomerAuth::logout');

// Admin authentication routes (separate from customer)
$routes->get('admin/login', '\App\Controllers\Admin\Auth::showLogin');
$routes->post('admin/login', '\App\Controllers\Admin\Auth::login');
$routes->get('admin/logout', '\App\Controllers\Admin\Auth::logout');

// General login route (redirect to admin login for now)
$routes->get('login', function () {
    return redirect()->to(site_url('admin/login'));
});
$routes->post('login', '\App\Controllers\Admin\Auth::login');
$routes->get('logout', '\App\Controllers\Admin\Auth::logout');

// ---------------------------------------------------------
// ADMIN AREA
// URL prefix: /admin/...
// ---------------------------------------------------------

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminAuth'], static function (RouteCollection $routes) {

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // TEMPORARY: Test Change Menu (REMOVE AFTER TESTING)
    $routes->get('test-change-menu', function () {return view('admin/test_change_menu');});

    // Redirect /admin -> /admin/dashboard (handled above)

    // ----------------- PRODUCTS -----------------
    $routes->get('products',                    'Products::index');
    $routes->get('products/new',                'Products::create');
    $routes->post('products/store',             'Products::store');
    $routes->get('products/(:num)/edit',        'Products::edit/$1');
    $routes->post('products/(:num)/update',     'Products::update/$1');
    $routes->post('products/(:num)/delete',     'Products::delete/$1');
    $routes->post('products/(:num)/toggle-status', 'Products::toggleStatus/$1');

    // ----------------- ORDERS -----------------
    $routes->group('orders', static function (RouteCollection $routes) {
        $routes->get('/',                        'Orders::index');         // /admin/orders
        $routes->get('print-labels',             'Orders::printLabels');   // /admin/orders/print-labels
        $routes->get('(:num)',                   'Orders::show/$1');       // /admin/orders/123
        $routes->post('(:num)/update-status',    'Orders::updateStatus/$1');
        $routes->post('update-status-inline',    'Orders::updateStatusInline');
    });

    // ----------------- MENUS -----------------
    $routes->get('menus', 'MenuController::index');
    $routes->get('menus/(:num)/edit', 'MenuController::edit/$1');
    $routes->post('menus/store', 'MenuController::store');
    $routes->post('menus/(:num)/update', 'MenuController::update/$1');
    $routes->post('menus/toggle-status/(:num)', 'MenuController::toggleStatus/$1');
    $routes->get('menus/get-by-weekday', 'MenuController::getMenusByWeekday');

    // ----------------- SUBSCRIPTION DELIVERIES -----------------
    $routes->get('subscription-deliveries',                 'SubscriptionDeliveries::index');
    $routes->post('subscription-deliveries/(:num)/status',  'SubscriptionDeliveries::updateStatus/$1');

    // Change menu for upcoming delivery (ADMIN)
    $routes->post('subscription-deliveries/change-menu', 'SubscriptionController::changeDeliveryMenu');

    // Customer menu change
    $routes->match(['get', 'post'], 'subscriptions/change-menu/(:num)', 'CustomerSubscriptions::changeMenu/$1');

    // TEMPORARY: Test Change Menu
    $routes->get('test-change-menu', function () {return view('admin/test_change_menu');});
    
    // ----------------- SUBSCRIPTION PLANS (Admin) -----------------
    $routes->get('subscription-plans',                  'SubscriptionPlans::index');
    $routes->get('subscription-plans/create',           'SubscriptionPlans::create');
    $routes->post('subscription-plans/store',           'SubscriptionPlans::store');
    $routes->get('subscription-plans/(:num)/edit',      'SubscriptionPlans::edit/$1');
    $routes->post('subscription-plans/(:num)/update',   'SubscriptionPlans::update/$1');
    $routes->get('subscription-plans/(:num)/delete',    'SubscriptionPlans::delete/$1');

    // Optional: admin view of subscriptions
    $routes->get('subscriptions', 'Subscriptions::index');

    // ----------------- CUSTOMERS -----------------
    $routes->get('customers',      'Customers::index');
    $routes->get('customers/(:num)','Customers::show/$1');
});

// ---------------------------------------------------------
// ENVIRONMENT-SPECIFIC ROUTES
// ---------------------------------------------------------

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}