<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\OrderItemModel;
// If you have a CategoryModel, you can also use it
// use App\Models\CategoryModel;

class Dashboard extends AdminBaseController
{
    protected $db;
    protected $orders;
    protected $customers;
    protected $products;
    protected $orderItems;

    public function __construct()
    {
        $session = session();
        if (!$session->get('isAdminLogged')) {
            return redirect()->to(site_url('admin/login'))->send();
        }

        $this->db         = db_connect();
        $this->orders     = new OrderModel();
        $this->customers  = new CustomerModel();
        $this->products   = new ProductModel();
        $this->orderItems = new OrderItemModel();
        // $this->categories = new CategoryModel();
    }

    public function index()
{
    // ------------------ BASIC STATS ------------------

    // Total revenue (paid orders only)
    $revenueRow = $this->orders
        ->selectSum('grand_total', 'total_revenue')
        ->where('payment_status', 'paid')
        ->get()
        ->getRow('total_revenue');

    $revenue = $revenueRow !== null ? (float) $revenueRow : 0.0;

    // Total orders (all)
    $totalOrders = $this->orders->countAll();

    // Total customers
    $totalCustomers = $this->customers->countAll();

    // Pending orders count
    $pendingOrders = $this->orders
        ->where('status', 'pending')
        ->countAllResults(); // resets builder

    // Today’s revenue (paid)
    $today = date('Y-m-d');
    $todayRow = $this->orders
        ->selectSum('grand_total', 'today_revenue')
        ->where('payment_status', 'paid')
        ->where('DATE(created_at)', $today)
        ->get()
        ->getRow('today_revenue');

    $todayRevenue = $todayRow !== null ? (float) $todayRow : 0.0;

    // This month revenue (paid)
    $monthRow = $this->orders
        ->selectSum('grand_total', 'month_revenue')
        ->where('payment_status', 'paid')
        ->where('YEAR(created_at)', date('Y'))
        ->where('MONTH(created_at)', date('m'))
        ->get()
        ->getRow('month_revenue');

    $monthRevenue = $monthRow !== null ? (float) $monthRow : 0.0;

    // Average order value (paid orders only)
    $paidOrderCount = $this->orders
        ->where('payment_status', 'paid')
        ->countAllResults();

    $avgOrderValue = $paidOrderCount > 0
        ? $revenue / $paidOrderCount
        : 0.0;

    // ------------------ RECENT ORDERS ------------------

    $recentOrders = $this->orders
        ->select('orders.id, orders.order_number, orders.grand_total, orders.status, orders.created_at, customers.name AS customer_name')
        ->join('customers', 'customers.id = orders.customer_id', 'left')
        ->orderBy('orders.created_at', 'DESC')
        ->limit(10)
        ->get()
        ->getResultArray();

    // Attach first item name for display
    foreach ($recentOrders as &$o) {
        $item = $this->orderItems
            ->where('order_id', $o['id'])
            ->orderBy('id', 'ASC')
            ->get(1)
            ->getRowArray();

        $o['item'] = $item['product_name'] ?? 'Multiple items';
    }
    unset($o);

    // ------------------ TOP PRODUCTS (LIST + CHART) ------------------
    // Uses only qty (no price column needed)

    $topProducts = $this->db->table('order_items')
        ->select('product_id, product_name, SUM(qty) AS total_qty')
        ->groupBy('product_id, product_name')
        ->orderBy('total_qty', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    // Single "hero" product for the green card
    $topProductSingle = $topProducts[0] ?? null;

    // ------------------ TOP CATEGORIES (LIST + CHART) ------------------
    // Only if categories table exists. Otherwise keep it empty to avoid error.

    $topCategories = [];

    try {
        if (method_exists($this->db, 'tableExists') && $this->db->tableExists('categories')) {
            $topCategories = $this->db->table('order_items')
                ->select('categories.id AS category_id, categories.name AS category_name,
                          SUM(order_items.qty) AS total_qty')
                ->join('products', 'products.id = order_items.product_id', 'left')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->groupBy('categories.id, categories.name')
                ->orderBy('total_qty', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        }
    } catch (\Throwable $e) {
        // Silently ignore if anything goes wrong (e.g. no table, different schema)
        $topCategories = [];
    }

    // ------------------ TOP CUSTOMERS (MORE ORDERED) ------------------

    $topCustomers = $this->db->table('orders')
        ->select('customers.id AS customer_id, customers.name AS customer_name,
                  COUNT(orders.id) AS total_orders,
                  SUM(orders.grand_total) AS total_spent')
        ->join('customers', 'customers.id = orders.customer_id', 'left')
        ->groupBy('customers.id, customers.name')
        ->orderBy('total_orders', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    // ------------------ MONTHLY REVENUE (LAST 6 MONTHS) ------------------
    // Group by year-month for paid orders

    $monthlyRows = $this->db->table('orders')
        ->select("DATE_FORMAT(created_at, '%Y-%m') AS ym, SUM(grand_total) AS total")
        ->where('payment_status', 'paid')
        ->groupBy('ym')
        ->orderBy('ym', 'ASC')
        ->limit(6)
        ->get()
        ->getResultArray();

    $monthlyLabels = [];
    $monthlyData   = [];

    foreach ($monthlyRows as $row) {
        $label           = date('M Y', strtotime($row['ym'] . '-01')); // "Nov 2025"
        $monthlyLabels[] = $label;
        $monthlyData[]   = (float) $row['total'];
    }

    // ------------------ CHART DATA ARRAYS ------------------

    $chartMonthlyRevenue = [
        'labels' => $monthlyLabels,
        'data'   => $monthlyData,
    ];

    $chartTopProducts = [
        'labels' => array_map(static function ($p) {
            return $p['product_name'];
        }, $topProducts),
        'data'   => array_map(static function ($p) {
            return (int) $p['total_qty'];
        }, $topProducts),
    ];

    $chartTopCategories = [
        'labels' => array_map(static function ($c) {
            return $c['category_name'] ?? 'Uncategorised';
        }, $topCategories),
        'data'   => array_map(static function ($c) {
            return (int) $c['total_qty'];
        }, $topCategories),
    ];

    // ------------------ FINAL DATA TO VIEW ------------------

    $data = [
        'title' => 'Dashboard',
        'stats' => [
            'revenue'          => $revenue,
            'orders'           => $totalOrders,
            'customers'        => $totalCustomers,
            'pending_orders'   => $pendingOrders,
            'today_revenue'    => $todayRevenue,
            'month_revenue'    => $monthRevenue,
            'avg_order_value'  => $avgOrderValue,
            'top_product'      => $topProductSingle['product_name'] ?? '—',
            'top_qty'          => $topProductSingle['total_qty'] ?? 0,
        ],
        'recent_orders' => $recentOrders,
        'topProducts'   => $topProducts,
        'topCategories' => $topCategories, // may be [] if no table
        'topCustomers'  => $topCustomers,
        'charts'        => [
            'monthlyRevenue' => $chartMonthlyRevenue,
            'topProducts'    => $chartTopProducts,
            'topCategories'  => $chartTopCategories,
        ],
    ];

    return view('admin/dashboard', $data);
}
}