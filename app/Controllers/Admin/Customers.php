<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\CustomerAddressModel;

class Customers extends BaseController
{
    protected $customers;
    protected $orders;
    protected $addresses;
    protected $perPage = 10;

    public function __construct()
    {
        $this->customers = new CustomerModel();
        $this->orders    = new OrderModel();
        $this->addresses = new CustomerAddressModel();
    }

    /**
     * Customer index: list customers + total orders + total spent
     */
    public function index()
    {
        $q = $this->request->getGet('q');

        $builder = $this->customers
            ->select('customers.*, COUNT(orders.id) AS total_orders, COALESCE(SUM(orders.grand_total),0) AS total_spent')
            ->join('orders', 'orders.customer_id = customers.id', 'left')
            ->groupBy('customers.id')
            ->orderBy('customers.created_at', 'DESC');

        if (!empty($q)) {
            $builder->groupStart()
                ->like('customers.name', $q)
                ->orLike('customers.email', $q)
                ->orLike('customers.contact', $q)
                ->groupEnd();
        }

        $customers = $builder->paginate($this->perPage);
        $pager     = $this->customers->pager;

        $data = [
            'title'     => 'Customers',
            'customers' => $customers,
            'pager'     => $pager,
            'q'         => $q,
        ];

        return view('admin/customers/index', $data);
    }

    /**
     * Customer profile:
     * - customer info
     * - addresses
     * - orders (with filters)
     * - stats: total spent, total orders, status counts, payment counts
     */
    public function show($id)
    {
        $customer = $this->customers->find($id);
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Customer not found');
        }

        // --- Addresses ---
        $addresses = $this->addresses
            ->where('customer_id', $id)
            ->orderBy('is_default', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // --- Filters for orders ---
        $request       = $this->request;
        $status        = $request->getGet('status');          // pending, processing, delivered, etc.
        $paymentStatus = $request->getGet('payment_status');  // pending, paid, failed, refunded
        $dateFrom      = $request->getGet('date_from');
        $dateTo        = $request->getGet('date_to');
        $q             = $request->getGet('q');               // order number search

        // --- Summary: total spent & total orders (lifetime) ---
        $summary = $this->orders
            ->select('COUNT(*) AS total_orders, COALESCE(SUM(grand_total),0) AS total_spent')
            ->where('customer_id', $id)
            ->get()
            ->getRowArray();

        // --- Status wise counts (for chips) ---
        $statusRows = $this->orders
            ->select('status, COUNT(*) AS total')
            ->where('customer_id', $id)
            ->groupBy('status')
            ->findAll();

        $statusCounts = [];
        foreach ($statusRows as $row) {
            $statusCounts[$row['status']] = (int) $row['total'];
        }

        // --- Payment status wise counts (for chips) ---
        $payRows = $this->orders
            ->select('payment_status, COUNT(*) AS total')
            ->where('customer_id', $id)
            ->groupBy('payment_status')
            ->findAll();

        $paymentCounts = [];
        foreach ($payRows as $row) {
            $paymentCounts[$row['payment_status']] = (int) $row['total'];
        }

        // --- Main Orders query with filters (high speed: paginated, indexed by customer_id) ---
        $builder = $this->orders
            ->where('customer_id', $id)
            ->orderBy('created_at', 'DESC');

        if (!empty($status)) {
            $builder->where('status', $status);
        }

        if (!empty($paymentStatus)) {
            $builder->where('payment_status', $paymentStatus);
        }

        if (!empty($dateFrom)) {
            $builder->where('DATE(created_at) >=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $builder->where('DATE(created_at) <=', $dateTo);
        }

        if (!empty($q)) {
            $builder->like('order_number', $q);
        }

        $orders     = $builder->paginate(10, 'cust_orders');
        $orderPager = $this->orders->pager;

        $data = [
            'title'          => 'Customer Profile',
            'customer'       => $customer,
            'addresses'      => $addresses,
            'orders'         => $orders,
            'orderPager'     => $orderPager,
            'summary'        => $summary,
            'statusCounts'   => $statusCounts,
            'paymentCounts'  => $paymentCounts,
            // filters back to view
            'filter_status'        => $status,
            'filter_paymentStatus' => $paymentStatus,
            'filter_dateFrom'      => $dateFrom,
            'filter_dateTo'        => $dateTo,
            'filter_q'             => $q,
        ];

        return view('admin/customers/show', $data);
    }
}