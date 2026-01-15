<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CustomerModel;
use App\Models\CustomerAddressModel;

class Orders extends AdminBaseController
{
    protected $orders;
    protected $orderItems;
    protected $customers;
    protected $addresses;
    protected $perPage = 15;

    public function __construct()
    {
        $this->orders     = new OrderModel();
        $this->orderItems = new OrderItemModel();
        $this->customers  = new CustomerModel();
        $this->addresses  = new CustomerAddressModel();
    }

    /**
     * Orders index with filters + metrics + inline status
     */
    public function index()
    {
        $request = $this->request;

        $status        = $request->getGet('status');
        $paymentStatus = $request->getGet('payment_status');
        $dateFrom      = $request->getGet('date_from');
        $dateTo        = $request->getGet('date_to');
        $q             = $request->getGet('q');

        $builder = $this->orders
            ->select('orders.*, customers.name AS customer_name, customers.email AS customer_email')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->orderBy('orders.created_at', 'DESC');

        if (!empty($status) && $status !== 'all') {
            $builder->where('orders.status', $status);
        }

        if (!empty($paymentStatus) && $paymentStatus !== 'all') {
            $builder->where('orders.payment_status', $paymentStatus);
        }

        if (!empty($dateFrom)) {
            $builder->where('DATE(orders.created_at) >=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $builder->where('DATE(orders.created_at) <=', $dateTo);
        }

        if (!empty($q)) {
            $builder->groupStart()
                ->like('orders.order_number', $q)
                ->orLike('customers.name', $q)
                ->groupEnd();
        }

        $orders = $builder->paginate($this->perPage);
        $pager  = $this->orders->pager;

        // Quick status stats for header
        $statusStats = $this->orders
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->findAll();

        $statusSummary = [
            'pending'    => 0,
            'processing' => 0,
            'shipped'    => 0,
            'delivered'  => 0,
            'cancelled'  => 0,
            'refunded'   => 0,
        ];
        foreach ($statusStats as $row) {
            if (isset($statusSummary[$row['status']])) {
                $statusSummary[$row['status']] = (int) $row['total'];
            }
        }

        // Metrics: total, revenue, avg order, today orders
        $metrics = $this->orders
            ->select('COUNT(*) AS total_orders, COALESCE(SUM(grand_total),0) AS total_revenue')
            ->get()->getRowArray();

        $today = date('Y-m-d');
        $todayOrders = $this->orders
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        $avgOrderValue = 0;
        if (!empty($metrics['total_orders']) && $metrics['total_orders'] > 0) {
            $avgOrderValue = $metrics['total_revenue'] / $metrics['total_orders'];
        }

        $data = [
            'title'          => 'Orders',
            'orders'         => $orders,
            'pager'          => $pager,
            'filters'        => [
                'status'         => $status ?: 'all',
                'payment_status' => $paymentStatus ?: 'all',
                'date_from'      => $dateFrom,
                'date_to'        => $dateTo,
                'q'              => $q,
            ],
            'statusSummary'  => $statusSummary,
            'statusOptions'  => ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'],
            'paymentOptions' => ['pending', 'paid', 'failed', 'refunded'],
            'metrics'        => [
                'total_orders'    => (int)($metrics['total_orders'] ?? 0),
                'total_revenue'   => (float)($metrics['total_revenue'] ?? 0),
                'avg_order_value' => (float)$avgOrderValue,
                'today_orders'    => (int)$todayOrders,
            ],
        ];

        return view('admin/orders/index', $data);
    }

    /**
     * Show single order details + customer order history
     */
    public function show($id)
    {
        $order = $this->orders
            ->select('orders.*, customers.name AS customer_name, customers.email AS customer_email, customers.contact AS customer_contact')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->where('orders.id', $id)
            ->get()
            ->getRowArray();

        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Order not found');
        }

        // Addresses
        $shipping = null;
        $billing  = null;

        if (!empty($order['shipping_address_id'])) {
            $shipping = $this->addresses->find($order['shipping_address_id']);
        }
        if (!empty($order['billing_address_id'])) {
            $billing = $this->addresses->find($order['billing_address_id']);
        }

        // Items
        $items = $this->orderItems
            ->where('order_id', $id)
            ->findAll();

        // Customer order history (last 10 orders of this customer)
        $customerOrders = [];
        if (!empty($order['customer_id'])) {
            $customerOrders = $this->orders
                ->where('customer_id', $order['customer_id'])
                ->orderBy('created_at', 'DESC')
                ->findAll(10);
        }

        $statusOptions  = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        $paymentOptions = ['pending', 'paid', 'failed', 'refunded'];

        $data = [
            'title'          => 'Order Details',
            'order'          => $order,
            'shipping'       => $shipping,
            'billing'        => $billing,
            'items'          => $items,
            'statusOptions'  => $statusOptions,
            'paymentOptions' => $paymentOptions,
            'customerOrders' => $customerOrders,
        ];

        return view('admin/orders/show', $data);
    }

    /**
     * Regular POST update (from order detail page)
     */
    public function updateStatus($id)
    {
        $order = $this->orders->find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $status        = $this->request->getPost('status');
        $paymentStatus = $this->request->getPost('payment_status');

        $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        $allowedPayments = ['pending', 'paid', 'failed', 'refunded'];

        $data = [];

        if ($status && in_array($status, $allowedStatuses, true)) {
            $data['status'] = $status;
        }

        if ($paymentStatus && in_array($paymentStatus, $allowedPayments, true)) {
            $data['payment_status'] = $paymentStatus;
        }

        if (!empty($data)) {
            $this->orders->update($id, $data);
            return redirect()->to('admin/orders/' . $id)->with('success', 'Order updated successfully.');
        }

        return redirect()->to('admin/orders/' . $id)->with('error', 'No valid changes to update.');
    }

    /**
     * Inline AJAX update (single or bulk) from index page
     * POST: order_id (or order_ids[]), status, payment_status, mode=single|bulk
     * Returns JSON
     */
    public function updateStatusInline()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $mode          = $this->request->getPost('mode') ?? 'single';
        $status        = $this->request->getPost('status');
        $paymentStatus = $this->request->getPost('payment_status');

        $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        $allowedPayments = ['pending', 'paid', 'failed', 'refunded'];

        $data = [];
        if ($status && in_array($status, $allowedStatuses, true)) {
            $data['status'] = $status;
        }
        if ($paymentStatus && in_array($paymentStatus, $allowedPayments, true)) {
            $data['payment_status'] = $paymentStatus;
        }

        if (empty($data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No valid changes']);
        }

        if ($mode === 'bulk') {
            $ids = $this->request->getPost('order_ids');
            if (!is_array($ids) || empty($ids)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'No orders selected']);
            }
            foreach ($ids as $id) {
                $this->orders->update((int)$id, $data);
            }
        } else {
            $id = (int)$this->request->getPost('order_id');
            if (!$id) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Missing order id']);
            }
            $this->orders->update($id, $data);
        }

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => 'Order(s) updated successfully',
            'data'    => $data,
        ]);
    }

    /**
     * Print labels for selected orders
     * GET params:
     * - order_ids=1,2,3
     * - fields[] = order_number, customer_name, phone, address, city, state, pincode, grand_total
     * - label_size (A4_3x8, A4_2x7, TH_100x150, etc.)
     * - page_size (A4, Letter)
     */
    public function printLabels()
    {
        $idsParam = $this->request->getGet('order_ids');
        if (empty($idsParam)) {
            return redirect()->back()->with('error', 'No orders selected for label printing.');
        }

        $idsRaw = explode(',', $idsParam);
        $ids    = array_filter(array_map('intval', $idsRaw));

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Invalid orders selected.');
        }

        $selectedFields = $this->request->getGet('fields') ?? [];
        if (!is_array($selectedFields)) {
            $selectedFields = [$selectedFields];
        }

        $labelSize = $this->request->getGet('label_size') ?: 'A4_3x8';
        $pageSize  = $this->request->getGet('page_size') ?: 'A4';

        $orders = $this->orders
            ->select('orders.*, customers.name AS customer_name, customers.contact AS customer_contact')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->whereIn('orders.id', $ids)
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        // Attach shipping address to each order
        foreach ($orders as &$order) {
            $order['shipping'] = null;
            if (!empty($order['shipping_address_id'])) {
                $order['shipping'] = $this->addresses->find($order['shipping_address_id']);
            }
        }

        $data = [
            'title'          => 'Print Order Labels',
            'orders'         => $orders,
            'selectedFields' => $selectedFields,
            'labelSize'      => $labelSize,
            'pageSize'       => $pageSize,
        ];

        return view('admin/orders/label_print', $data);
    }
}