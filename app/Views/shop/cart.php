<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// ----------------- Guards & normalisation -----------------
$items = $cartItems ?? [];

// currency
$currencySymbol = '₹';

// calculate totals
$subTotal = 0;

foreach ($items as &$item) {
    $title   = $item['name'] ?? $item['title'] ?? 'Product';
    $item['title'] = $title;

    // slug + product URL
    $slug    = $item['slug'] ?? '';
    $item['url'] = $slug ? site_url('product/' . $slug) : '#';

    // price
    $price      = (float)($item['price'] ?? 0);
    $salePrice  = (float)($item['sale_price'] ?? 0);
    $finalPrice = (float)($item['final_price'] ?? 0);

    if ($finalPrice <= 0) {
        if ($salePrice > 0 && $salePrice < $price) {
            $finalPrice = $salePrice;
        } else {
            $finalPrice = $price;
        }
    }
    $item['final_price'] = $finalPrice;

    // qty
    $qty = (int)($item['qty'] ?? 1);
    if ($qty < 1) {
        $qty = 1;
    }
    $item['qty'] = $qty;

    // row total
    $rowTotal = isset($item['row_total'])
        ? (float)$item['row_total']
        : $finalPrice * $qty;

    $item['row_total'] = $rowTotal;
    $subTotal += $rowTotal;

    // ---------- IMAGE GUARD (use real upload path / full URL) ----------
    $rawImg = $item['main_image']
        ?? $item['image']
        ?? $item['image_url']
        ?? '';

    $rawImg = trim((string)$rawImg);

    if ($rawImg === '' || strtolower($rawImg) === 'null') {
        // fallback placeholder
        $item['image_url'] = base_url('assets/img/product/product_thumb_1_1.jpg');
    } elseif (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://')) {
        // full URL (CDN / external)
        $item['image_url'] = $rawImg;
    } else {
        // relative path, e.g. "uploads/products/1763938362_343dd014aee500162a0b.png"
        $item['image_url'] = base_url($rawImg);
    }
    // -------------------------------------------------------------------

    // row id for forms (update/remove)
    $item['row_id'] = $item['row_id'] ?? $item['id'] ?? null;
}
unset($item);

// shipping & total (simple – adjust as per your logic)
$shippingAmount = 0; // e.g. 0 now, or 49 etc.
$orderTotal     = $subTotal + $shippingAmount;

// Show totals formatted
$fmtSubTotal    = number_format($subTotal, 2);
$fmtShipping    = number_format($shippingAmount, 2);
$fmtOrderTotal  = number_format($orderTotal, 2);

// Simple notice example – you can set from controller
$cartNotice = $cartNotice ?? null;

// Login flag from controller
$isCustomerLoggedIn = $isCustomerLoggedIn ?? false;
?>

<style>
    /* Overall page background */
    .th-cart-wrapper {
        position: relative;
        background: radial-gradient(circle at top left,
            rgba(73,177,190,.06),
            rgba(251,203,50,.02),
            #f7faf8);
    }

    .th-cart-wrapper .container {
        position: relative;
        z-index: 1;
    }

    .cart_table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 18px 50px rgba(0,0,0,.06);
    }

    .cart_table th,
    .cart_table td {
        padding: 14px 16px;
        font-size: 14px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0,0,0,.04);
    }

    .cart_table thead {
        background: linear-gradient(135deg,
            rgba(73,177,190,.12),
            rgba(251,203,50,.12));
    }

    .cart_table th {
        font-weight: 600;
        color: #233d32;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 12px;
    }

    .cart_table tbody tr:last-child td {
        border-bottom: none;
    }

    .cart-productimage img {
        border-radius: 14px;
        width: 80px;
        height: 80px;
        object-fit: cover;
        box-shadow: 0 6px 18px rgba(0,0,0,.09);
    }

    .cart-productname {
        font-weight: 600;
        color: #2f7f67;
        text-decoration: none;
        font-size: 15px;
    }

    .cart-productname:hover {
        text-decoration: underline;
    }

    .cart_table .amount {
        font-weight: 600;
        color: #233d32;
    }

    .cart_table .amount span:first-child {
        font-size: 12px;
        margin-right: 1px;
    }

    .quantity {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,.06);
        overflow: hidden;
        background: #f9fbfa;
    }

    .qty-input {
        width: 60px;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 14px;
        padding: 6px 4px;
    }

    .qty-btn {
        border: none;
        background: transparent;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #555;
        font-size: 13px;
    }

    .qty-btn:hover {
        background: rgba(73,177,190,.1);
        color: #2f7f67;
    }

    .remove {
        color: #dc3545;
        font-size: 16px;
    }

    .remove:hover {
        color: #c82333;
    }

    .th-cart-coupon {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-right: auto;
    }

    .th-cart-coupon .form-control {
        max-width: 220px;
        border-radius: 999px;
        border-color: rgba(0,0,0,.08);
        font-size: 13px;
    }

    .th-cart-coupon .form-control:focus {
        box-shadow: 0 0 0 0.15rem rgba(73,177,190,.25);
        border-color: rgba(73,177,190,.65);
    }

    .summary-title {
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-size: 13px;
        color: #555;
    }

    .cart_totals {
        width: 100%;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 18px 50px rgba(0,0,0,.06);
        border-collapse: collapse;
        margin-bottom: 18px;
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

    .woocommerce-notices-wrapper {
        margin-bottom: 12px;
    }

    .woocommerce-message {
        background: rgba(73,177,190,.09);
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #2f7f67;
    }

    .woocommerce-message::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Pro";
        font-weight: 400;
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

    .empty-cart-box p {
        margin-bottom: 16px;
        color: #555;
    }

    .cart-actions-row .th-btn {
        border-radius: 999px;
        padding-inline: 22px;
        padding-block: 8px;
        font-size: 13px;
    }

    @media (max-width: 767.98px) {
        .cart_table th,
        .cart_table td {
            padding: 10px 10px;
        }

        .cart-actions-row {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: stretch;
        }

        .th-cart-coupon {
            width: 100%;
        }

        .th-cart-coupon .form-control {
            max-width: none;
            width: 100%;
        }
    }
</style>

<!--==============================
 Breadcumb
==============================-->
<div class="breadcumb-wrapper" data-bg-src="<?= base_url('assets/img/bg/breadcumb-bg.jpg') ?>">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Cart</h1>
            <ul class="breadcumb-menu">
                <li><a href="<?= site_url('/') ?>">Home</a></li>
                <li>Cart</li>
            </ul>
        </div>
    </div>
</div>

<!--==============================
 Cart Area
==============================-->
<div class="th-cart-wrapper space-top space-extra-bottom">
    <div class="container">

        <div class="woocommerce-notices-wrapper">
            <?php if ($cartNotice): ?>
                <div class="woocommerce-message"><?= esc($cartNotice) ?></div>
            <?php elseif (!empty($items)): ?>
                <div class="woocommerce-message">
                    Your cart is almost ready. Review your items below.
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($items)): ?>
            <!-- Empty cart -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="empty-cart-box">
                        <i class="far fa-shopping-cart"></i>
                        <h4 class="mb-2">Your cart is empty</h4>
                        <p>Add some fresh & healthy products to your cart to see them here.</p>
                        <a href="<?= site_url('shop') ?>" class="th-btn">
                            Browse Shop<i class="far fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>

            <!-- CART FORM -->
            <form action="<?= site_url('cart/update') ?>" method="post" class="woocommerce-cart-form">
                <?= csrf_field() ?>
                <table class="cart_table">
                    <thead>
                        <tr>
                            <th class="cart-col-image">Image</th>
                            <th class="cart-col-productname">Product</th>
                            <th class="cart-col-price">Price</th>
                            <th class="cart-col-quantity">Quantity</th>
                            <th class="cart-col-total">Total</th>
                            <th class="cart-col-remove">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $rowId     = $item['row_id'];
                            $title     = esc($item['title']);
                            $url       = $item['url'];
                            $img       = $item['image_url'];
                            $qty       = (int)$item['qty'];
                            $final     = (float)$item['final_price'];
                            $rowTotal  = (float)$item['row_total'];
                            ?>
                            <tr class="cart_item">
                                <td data-title="Product">
                                    <a class="cart-productimage" href="<?= $url ?>">
                                        <img src="<?= $img ?>" alt="<?= $title ?>">
                                    </a>
                                </td>
                                <td data-title="Name">
                                    <a class="cart-productname" href="<?= $url ?>">
                                        <?= $title ?>
                                    </a>
                                </td>
                                <td data-title="Price">
                                    <span class="amount">
                                        <bdi><span><?= $currencySymbol ?></span><?= number_format($final, 2) ?></bdi>
                                    </span>
                                </td>
                                <td data-title="Quantity">
                                    <div class="quantity">
                                        <button type="button" class="quantity-minus qty-btn" data-target="qty-<?= $rowId ?>">
                                            <i class="far fa-minus"></i>
                                        </button>
                                        <input
                                            type="number"
                                            class="qty-input"
                                            id="qty-<?= $rowId ?>"
                                            name="quantities[<?= esc($rowId) ?>]"
                                            value="<?= $qty ?>"
                                            min="1" max="99">
                                        <button type="button" class="quantity-plus qty-btn" data-target="qty-<?= $rowId ?>">
                                            <i class="far fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td data-title="Total">
                                    <span class="amount">
                                        <bdi><span><?= $currencySymbol ?></span><?= number_format($rowTotal, 2) ?></bdi>
                                    </span>
                                </td>
                                <td data-title="Remove">
                                    <a href="<?= site_url('cart/remove/' . $rowId) ?>" class="remove">
                                        <i class="fal fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td colspan="6" class="actions cart-actions-row">
                                <div class="th-cart-coupon">
                                    <input type="text" name="coupon_code" class="form-control" placeholder="Coupon Code">
                                    <button type="submit" name="apply_coupon" value="1" class="th-btn">
                                        Apply Coupon
                                    </button>
                                </div>
                                <button type="submit" name="update_cart" value="1" class="th-btn">
                                    Update Cart
                                </button>
                                <a href="<?= site_url('shop') ?>" class="th-btn">
                                    Continue Shopping
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <!-- CART TOTALS -->
            <div class="row justify-content-end">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <h2 class="h4 summary-title">Cart Totals</h2>
                    <table class="cart_totals">
                        <tbody>
                            <tr>
                                <th>Cart Subtotal</th>
                                <td data-title="Cart Subtotal">
                                    <span class="amount">
                                        <bdi><span><?= $currencySymbol ?></span><?= $fmtSubTotal ?></bdi>
                                    </span>
                                </td>
                            </tr>
                            <tr class="shipping">
                                <th>Shipping</th>
                                <td data-title="Shipping">
                                    <?php if ($shippingAmount <= 0): ?>
                                        <p class="mb-0">Calculated at checkout or free for eligible orders.</p>
                                    <?php else: ?>
                                        <span class="amount">
                                            <bdi><span><?= $currencySymbol ?></span><?= $fmtShipping ?></bdi>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="order-total">
                                <th>Order Total</th>
                                <td data-title="Total">
                                    <strong>
                                        <span class="amount">
                                            <bdi><span><?= $currencySymbol ?></span><?= $fmtOrderTotal ?></bdi>
                                        </span>
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <?php
                    // Login-aware proceed URL
                    $checkoutUrl = $isCustomerLoggedIn
                        ? site_url('checkout')
                        : site_url('customer/login?redirect=' . urlencode(site_url('checkout')));
                    ?>
                    <div class="wc-proceed-to-checkout mb-30">
                        <a href="<?= $checkoutUrl ?>" class="th-btn">
                            Proceed to Checkout<i class="far fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<script>
    // +/- quantity buttons
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.qty-btn')) return;
        const btn = e.target.closest('.qty-btn');
        const targetId = btn.getAttribute('data-target');
        if (!targetId) return;
        const input = document.getElementById(targetId);
        if (!input) return;

        let val = parseInt(input.value || '1', 10);
        if (btn.classList.contains('quantity-plus')) {
            val++;
        } else if (btn.classList.contains('quantity-minus')) {
            val--;
        }
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;
    });
</script>

<?= $this->endSection() ?>
