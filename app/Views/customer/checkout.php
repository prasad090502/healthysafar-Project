<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// ------- Safe defaults so view never breaks -------
$cartItems         = $cartItems         ?? [];
$addresses         = $addresses         ?? [];
$selectedAddressId = $selectedAddressId ?? null;

// Totals (if controller didn’t pre-compute them)
$subTotal       = $subTotal       ?? 0;
$shippingAmount = $shippingAmount ?? 0;
$orderTotal     = $orderTotal     ?? ($subTotal + $shippingAmount);
$currencySymbol = $currencySymbol ?? '₹';

// If controller passed totals per cart item, fine;
// if not, we can recalc:
if ($subTotal === 0 && ! empty($cartItems)) {
    $subTotal = 0;
    foreach ($cartItems as $ci) {
        $qty   = (int)($ci['qty'] ?? 1);
        $price = (float)($ci['final_price'] ?? $ci['price'] ?? 0);
        $subTotal += $qty * $price;
    }
    $orderTotal = $subTotal + $shippingAmount;
}
?>

<!--==============================
 Breadcumb
==============================-->
<div class="breadcumb-wrapper" data-bg-src="<?= base_url('assets/img/bg/breadcumb-bg.jpg') ?>">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Checkout</h1>
            <ul class="breadcumb-menu">
                <li><a href="<?= site_url('/') ?>">Home</a></li>
                <li><a href="<?= site_url('cart') ?>">Cart</a></li>
                <li>Checkout</li>
            </ul>
        </div>
    </div>
</div>

<!--==============================
 Checkout Area
==============================-->
<div class="th-cart-wrapper space-top space-extra-bottom">
    <div class="container">

        <?php if (empty($cartItems)): ?>
            <!-- Empty cart: no checkout -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="empty-cart-box text-center">
                        <i class="far fa-shopping-cart"></i>
                        <h4 class="mb-2">Your cart is empty</h4>
                        <p>Add some fresh & healthy products to proceed to checkout.</p>
                        <a href="<?= site_url('shop') ?>" class="th-btn">
                            Browse Shop<i class="far fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>

            <form action="<?= site_url('checkout/place') ?>" method="post" class="hs-checkout-form">
                <?= csrf_field() ?>

                <div class="row g-4">
                    <!-- LEFT: Address selection -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm border-0 rounded-4 mb-3">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-0">Delivery Address</h5>
                                        <p class="small text-muted mb-0">
                                            Select one of your saved addresses for this order.
                                        </p>
                                    </div>
                                    <a href="<?= site_url('customer/addresses/add') ?>"
                                       class="btn btn-sm btn-outline-success">
                                        <i class="far fa-plus me-1"></i>Add New
                                    </a>
                                </div>

                                <?php if (empty($addresses)): ?>
                                    <div class="alert alert-info small mb-0">
                                        You don’t have any saved delivery addresses yet.
                                        <br>
                                        <a href="<?= site_url('customer/addresses/add') ?>">Add a new address</a>
                                        to continue.
                                    </div>
                                <?php else: ?>
                                    <div class="vstack gap-2">
                                        <?php foreach ($addresses as $addr): ?>
                                            <?php
                                                $id        = (int)($addr['id'] ?? 0);
                                                $isDefault = !empty($addr['is_default']);
                                                // preselect default or previously selected
                                                $checked   = $selectedAddressId
                                                    ? ($selectedAddressId == $id)
                                                    : $isDefault;
                                                $type      = ucfirst($addr['address_type'] ?? 'Home');
                                            ?>
                                            <label class="hs-address-card form-check mb-0">
                                                <input
                                                    class="form-check-input me-2"
                                                    type="radio"
                                                    name="shipping_address_id"
                                                    value="<?= $id ?>"
                                                    <?= $checked ? 'checked' : '' ?>
                                                >
                                                <div class="hs-address-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="fw-semibold">
                                                                <?= esc($addr['name'] ?? 'Receiver') ?>
                                                            </span>
                                                            <span class="badge bg-light text-success border small">
                                                                <i class="far fa-house me-1"></i>
                                                                <?= esc($type) ?>
                                                            </span>
                                                        </div>
                                                        <?php if ($isDefault): ?>
                                                            <span class="badge bg-success-subtle text-success small">
                                                                <i class="far fa-star me-1"></i>Default
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="small mb-1">
                                                        <?= esc($addr['address_line1'] ?? '') ?><br>
                                                        <?php if (!empty($addr['address_line2'])): ?>
                                                            <?= esc($addr['address_line2']) ?><br>
                                                        <?php endif; ?>
                                                        <?php if (!empty($addr['landmark'])): ?>
                                                            <?= esc($addr['landmark']) ?><br>
                                                        <?php endif; ?>
                                                        <?= esc($addr['city'] ?? '') ?>
                                                        <?php if (!empty($addr['pincode'])): ?>
                                                            - <?= esc($addr['pincode']) ?>
                                                        <?php endif; ?><br>
                                                        <?= esc($addr['state'] ?? '') ?>
                                                        <?php if (!empty($addr['country'])): ?>
                                                            , <?= esc($addr['country']) ?>
                                                        <?php endif; ?>
                                                    </p>
                                                    <p class="small text-muted mb-0">
                                                        <i class="far fa-phone-alt me-1"></i>
                                                        <?= esc($addr['phone'] ?? '-') ?>
                                                        <?php if (!empty($addr['alternate_phone'])): ?>
                                                            <span class="ms-2">
                                                                Alt: <?= esc($addr['alternate_phone']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                    <p class="small text-muted mt-2 mb-0">
                                        We currently deliver in selected areas. Our team may contact you
                                        if delivery is not available at your pincode.
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Payment method placeholder (simple for now) -->
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Payment Method</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="pmCod" value="cod" checked>
                                    <label class="form-check-label" for="pmCod">
                                        Cash on Delivery (if available in your area)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="pmOnline" value="online" disabled>
                                    <label class="form-check-label text-muted" for="pmOnline">
                                        Online Payment (coming soon)
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT: Order summary -->
                    <div class="col-lg-5">
                        <h5 class="mb-3">Order Summary</h5>

                        <div class="card shadow-sm border-0 rounded-4 mb-3">
                            <div class="card-body p-3">
                                <?php foreach ($cartItems as $item): ?>
                                    <?php
                                        $title = $item['title'] ?? $item['name'] ?? 'Product';
                                        $qty   = (int)($item['qty'] ?? 1);
                                        $price = (float)($item['final_price'] ?? $item['price'] ?? 0);
                                        $rowTotal = $qty * $price;
                                    ?>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <div>
                                            <div class="fw-semibold"><?= esc($title) ?></div>
                                            <div class="text-muted">
                                                Qty: <?= $qty ?> × <?= $currencySymbol . number_format($price, 2) ?>
                                            </div>
                                        </div>
                                        <div class="fw-semibold">
                                            <?= $currencySymbol . number_format($rowTotal, 2) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <table class="cart_totals mb-3">
                            <tbody>
                                <tr>
                                    <th>Cart Subtotal</th>
                                    <td>
                                        <span class="amount">
                                            <bdi><span><?= $currencySymbol ?></span><?= number_format($subTotal, 2) ?></bdi>
                                        </span>
                                    </td>
                                </tr>
                                <tr class="shipping">
                                    <th>Shipping</th>
                                    <td>
                                        <?php if ($shippingAmount <= 0): ?>
                                            <p class="mb-0 small">
                                                Calculated based on delivery area or free for eligible orders.
                                            </p>
                                        <?php else: ?>
                                            <span class="amount">
                                                <bdi><span><?= $currencySymbol ?></span><?= number_format($shippingAmount, 2) ?></bdi>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <th>Order Total</th>
                                    <td>
                                        <strong>
                                            <span class="amount">
                                                <bdi><span><?= $currencySymbol ?></span><?= number_format($orderTotal, 2) ?></bdi>
                                            </span>
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <button type="submit" class="th-btn w-100">
                            Place Order<i class="far fa-chevron-right ms-2"></i>
                        </button>

                        <p class="small text-muted mt-2 mb-0">
                            By placing your order you agree to our Terms &amp; Conditions and refund policy.
                        </p>
                    </div>
                </div> <!-- /.row -->
            </form>

        <?php endif; ?>
    </div>
</div>

<style>
    .hs-address-card {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 14px;
        border: 1px solid rgba(0,0,0,.06);
        background: #fff;
        cursor: pointer;
        transition: all .15s ease;
    }
    .hs-address-card:hover {
        border-color: rgba(73,177,190,.7);
        box-shadow: 0 8px 24px rgba(0,0,0,.04);
        background: #f9fbfa;
    }
    .hs-address-card .form-check-input {
        margin-top: 4px;
    }
    .hs-address-body {
        flex: 1;
    }

    .empty-cart-box {
        background: #fff;
        border-radius: 22px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 18px 50px rgba(0,0,0,.06);
    }
    .empty-cart-box i {
        font-size: 40px;
        color: #49b1be;
        margin-bottom: 10px;
    }
    .cart_totals {
        width: 100%;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 18px 50px rgba(0,0,0,.06);
        border-collapse: collapse;
        font-size: 14px;
    }
    .cart_totals td,
    .cart_totals th {
        padding: 10px 16px;
        border-bottom: 1px dashed rgba(0,0,0,.06);
    }
    .cart_totals tr:last-child td,
    .cart_totals tr:last-child th {
        border-bottom: none;
    }
    .cart_totals th {
        font-weight: 600;
        color: #444;
    }
    .cart_totals .order-total td {
        font-weight: 700;
        font-size: 18px;
        color: #2f7f67;
    }

    @media (max-width: 767.98px) {
        .hs-address-card {
            padding: 10px;
        }
    }
</style>

<?= $this->endSection() ?>