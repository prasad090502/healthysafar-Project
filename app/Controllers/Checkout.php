<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Razorpay\Api\Api;
use Config\Razorpay as RazorpayConfig;

class Checkout extends BaseController
{
    protected $session;
    protected $db;
    protected string $cartKey = 'cart';

    public function __construct()
    {
        $this->session = session();
        $this->db      = \Config\Database::connect();
        helper(['url', 'form']);
    }

    /* -------------------- Common helpers -------------------- */

    protected function requireLogin()
    {
        if (! $this->session->get('isCustomerLoggedIn')) {
            return redirect()->to(site_url('customer/login'))
                ->with('error', 'Please login to proceed to checkout.');
        }
        return null;
    }

    protected function getCart(): array
    {
        return (array) $this->session->get($this->cartKey);
    }

    protected function setCart(array $cart): void
    {
        $cart = array_filter($cart, static function ($item) {
            return isset($item['qty']) && $item['qty'] > 0;
        });

        $this->session->set($this->cartKey, $cart);
    }

    protected function getCartItemCount(): int
    {
        $cart  = $this->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)($item['qty'] ?? 0);
        }
        return $count;
    }

    /**
     * Build normalized items + totals from session cart.
     */
    protected function buildCartSummary(): array
    {
        $cart      = $this->getCart();
        $items     = [];
        $subTotal  = 0;
        $currencySymbol = '₹';

        foreach ($cart as $rowId => $line) {
            $qty = (int)($line['qty'] ?? 1);
            if ($qty < 1) {
                $qty = 1;
            }

            $price     = (float)($line['price'] ?? 0);
            $salePrice = (float)($line['sale_price'] ?? 0);
            $final     = (float)($line['final_price'] ?? 0);

            if ($final <= 0) {
                if ($salePrice > 0 && $salePrice < $price) {
                    $final = $salePrice;
                } else {
                    $final = $price;
                }
            }

            $rowTotal = $final * $qty;
            $subTotal += $rowTotal;

            $img = $line['main_image'] ?? $line['image'] ?? 'assets/img/product/product_thumb_1_1.jpg';

            $isSubscription      = (int)($line['is_subscription'] ?? 0);
            $subMeta             = $line['subscription_meta'] ?? null;
            $subscriptionPlanId  = $line['subscription_plan_id'] ?? null;

            $items[] = [
                'row_id'              => $rowId,
                'product_id'          => $line['product_id'] ?? $rowId,
                'name'                => $line['name'] ?? 'Product',
                'slug'                => $line['slug'] ?? null,
                'image'               => $img,
                'price'               => $price,
                'sale_price'          => $salePrice ?: null,
                'final_price'         => $final,
                'qty'                 => $qty,
                'row_total'           => $rowTotal,
                'is_subscription'     => $isSubscription,
                'subscription_meta'   => $subMeta,
                'subscription_plan_id'=> $subscriptionPlanId,
            ];
        }

        $shippingAmount = 0;
        $orderTotal     = $subTotal + $shippingAmount;

        return [
            'items'           => $items,
            'subTotal'        => $subTotal,
            'shippingAmount'  => $shippingAmount,
            'orderTotal'      => $orderTotal,
            'currencySymbol'  => $currencySymbol,
            'cartCount'       => $this->getCartItemCount(),
        ];
    }

    /* =============================
     * GET /checkout
     * ============================= */
    public function index()
    {
        // We do NOT force login just to view the page
        $summary = $this->buildCartSummary();

        if (empty($summary['items'])) {
            return redirect()->to(site_url('cart'))
                ->with('cart_notice', 'Your cart is empty. Add products before checkout.');
        }

        // user addresses
        $addresses         = [];
        $selectedAddressId = null;
        $customerId        = (int) $this->session->get('customer_id');

        if ($customerId > 0) {
            $addresses = $this->db->table('customer_addresses')
                ->where('customer_id', $customerId)
                ->orderBy('is_default', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()->getResultArray();

           // If user added a new address → pre-select that
$requestedAddressId = (int) $this->request->getGet('addr');
if ($requestedAddressId > 0) {
    $selectedAddressId = $requestedAddressId;

    // Make selected one appear FIRST
    $addresses = $this->db->table('customer_addresses')
        ->where('customer_id', $customerId)
        ->orderBy("id = {$requestedAddressId}", 'DESC', false)
        ->orderBy('is_default', 'DESC')
        ->orderBy('id', 'DESC')
        ->get()->getResultArray();
} else {
    // Normal default address logic
    foreach ($addresses as $adr) {
        if (! empty($adr['is_default'])) {
            $selectedAddressId = (int) $adr['id'];
            break;
        }
    }
}
        }

        return view('shop/checkout', [
            'cartItems'        => $summary['items'],
            'subTotal'         => $summary['subTotal'],
            'shippingAmount'   => $summary['shippingAmount'],
            'orderTotal'       => $summary['orderTotal'],
            'currencySymbol'   => $summary['currencySymbol'],
            'cartCount'        => $summary['cartCount'],
            'addresses'        => $addresses,
            'selectedAddressId'=> $selectedAddressId,
        ]);
    }

    /* =============================
     * POST /checkout/place   (AJAX)
     * ============================= */
    public function placeOrder()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $summary = $this->buildCartSummary();
        if (empty($summary['items'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Your cart is empty. Add products before checkout.',
                ]);
            }

            return redirect()->to(site_url('cart'))
                ->with('cart_notice', 'Your cart is empty. Add products before checkout.');
        }

        $customerId    = (int)$this->session->get('customer_id');
        $request       = service('request');

        $addressId     = (int)$request->getPost('shipping_address_id');
        $orderNote     = trim((string)$request->getPost('order_note'));

        if ($addressId <= 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please select a delivery address.',
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select a delivery address.');
        }

        $address = $this->db->table('customer_addresses')
            ->where('id', $addressId)
            ->where('customer_id', $customerId)
            ->get()->getRowArray();

        if (! $address) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid delivery address selected.',
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid delivery address selected.');
        }
        

        // Totals
        $subTotal       = $summary['subTotal'];
        $shippingAmount = $summary['shippingAmount'];
        $orderTotal     = $summary['orderTotal'];

        // Simple order number
        $orderNumber = 'HS' . date('YmdHis') . $customerId;

        $orderData = [
            'customer_id'         => $customerId,
            'order_number'        => $orderNumber,
            'status'              => 'pending',
            'payment_status'      => 'pending',        // always pending initially
            'payment_method'      => 'online',         // only Razorpay now
            'subtotal'            => $subTotal,
            'tax_amount'          => 0,
            'shipping_amount'     => $shippingAmount,
            'discount_amount'     => 0,
            'grand_total'         => $orderTotal,
            'currency'            => 'INR',
            'shipping_address_id' => $addressId,
            'billing_address_id'  => $addressId,
            'notes'               => $orderNote,
            'created_at'          => date('Y-m-d H:i:s'),
        ];

        $this->db->transStart();

        // 1) Insert into orders
        $this->db->table('orders')->insert($orderData);
        $orderId = $this->db->insertID();

        // 2) Insert order items
        foreach ($summary['items'] as $item) {
            $this->db->table('order_items')->insert([
                'order_id'     => $orderId,
                'product_id'   => $item['product_id'],
                'product_name' => $item['name'],
                'product_sku'  => null,
                'qty'          => $item['qty'],
                'unit_price'   => $item['final_price'],
                'total_price'  => $item['row_total'],
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        }

        // 3) Subscription items (same as before)
        foreach ($summary['items'] as $item) {
            if (empty($item['is_subscription'])) {
                continue;
            }

            $meta   = $item['subscription_meta'] ?? [];
            $planId = (int)($item['subscription_plan_id'] ?? ($meta['subscription_plan_id'] ?? 0));
            $durationDays = (int)($meta['duration_days'] ?? 0);
            $startDate    = $meta['start_date'] ?? null;
            $endDate      = $meta['end_date']   ?? null;
            $slotKey      = $meta['slot_key']   ?? null;

            if ($planId <= 0 || $durationDays <= 0 || empty($startDate) || empty($slotKey)) {
                continue;
            }

            $configRow = $this->db->table('subscription_plan_config')
                ->where('subscription_plan_id', $planId)
                ->get()->getRowArray();

            $offDays = [];
            $postponementLimit = 0;
            if ($configRow) {
                if (!empty($configRow['off_days_json'])) {
                    $offDays = json_decode($configRow['off_days_json'], true) ?: [];
                }
                $postponementLimit = (int)($configRow['postponement_limit'] ?? 0);
            }

            $deliveryDates = $this->generateDeliveryDates($startDate, $durationDays, $offDays);
            $totalDeliveriesPlanned = count($deliveryDates);

            $subscriptionData = [
                'user_id'                 => null,
                'customer_id'             => $customerId,
                'subscription_plan_id'    => $planId,
                'duration_days'           => $durationDays,
                'start_date'              => $startDate,
                'end_date'                => $endDate ?: end($deliveryDates),
                'total_deliveries_planned'=> $totalDeliveriesPlanned,
                'postponement_limit'      => $postponementLimit,
                'postponement_used'       => 0,
                'base_address_id'         => $addressId,
                'default_slot_key'        => $slotKey,
                'total_price'             => $item['row_total'],
                'status'                  => 'pending',   // will mark active after payment success
                'created_at'              => date('Y-m-d H:i:s'),
            ];

            $this->db->table('subscriptions')->insert($subscriptionData);
            $subscriptionId = $this->db->insertID();

            foreach ($deliveryDates as $deliveryDate) {
                $this->db->table('subscription_deliveries')->insert([
                    'subscription_id'      => $subscriptionId,
                    'subscription_plan_id' => $planId,
                    'user_id'              => null,
                    'customer_id'          => $customerId,
                    'delivery_date'        => $deliveryDate,
                    'base_address_id'      => $addressId,
                    'override_address_id'  => null,
                    'base_slot_key'        => $slotKey,
                    'override_slot_key'    => null,
                    'status'               => 'pending',
                    'notes'                => null,
                    'is_generated_extension'=> 0,
                    'created_at'           => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Could not place order right now. Please try again.',
                ]);
            }

            return redirect()->back()
                ->with('error', 'Could not place order right now. Please try again.');
        }

        // ---------- Prepare customer for Razorpay prefill ----------
        $customer = [
            'name'  => (string)($this->session->get('customer_name') ?? ''),
            'email' => (string)($this->session->get('customer_email') ?? ''),
            'phone' => (string)($this->session->get('customer_phone') ?? ''),
        ];

        // If phone not in session, load from customers.contact
        if ($customer['phone'] === '' && $customerId > 0) {
            $row = $this->db->table('customers')
                ->select('contact')   // your column name
                ->where('id', $customerId)
                ->get()->getRowArray();

            if ($row && !empty($row['contact'])) {
                $customer['phone'] = $row['contact'];
                $this->session->set('customer_phone', $row['contact']);
            }
        }

        // ---------- Create Razorpay Order ----------
        $rzConfig = new RazorpayConfig();
        $api      = new Api($rzConfig->keyId, $rzConfig->keySecret);

        $razorpayAmount = (int) round($orderTotal * 100);

        $razorpayOrder = $api->order->create([
            'receipt'         => 'rcpt_' . $orderId,
            'amount'          => $razorpayAmount,
            'currency'        => $rzConfig->currency,
            'payment_capture' => 1,
            'notes'           => [
                'order_id'    => $orderId,
                'order_number'=> $orderNumber,
                'customer_id' => $customerId,
            ],
        ]);

        // Save razorpay_order_id on orders table
        $this->db->table('orders')
            ->where('id', $orderId)
            ->update([
                'razorpay_order_id' => $razorpayOrder['id'] ?? null,
            ]);

        $this->session->set('current_order_id', $orderId);

        // ---------- Return JSON for AJAX (open Razorpay on same page) ----------
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'      => true,
                'message'      => 'Order created. Redirecting to payment...',
                'orderId'      => $orderId,
                'orderNumber'  => $orderNumber,
                'razorpay'     => [
                    'key'      => $rzConfig->keyId,
                    'amount'   => $razorpayAmount,
                    'currency' => $rzConfig->currency,
                    'orderId'  => $razorpayOrder['id'],
                ],
                'customer'     => $customer,
            ]);
        }

        // Fallback (non-AJAX): you could redirect to a simple "processing" page, but
        // in your case everything is via AJAX from checkout, so this path is rarely used.
        return redirect()->to(site_url('checkout'))
            ->with('error', 'Unexpected request type. Please try again.');
    }

    /* =============================
     * POST /checkout/razorpay-success
     * ============================= */
    public function razorpaySuccess()
    {
        $request = service('request');
        if (! $this->request->is('post')) {
            return redirect()->to(site_url('/'));
        }

        $paymentId    = $request->getPost('razorpay_payment_id');
        $orderIdRzp   = $request->getPost('razorpay_order_id');
        $signature    = $request->getPost('razorpay_signature');
        $localOrderId = (int)$request->getPost('local_order_id');

        if (! $paymentId || ! $orderIdRzp || ! $signature || ! $localOrderId) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Invalid payment response. Please contact support.');
        }

        $order = $this->db->table('orders')
            ->where('id', $localOrderId)
            ->get()->getRowArray();

        if (! $order || empty($order['razorpay_order_id'])) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Order not found or not linked with Razorpay.');
        }

        $rzConfig = new RazorpayConfig();

        try {
            $generatedSignature = hash_hmac(
                'sha256',
                $orderIdRzp . '|' . $paymentId,
                $rzConfig->keySecret
            );

            if (! hash_equals($generatedSignature, $signature)) {
                return redirect()->to(site_url('customer/orders'))
                    ->with('error', 'Payment signature verification failed.');
            }

            // Signature OK → update order as paid
            $this->db->table('orders')
                ->where('id', $localOrderId)
                ->update([
                    'payment_status'      => 'paid',
                    'status'              => 'confirmed',
                    'razorpay_payment_id' => $paymentId,
                    'updated_at'          => date('Y-m-d H:i:s'),
                ]);

            // Activate subscriptions now (if any)
            $this->db->table('subscriptions')
                ->where('customer_id', (int)$order['customer_id'])
                ->where('status', 'pending')
                ->update([
                    'status'     => 'active',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            // Clear cart/session
            $this->session->remove($this->cartKey);
            $this->session->remove('current_order_id');

            return redirect()->to(site_url('customer/orders'))
                ->with('success', 'Payment successful! Your order #' . $order['order_number'] . ' is confirmed.');

        } catch (\Throwable $e) {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /* =============================
     * POST /checkout/razorpay-failed
     * (user closed popup OR Razorpay payment.failed event)
     * ============================= */
    public function razorpayFailed()
    {
        $request      = service('request');
        $localOrderId = (int)($request->getPost('local_order_id') ?? 0);
        $reason       = (string)($request->getPost('reason') ?? 'Payment failed or cancelled.');

        if ($localOrderId <= 0) {
            $localOrderId = (int)$this->session->get('current_order_id');
        }

        if ($localOrderId > 0) {
            $this->db->table('orders')
                ->where('id', $localOrderId)
                ->update([
                    'payment_status' => 'failed',
                    'status'         => 'payment_failed',
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);
        }

        return redirect()->to(site_url('checkout'))
            ->with('error', 'Payment was cancelled or failed. ' . $reason);
    }

    /**
     * Generate a list of delivery dates, skipping off-days.
     * offDays example: ["SUN", "SAT"]
     */
    protected function generateDeliveryDates(string $startDate, int $durationDays, array $offDays): array
    {
        $dates   = [];
        $offDays = array_map('strtoupper', $offDays);

        $date = new \DateTime($startDate);

        // First day is always included
        $dates[] = $date->format('Y-m-d');
        $count = 1;

        while ($count < $durationDays) {
            $date->modify('+1 day');
            $dayCode = strtoupper($date->format('D')); // MON, TUE, WED...

            if (in_array($dayCode, $offDays, true)) {
                continue;
            }

            $dates[] = $date->format('Y-m-d');
            $count++;
        }

        return $dates;
    }
}