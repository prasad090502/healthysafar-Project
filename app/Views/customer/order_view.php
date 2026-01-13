<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$order = $order ?? [];
$items = $items ?? [];

if (!function_exists('hs_format_date')) {
    function hs_format_date(?string $dt): string
    {
        if (empty($dt)) return '-';
        $ts = strtotime($dt);
        if ($ts === false) return $dt;
        return date('d M Y, h:i A', $ts);
    }
}

$orderNo   = $order['order_number'] ?? $order['id'] ?? '';
$statusRaw = strtolower($order['status'] ?? '');
$statusLbl = ucfirst($statusRaw ?: 'Pending');
$date      = hs_format_date($order['created_at'] ?? null);
$methodRaw = strtolower($order['payment_method'] ?? '');
$payment   = $order['payment_status'] ?? 'pending';

if ($methodRaw === 'cod') {
    $method = 'Cash on Delivery';
} elseif ($methodRaw === 'online') {
    $method = 'Online';
} else {
    $method = ucfirst($methodRaw ?: 'Online');
}

$total = (float)($order['grand_total'] ?? 0);
?>

<style>
    .hs-order-shell {
        padding-block: 30px 50px;
    }
    .hs-order-card {
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 18px 40px rgba(15,23,42,.06);
    }
    .hs-order-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 18px 20px;
    }
    .hs-order-title {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 4px;
    }
    .hs-order-sub {
        color: #64748b;
        font-size: .88rem;
    }
    .hs-badge-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: .75rem;
        font-weight: 600;
    }
    .hs-badge-order {
        background: #eff6ff;
        color: #1d4ed8;
    }
    .hs-badge-pay {
        background: #dcfce7;
        color: #166534;
    }
    .hs-badge-pay.pending {
        background: #fef9c3;
        color: #854d0e;
    }
    .hs-badge-pay.failed {
        background: #fee2e2;
        color: #991b1b;
    }
    .hs-order-body {
        padding: 18px 20px;
    }
    .hs-summary-row {
        font-size: .9rem;
        color: #64748b;
    }
    .hs-summary-row strong {
        color: #0f172a;
    }
    .hs-total {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
    }
</style>

<div class="hs-order-shell">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">

                <div class="mb-3">
                    <a href="<?= site_url('customer/orders') ?>" class="btn btn-sm btn-outline-secondary">
                        ← Back to Orders
                    </a>
                </div>

                <div class="hs-order-card">
                    <div class="hs-order-header d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="hs-order-title">
                                Order #<?= esc($orderNo) ?>
                            </div>
                            <div class="hs-order-sub">
                                Placed on <?= esc($date) ?> · Method: <?= esc($method) ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="mb-1">
                                <span class="hs-badge-chip hs-badge-order">
                                    <?= esc(ucfirst($statusRaw ?: 'Pending')) ?>
                                </span>
                            </div>
                            <div>
                                <?php
                                $psRaw = strtolower($payment);
                                $payClass = 'hs-badge-pay';
                                if (in_array($psRaw, ['pending', 'cod_pending'], true)) {
                                    $payClass .= ' pending';
                                } elseif (in_array($psRaw, ['failed', 'refunded'], true)) {
                                    $payClass .= ' failed';
                                }
                                ?>
                                <span class="hs-badge-chip <?= $payClass ?>">
                                    Payment: <?= esc(ucfirst($psRaw)) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="hs-order-body">
                        <?php if (empty($items)): ?>
                            <p class="text-muted mb-0">
                                No standalone products found in this order (this might be a pure subscription order).
                            </p>
                        <?php else: ?>
                            <div class="table-responsive mb-3">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center" style="width: 80px;">Qty</th>
                                        <th class="text-end" style="width: 120px;">Price</th>
                                        <th class="text-end" style="width: 130px;">Line Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($items as $it): ?>
                                        <?php
                                        $name  = $it['product_name'] ?? $it['name'] ?? 'Item';
                                        $qty   = (int)($it['quantity'] ?? $it['qty'] ?? 1);
                                        $price = (float)($it['unit_price'] ?? $it['price'] ?? 0);
                                        $line  = (float)($it['line_total'] ?? $it['row_total'] ?? ($price * $qty));
                                        ?>
                                        <tr>
                                            <td><?= esc($name) ?></td>
                                            <td class="text-center"><?= $qty ?></td>
                                            <td class="text-end">₹<?= number_format($price, 2) ?></td>
                                            <td class="text-end">₹<?= number_format($line, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center hs-summary-row">
                            <div>
                                <strong>Order Total</strong><br>
                                <small class="text-muted">Including all items & charges</small>
                            </div>
                            <div class="hs-total">
                                ₹<?= number_format($total, 2) ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>