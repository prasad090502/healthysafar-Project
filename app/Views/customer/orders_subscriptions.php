<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/** @var array $orders */
/** @var array $subscriptions */
/** @var int   $upcoming_count */
/** @var int   $postponements_used */

$orders             = $orders ?? [];
$subscriptions      = $subscriptions ?? [];
$upcoming_count     = $upcoming_count ?? 0;
$postponements_used = $postponements_used ?? 0;

// Small helper for dates
if (!function_exists('hs_format_date')) {
    function hs_format_date(?string $dt): string
    {
        if (empty($dt)) {
            return '-';
        }
        $ts = strtotime($dt);
        if ($ts === false) {
            return $dt;
        }
        return date('d M Y, h:i A', $ts);
    }
}
?>

<style>
    :root {
        --hs-ink: #0f172a;
        --hs-muted: #64748b;
        --hs-line: #e2e8f0;
        --hs-bg: #f8fafc;
        --hs-card: #ffffff;
        --hs-green: #16a34a;
        --hs-green-soft: #dcfce7;
        --hs-amber: #f59e0b;
        --hs-red: #ef4444;
        --hs-blue: #2563eb;
        --hs-radius-lg: 1rem;
        --hs-radius-xl: 1.5rem;
    }

    body {
        background-color: var(--hs-bg);
    }

    .hs-account-wrapper {
        padding-block: 32px 56px;
    }

    .hs-page-title {
        font-weight: 800;
        letter-spacing: -.03em;
        color: var(--hs-ink);
    }

    .hs-page-sub {
        color: var(--hs-muted);
        font-size: .92rem;
    }

    /* Top stats cards */
    .hs-stat-card {
        border-radius: 18px;
        border: 1px solid var(--hs-line);
        background: var(--hs-card);
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        box-shadow: 0 18px 40px rgba(15,23,42,.04);
        height: 100%;
    }
    .hs-stat-label {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--hs-muted);
        font-weight: 600;
    }
    .hs-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--hs-ink);
    }

    /* Tabs */
    .hs-tab-nav {
        border-bottom: 1px solid var(--hs-line);
        margin-top: 24px;
    }
    .hs-tab-nav .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        padding: 10px 0;
        margin-right: 24px;
        color: var(--hs-muted);
        font-weight: 500;
        font-size: .95rem;
    }
    .hs-tab-nav .nav-link.active {
        color: var(--hs-ink);
        border-bottom-color: var(--hs-green);
    }

    /* Orders list */
    .hs-orders-card {
        border-radius: var(--hs-radius-xl);
        background: var(--hs-card);
        border: 1px solid var(--hs-line);
        box-shadow: 0 22px 45px rgba(15,23,42,.06);
        margin-top: 18px;
    }
    .hs-orders-card .card-body {
        padding: 18px 22px;
    }
    .hs-order-row {
        border-bottom: 1px dashed var(--hs-line);
    }
    .hs-order-row:last-child {
        border-bottom: none;
    }
    .hs-order-id {
        font-weight: 700;
        color: var(--hs-ink);
    }
    .hs-order-meta {
        color: var(--hs-muted);
        font-size: .8rem;
    }

    .hs-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 3px 10px;
        font-size: .75rem;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .hs-badge.status-pending {
        background: #fef9c3;
        color: #854d0e;
        border-color: #facc15;
    }
    .hs-badge.status-paid,
    .hs-badge.status-confirmed {
        background: var(--hs-green-soft);
        color: #166534;
        border-color: #4ade80;
    }
    .hs-badge.status-delivered {
        background: #dcfce7;
        color: #166534;
        border-color: #22c55e;
    }
    .hs-badge.status-cancelled {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }
    .hs-badge.status-processing {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .hs-order-total {
        font-weight: 700;
        color: var(--hs-ink);
    }
    .hs-order-total span {
        font-size: .8rem;
        color: var(--hs-muted);
        font-weight: 500;
    }

    .btn-outline-soft {
        border-radius: 999px;
        font-size: .8rem;
        padding: 6px 14px;
    }

    /* Subscriptions */
    .hs-sub-card {
        border-radius: var(--hs-radius-xl);
        border: 1px solid var(--hs-line);
        background: var(--hs-card);
        padding: 18px 20px 10px;
        margin-top: 18px;
        box-shadow: 0 18px 40px rgba(15,23,42,.06);
    }
    .hs-sub-title {
        font-weight: 700;
        margin-bottom: 2px;
        color: var(--hs-ink);
    }
    .hs-sub-plan {
        font-size: .85rem;
        color: var(--hs-muted);
    }
    .hs-sub-badges {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        margin-top: 6px;
    }
    .hs-badge-substatus {
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 999px;
        padding: 2px 8px;
        font-size: .72rem;
        font-weight: 600;
    }
    .hs-badge-substatus.active {
        background: #dcfce7;
        color: #166534;
    }
    .hs-badge-substatus.completed {
        background: #e5e7eb;
        color: #374151;
    }

    .hs-progress-wrap {
        margin-top: 12px;
    }
    .hs-progress-meta {
        display: flex;
        justify-content: space-between;
        font-size: .78rem;
        color: var(--hs-muted);
        margin-bottom: 4px;
    }
    .hs-progress-bar {
        height: 7px;
        border-radius: 999px;
        background: #e5e7eb;
        overflow: hidden;
    }
    .hs-progress-bar span {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, #22c55e, #16a34a);
    }

    .hs-sub-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        font-size: .8rem;
        color: var(--hs-muted);
        margin-top: 10px;
    }
    .hs-sub-meta-row strong {
        color: var(--hs-ink);
    }

    .hs-sub-footer {
        border-top: 1px dashed var(--hs-line);
        margin-top: 12px;
        padding-top: 10px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
    }

    .hs-sub-next {
        font-size: .8rem;
        color: var(--hs-muted);
    }

    /* Deliveries table */
    .hs-deliveries-wrap {
        margin-top: 10px;
        border-radius: 14px;
        border: 1px dashed #cbd5f5;
        background: #f9fafb;
        padding: 10px 10px 6px;
    }
    .hs-deliveries-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .78rem;
        color: var(--hs-muted);
        margin-bottom: 6px;
    }
    .hs-deliveries-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .8rem;
    }
    .hs-deliveries-table thead tr {
        border-bottom: 1px solid #e5e7eb;
    }
    .hs-deliveries-table th,
    .hs-deliveries-table td {
        padding: 6px 4px;
        white-space: nowrap;
    }
    .hs-deliveries-table tbody tr + tr {
        border-top: 1px dotted #e5e7eb;
    }

    .hs-delivery-status {
        font-size: .75rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 999px;
    }
    .hs-delivery-status.pending {
        background: #fef9c3;
        color: #854d0e;
    }
    .hs-delivery-status.out_for_delivery {
        background: #eff6ff;
        color: #1d4ed8;
    }
    .hs-delivery-status.delivered {
        background: #dcfce7;
        color: #166534;
    }
    .hs-delivery-status.skipped {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-xs {
        padding: 3px 8px;
        font-size: .72rem;
        border-radius: 999px;
    }

    @media (max-width: 767.98px) {
        .hs-orders-card .card-body,
        .hs-sub-card {
            padding-inline: 14px;
        }
        .hs-sub-meta-row {
            flex-direction: column;
        }
    }
</style>

<div class="hs-account-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">

                <!-- Header -->
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <h2 class="hs-page-title mb-1">My Orders &amp; Subscriptions</h2>
                        <p class="hs-page-sub mb-0">
                            Track your purchases, manage active meal plans and see what’s coming next.
                        </p>
                    </div>
                </div>

                <!-- Top stats -->
                <div class="row g-3 mb-1">
                    <div class="col-6 col-md-3">
                        <div class="hs-stat-card">
                            <span class="hs-stat-label">Total orders</span>
                            <div class="hs-stat-value"><?= count($orders) ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="hs-stat-card">
                            <span class="hs-stat-label">Active subscriptions</span>
                            <div class="hs-stat-value">
                                <?= count(array_filter($subscriptions, fn($s) => strtolower($s['status'] ?? '') === 'active')) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="hs-stat-card">
                            <span class="hs-stat-label">Upcoming deliveries</span>
                            <div class="hs-stat-value">
                                <?= (int) $upcoming_count ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="hs-stat-card">
                            <span class="hs-stat-label">Postpone credits used</span>
                            <div class="hs-stat-value">
                                <?= (int) $postponements_used ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav hs-tab-nav" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="orders-tab" data-bs-toggle="tab"
                                data-bs-target="#orders-pane" type="button" role="tab">
                            Orders
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="subs-tab" data-bs-toggle="tab"
                                data-bs-target="#subs-pane" type="button" role="tab">
                            Subscriptions
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="payments-tab" data-bs-toggle="tab"
                                data-bs-target="#payments-pane" type="button" role="tab">
                            Payments
                        </button>
                    </li>
                </ul>

                <div class="tab-content pt-3">

                    <!-- ORDERS TAB -->
                    <div class="tab-pane fade show active" id="orders-pane" role="tabpanel">

                        <?php if (empty($orders)): ?>
                            <div class="alert alert-info rounded-4 border-0 shadow-sm mt-2">
                                You don’t have any orders yet. Start shopping from the
                                <a href="<?= site_url('shop') ?>">Shop</a>.
                            </div>
                        <?php else: ?>

                            <div class="card hs-orders-card">
                                <div class="card-body">

                                    <?php foreach ($orders as $order): ?>
                                        <?php
                                            $orderId    = $order['id'] ?? null;
                                            $orderNo    = $order['order_number'] ?? $orderId;
                                            $statusRaw  = strtolower((string)($order['status'] ?? ''));
                                            $statusText = ucfirst($statusRaw ?: 'Pending');
                                            $total      = (float)($order['grand_total'] ?? 0);
                                            $date       = hs_format_date($order['created_at'] ?? null);

                                            $badgeClass = 'status-processing';
                                            if (in_array($statusRaw, ['pending', 'awaiting_payment'], true)) {
                                                $badgeClass = 'status-pending';
                                            } elseif (in_array($statusRaw, ['paid', 'confirmed'], true)) {
                                                $badgeClass = 'status-paid';
                                            } elseif ($statusRaw === 'delivered' || $statusRaw === 'completed') {
                                                $badgeClass = 'status-delivered';
                                            } elseif (in_array($statusRaw, ['cancelled', 'refunded'], true)) {
                                                $badgeClass = 'status-cancelled';
                                            }
                                        ?>
                                        <div class="hs-order-row py-3">
                                            <div class="row align-items-center g-2">
                                                <div class="col-md-4">
                                                    <div class="hs-order-id">#<?= esc($orderNo) ?></div>
                                                    <div class="hs-order-meta">
                                                        Placed on <?= esc($date) ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <span class="hs-badge <?= $badgeClass ?>">
                                                        <?= esc($statusText) ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-3 col-6 text-md-end">
                                                    <div class="hs-order-total">
                                                        ₹<?= number_format($total, 2) ?>
                                                        <span>total</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 text-md-end">
                                                    <a href="<?= site_url('customer/orders/view/' . $orderId) ?>"
                                                       class="btn btn-outline-secondary btn-outline-soft">
                                                        View details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>

                        <?php endif; ?>
                    </div>

                    <!-- SUBSCRIPTIONS TAB -->
                    <div class="tab-pane fade" id="subs-pane" role="tabpanel">
                        <?php if (empty($subscriptions)): ?>
                            <div class="alert alert-info rounded-4 border-0 shadow-sm mt-2">
                                You don’t have any active subscriptions yet. Explore
                                <a href="<?= site_url('subscriptions') ?>">meal plans</a>.
                            </div>
                        <?php else: ?>

                            <?php foreach ($subscriptions as $sub): ?>
                                <?php
                                    $subId        = (int)($sub['id'] ?? 0);
                                    $planTitle    = $sub['plan_title'] ?? 'Subscription Plan';
                                    $status       = strtolower((string)($sub['status'] ?? 'active'));
                                    $start        = $sub['start_date'] ?? null;
                                    $end          = $sub['end_date'] ?? null;
                                    $totalDays    = (int)($sub['total_days'] ?? $sub['duration_days'] ?? 0);
                                    $delivered    = (int)($sub['delivered_days'] ?? 0);
                                    $remaining    = (int)($sub['remaining_days'] ?? max(0, $totalDays - $delivered));
                                    $cutOffHour   = (int)($sub['cut_off_hour'] ?? 22);
                                    $ppUsed       = (int)($sub['postponement_used'] ?? 0);
                                    $ppLimit      = (int)($sub['postponement_limit'] ?? 0);
                                    $ppLeft       = max(0, $ppLimit - $ppUsed);

                                    $progressPct = $totalDays > 0 ? round(($delivered / $totalDays) * 100) : 0;

                                    /** @var array $upcoming */
                                    $upcoming = $sub['upcoming_deliveries'] ?? [];

                                    $nextDelivery = $upcoming[0]['delivery_date'] ?? null;
                                    $nextSlot     = $upcoming[0]['slot_label'] ?? ($upcoming[0]['base_slot_key'] ?? null);
                                ?>
                                <div class="hs-sub-card">
                                    <!-- Header row -->
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="hs-sub-title">
                                                <?= esc($planTitle) ?>
                                            </div>
                                            <div class="hs-sub-plan">
                                                Subscription #<?= esc($subId) ?> ·
                                                <?= esc(hs_format_date($start)) ?> –
                                                <?= esc(hs_format_date($end)) ?>
                                            </div>
                                            <div class="hs-sub-badges">
                                                <span class="hs-badge-substatus <?= $status === 'active' ? 'active' : ($status === 'completed' ? 'completed' : '') ?>">
                                                    <?= ucfirst($status) ?>
                                                </span>
                                                <?php if ($ppLimit > 0): ?>
                                                    <span class="hs-badge-substatus">
                                                        Postpone credits: <?= $ppUsed ?>/<?= $ppLimit ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="text-sm-end text-muted small">
                                            Changes allowed until <strong><?= sprintf('%02d:00', $cutOffHour) ?></strong><br>
                                            on the previous day.
                                        </div>
                                    </div>

                                    <!-- Progress -->
                                    <div class="hs-progress-wrap">
                                        <div class="hs-progress-meta">
                                            <span><?= $delivered ?> delivered · <?= $remaining ?> remaining</span>
                                            <span><?= $progressPct ?>%</span>
                                        </div>
                                        <div class="hs-progress-bar">
                                            <span style="width: <?= $progressPct ?>%;"></span>
                                        </div>
                                    </div>

                                    <!-- Meta -->
                                    <div class="hs-sub-meta-row">
                                        <div>
                                            <strong>Start</strong><br>
                                            <?= esc(hs_format_date($start)) ?>
                                        </div>
                                        <div>
                                            <strong>End (current)</strong><br>
                                            <?= esc(hs_format_date($end)) ?>
                                        </div>
                                        <?php if (!empty($nextDelivery)): ?>
                                            <div>
                                                <strong>Next delivery</strong><br>
                                                <?= esc(date('d M Y', strtotime($nextDelivery))) ?>
                                                <?php if ($nextSlot): ?>
                                                    · <?= esc($nextSlot) ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Deliveries list -->
                                    <?php if (!empty($upcoming)): ?>
                                        <div class="hs-deliveries-wrap">
                                            <div class="hs-deliveries-header">
                                                <div>
                                                    Upcoming deliveries
                                                </div>
                                                <div>
                                                    <span class="text-muted">
                                                        Manage each day until <?= sprintf('%02d:00', $cutOffHour) ?> previous night
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="hs-deliveries-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Menu</th>
                                                        <th>Slot</th>
                                                        <th>Address</th>
                                                        <th>Status</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($upcoming as $del): ?>
                                                        <?php
                                                            $delId   = (int)($del['id'] ?? 0);
                                                            $dDate   = $del['delivery_date'] ?? null;
                                                            $slotLbl = $del['slot_label'] ?? ($del['override_slot_key'] ?? $del['base_slot_key'] ?? '');
                                                            $addrLbl = $del['address_label'] ?? 'Default address';
                                                            $dStatus = strtolower($del['status'] ?? 'pending');
                                                            $statusClass = 'pending';
                                                            if ($dStatus === 'out_for_delivery') {
                                                                $statusClass = 'out_for_delivery';
                                                            } elseif ($dStatus === 'delivered') {
                                                                $statusClass = 'delivered';
                                                            } elseif (in_array($dStatus, ['skipped', 'cancelled'], true)) {
                                                                $statusClass = 'skipped';
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td><?= esc(date('d M, D', strtotime($dDate))) ?></td>
                                                            <td>
                                                                <?= esc($del['menu_name'] ?? 'No menu') ?>
                                                            </td>
                                                            <td><?= esc($slotLbl) ?></td>
                                                            <td><?= esc($addrLbl) ?></td>
                                                            <td>
                                                                <span class="hs-delivery-status <?= $statusClass ?>">
                                                                    <?= ucfirst($dStatus) ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-end">
                                                                <?php if (in_array($dStatus, ['pending', 'confirmed', 'processing', 'out_for_delivery'], true)): ?>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <a href="<?= site_url('customer/subscriptions/change-address/' . $delId) ?>"
                                                                           class="btn btn-light btn-xs">
                                                                            Address
                                                                        </a>
                                                                        <a href="<?= site_url('customer/subscriptions/change-slot/' . $delId) ?>"
                                                                           class="btn btn-light btn-xs">
                                                                            Slot
                                                                        </a>

                                                                        <a href="<?= site_url('customer/subscriptions/add-note/' . $delId) ?>"
                                                                           class="btn btn-light btn-xs">
                                                                            Note
                                                                        </a>
                                                                        <?php if ($ppLeft > 0): ?>
                                                                            <a href="<?= site_url('customer/subscriptions/skip/' . $delId) ?>"
                                                                               class="btn btn-outline-danger btn-xs">
                                                                                Skip
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="text-muted small">Locked</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Footer actions -->
                                    <div class="hs-sub-footer">
                                        <div class="hs-sub-next">
                                            <?php if (!empty($nextDelivery)): ?>
                                                Next delivery on
                                                <strong><?= esc(date('d M Y', strtotime($nextDelivery))) ?></strong>
                                                <?php if ($nextSlot): ?>
                                                    at <strong><?= esc($nextSlot) ?></strong>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                No upcoming deliveries scheduled.
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <!-- Placeholder: implement when you have a subscription details page -->
                                            <a href="javascript:void(0)"
                                               class="btn btn-outline-secondary btn-outline-soft disabled">
                                                View subscription details
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </div>

                    <!-- PAYMENTS TAB -->
                    <div class="tab-pane fade" id="payments-pane" role="tabpanel">
                        <?php
                        // For now, treat EVERY order as a payment record (including COD / pending)
                        $paymentOrders = $orders;
                        ?>
                        <?php if (empty($paymentOrders)): ?>
                            <div class="alert alert-info rounded-4 border-0 shadow-sm mt-2">
                                No payment history found yet.
                            </div>
                        <?php else: ?>
                            <div class="card hs-orders-card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Method</th>
                                                <th>Payment Status</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($paymentOrders as $order): ?>
                                                <?php
                                                $orderNo     = $order['order_number'] ?? $order['id'];
                                                $date        = hs_format_date($order['created_at'] ?? null);
                                                $methodRaw   = strtolower($order['payment_method'] ?? '');
                                                if ($methodRaw === 'cod') {
                                                    $method = 'Cash on Delivery';
                                                } elseif ($methodRaw === 'online') {
                                                    $method = 'Online';
                                                } else {
                                                    $method = ucfirst($methodRaw ?: 'Online');
                                                }

                                                $psRaw  = strtolower($order['payment_status'] ?? 'pending');
                                                $psText = ucfirst($psRaw);
                                                $amount = (float)($order['grand_total'] ?? 0);

                                                $payBadge = 'status-processing';
                                                if (in_array($psRaw, ['paid', 'captured', 'success'], true)) {
                                                    $payBadge = 'status-paid';
                                                } elseif (in_array($psRaw, ['failed', 'refunded'], true)) {
                                                    $payBadge = 'status-cancelled';
                                                } elseif (in_array($psRaw, ['pending', 'cod_pending'], true)) {
                                                    $payBadge = 'status-pending';
                                                }
                                                ?>
                                                <tr>
                                                    <td>#<?= esc($orderNo) ?></td>
                                                    <td><?= esc($date) ?></td>
                                                    <td><?= esc($method) ?></td>
                                                    <td>
                                                        <span class="hs-badge <?= $payBadge ?>">
                                                            <?= esc($psText) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        ₹<?= number_format($amount, 2) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>