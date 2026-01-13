<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Customer <?= esc($customer['name']) ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .stat-card {
        border-radius: 18px;
        border: 1px solid #eef1f6;
        padding: 16px 18px;
        background: #ffffff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
    }
    .chip-filter {
        border-radius: 999px;
        padding: 6px 14px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 0.775rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .15s ease-in-out;
    }
    .chip-filter.active {
        background: #dcfce7;
        border-color: #22c55e;
        color: #166534;
        font-weight: 600;
    }
    .chip-filter .dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: currentColor;
    }
    .chip-filter span.count {
        background:#00000009;
        border-radius:999px;
        padding:2px 6px;
        font-size:0.7rem;
    }
    .table-modern tbody tr:hover {
        background: #f9fafb;
    }
</style>

<?php
    $filter_status        = $filter_status        ?? '';
    $filter_paymentStatus = $filter_paymentStatus ?? '';
    $filter_dateFrom      = $filter_dateFrom      ?? '';
    $filter_dateTo        = $filter_dateTo        ?? '';
    $filter_q             = $filter_q             ?? '';

    $statusCounts   = $statusCounts   ?? [];
    $paymentCounts  = $paymentCounts  ?? [];
    $totalOrdersAll = (int)($summary['total_orders'] ?? 0);

    // Helper for building query strings while staying on same page
    $baseUrl = current_url();
    $currentQuery = $_GET ?? [];
    $buildUrl = function(array $overrides = []) use ($baseUrl, $currentQuery) {
        $params = array_merge($currentQuery, $overrides);
        // remove empty
        foreach ($params as $k => $v) {
            if ($v === '' || $v === null) {
                unset($params[$k]);
            }
        }
        $qs = http_build_query($params);
        return $baseUrl . ($qs ? ('?' . $qs) : '');
    };

    $statusLabel = [
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'shipped'    => 'Shipped',
        'delivered'  => 'Delivered',
        'cancelled'  => 'Cancelled',
        'refunded'   => 'Refunded',
    ];

    $payLabel = [
        'pending' => 'Pending',
        'paid'    => 'Paid',
        'failed'  => 'Failed',
        'refunded'=> 'Refunded',
    ];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-success fw-bold me-3"
             style="width:56px;height:56px;font-size:1.35rem">
            <?= strtoupper(substr($customer['name'], 0, 1)) ?>
        </div>
        <div>
            <h3 class="fw-bold mb-1"><?= esc($customer['name']) ?></h3>
            <p class="text-muted mb-0">
                Joined on <?= date('d M Y', strtotime($customer['created_at'])) ?> ·
                Username: <?= esc($customer['username']) ?>
            </p>
        </div>
    </div>
    <a href="<?= base_url('admin/customers') ?>" class="btn btn-light">
        <i class="bi bi-arrow-left"></i> Back to Customers
    </a>
</div>

<!-- Top Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="text-muted text-uppercase small fw-bold mb-2">Contact</div>
            <div class="mb-1">
                <i class="bi bi-envelope me-2"></i><?= esc($customer['email']) ?>
            </div>
            <?php if (!empty($customer['contact'])): ?>
                <div class="mb-1">
                    <i class="bi bi-telephone me-2"></i><?= esc($customer['contact']) ?>
                </div>
            <?php endif; ?>
            <div class="small text-muted mt-2">
                Role: <?= esc(ucfirst($customer['role'])) ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="text-muted text-uppercase small fw-bold mb-2">Total Orders</div>
            <div class="fs-3 fw-bold mb-1">
                <?= (int) ($summary['total_orders'] ?? 0) ?>
            </div>
            <div class="small text-muted">
                All orders ever placed by this customer.
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="text-muted text-uppercase small fw-bold mb-2">Total Spent</div>
            <div class="fs-3 fw-bold mb-1">
                ₹<?= number_format($summary['total_spent'] ?? 0, 2) ?>
            </div>
            <div class="small text-muted">
                Lifetime revenue from this customer.
            </div>
        </div>
    </div>
</div>

<!-- Addresses + Orders -->
<div class="row g-3">
    <!-- Addresses -->
    <div class="col-lg-4">
        <div class="card border-0 mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="fw-bold mb-0">Saved Addresses</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($addresses)): ?>
                    <?php foreach ($addresses as $addr): ?>
                        <div class="border rounded-3 p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="fw-semibold">
                                    <?= esc($addr['name']) ?>
                                    <span class="badge bg-light text-dark ms-1">
                                        <?= ucfirst($addr['address_type']) ?>
                                    </span>
                                </div>
                                <?php if ($addr['is_default']): ?>
                                    <span class="badge bg-success-subtle text-success">Default</span>
                                <?php endif; ?>
                            </div>
                            <div class="small text-muted mb-1">
                                <?= esc($addr['address_line1']) ?><br>
                                <?php if (!empty($addr['address_line2'])): ?>
                                    <?= esc($addr['address_line2']) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($addr['landmark'])): ?>
                                    Landmark: <?= esc($addr['landmark']) ?><br>
                                <?php endif; ?>
                                <?= esc($addr['city']) ?>, <?= esc($addr['state']) ?> - <?= esc($addr['pincode']) ?><br>
                                <?= esc($addr['country']) ?>
                            </div>
                            <div class="small text-muted">
                                📞 <?= esc($addr['phone']) ?>
                                <?php if (!empty($addr['alternate_phone'])): ?>
                                    <br>Alt: <?= esc($addr['alternate_phone']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted small mb-0">
                        No addresses saved for this customer.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-lg-8">
        <div class="card border-0">
            <div class="card-header bg-white border-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h6 class="fw-bold mb-0">Orders & Status</h6>
                        <small class="text-muted">
                            Track all orders, payments and delivery status for this customer.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick chips for Status -->
            <div class="px-3 pt-2 pb-1 border-bottom bg-white">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="text-muted small me-2">Order Status:</span>
                    <a href="<?= esc($buildUrl(['status' => ''])) ?>" class="text-decoration-none">
                        <div class="chip-filter <?= $filter_status === '' ? 'active' : '' ?>">
                            <span class="dot"></span>
                            <span>All</span>
                            <span class="count"><?= $totalOrdersAll ?></span>
                        </div>
                    </a>
                    <?php foreach ($statusLabel as $key => $label): ?>
                        <?php $cnt = $statusCounts[$key] ?? 0; ?>
                        <?php if ($cnt === 0 && empty($filter_status)) continue; // hide empty chips when no filter ?>
                        <a href="<?= esc($buildUrl(['status' => $key])) ?>" class="text-decoration-none">
                            <div class="chip-filter <?= $filter_status === $key ? 'active' : '' ?>">
                                <span class="dot"></span>
                                <span><?= esc($label) ?></span>
                                <span class="count"><?= $cnt ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Quick chips for Payment Status -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="text-muted small me-2">Payment:</span>
                    <a href="<?= esc($buildUrl(['payment_status' => ''])) ?>" class="text-decoration-none">
                        <div class="chip-filter <?= $filter_paymentStatus === '' ? 'active' : '' ?>">
                            <span class="dot"></span>
                            <span>All</span>
                            <span class="count"><?= $totalOrdersAll ?></span>
                        </div>
                    </a>
                    <?php foreach ($payLabel as $key => $label): ?>
                        <?php $cnt = $paymentCounts[$key] ?? 0; ?>
                        <?php if ($cnt === 0 && empty($filter_paymentStatus)) continue; ?>
                        <a href="<?= esc($buildUrl(['payment_status' => $key])) ?>" class="text-decoration-none">
                            <div class="chip-filter <?= $filter_paymentStatus === $key ? 'active' : '' ?>">
                                <span class="dot"></span>
                                <span><?= esc($label) ?></span>
                                <span class="count"><?= $cnt ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Advanced filters -->
            <div class="card-body border-bottom bg-light-subtle">
                <form class="row g-2 align-items-end" method="get" action="<?= current_url() ?>">
                    <!-- Keep existing query in URL -->
                    <?php
                        // When using GET, keep existing other params (like page for pagination will reset anyway)
                        foreach ($_GET as $k => $v):
                            if (in_array($k, ['status', 'payment_status', 'date_from', 'date_to', 'q', 'page_cust_orders'])) continue;
                            if ($v === '' || $v === null) continue;
                    ?>
                        <input type="hidden" name="<?= esc($k) ?>" value="<?= esc($v) ?>">
                    <?php endforeach; ?>

                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">Order #</label>
                        <input type="text"
                               name="q"
                               class="form-control form-control-sm"
                               placeholder="Search order number"
                               value="<?= esc($filter_q) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">Order Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <?php foreach ($statusLabel as $key => $label): ?>
                                <option value="<?= esc($key) ?>" <?= $filter_status === $key ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-0">Payment Status</label>
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <?php foreach ($payLabel as $key => $label): ?>
                                <option value="<?= esc($key) ?>" <?= $filter_paymentStatus === $key ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <div class="flex-fill">
                            <label class="form-label small text-muted mb-0">From</label>
                            <input type="date"
                                   name="date_from"
                                   class="form-control form-control-sm"
                                   value="<?= esc($filter_dateFrom) ?>">
                        </div>
                        <div class="flex-fill">
                            <label class="form-label small text-muted mb-0">To</label>
                            <input type="date"
                                   name="date_to"
                                   class="form-control form-control-sm"
                                   value="<?= esc($filter_dateTo) ?>">
                        </div>
                    </div>

                    <div class="col-12 col-md-auto">
                        <button class="btn btn-success btn-sm w-100 mt-2 mt-md-0">
                            <i class="bi bi-funnel me-1"></i> Apply
                        </button>
                    </div>
                    <div class="col-12 col-md-auto">
                        <a href="<?= esc(current_url()) ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2 mt-md-0">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Orders table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-modern">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <?php
                                        $st  = $order['status'];
                                        $pst = $order['payment_status'];
                                        $statusClassMap = [
                                            'pending'    => 'badge bg-warning-subtle text-warning',
                                            'processing' => 'badge bg-info-subtle text-info',
                                            'shipped'    => 'badge bg-primary-subtle text-primary',
                                            'delivered'  => 'badge bg-success-subtle text-success',
                                            'cancelled'  => 'badge bg-secondary-subtle text-secondary',
                                            'refunded'   => 'badge bg-dark-subtle text-dark',
                                        ];
                                        $payClassMap = [
                                            'pending' => 'badge bg-warning-subtle text-warning',
                                            'paid'    => 'badge bg-success-subtle text-success',
                                            'failed'  => 'badge bg-danger-subtle text-danger',
                                            'refunded'=> 'badge bg-dark-subtle text-dark',
                                        ];
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold">
                                            <a href="<?= base_url('admin/orders/'.$order['id']) ?>" class="text-primary text-decoration-none">
                                                <?= esc($order['order_number']) ?>
                                            </a>
                                            <div class="small text-muted">
                                                <?= esc(strtoupper($order['payment_method'] ?? '')) ?>
                                            </div>
                                        </td>
                                        <td class="fw-bold">
                                            ₹<?= number_format($order['grand_total'], 2) ?>
                                        </td>
                                        <td>
                                            <span class="<?= $statusClassMap[$st] ?? 'badge bg-light text-dark' ?>">
                                                <?= ucfirst($st) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="<?= $payClassMap[$pst] ?? 'badge bg-light text-dark' ?>">
                                                <?= ucfirst($pst) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="<?= base_url('admin/orders/'.$order['id']) ?>" class="btn btn-sm btn-light border-0">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No orders for this customer with current filters.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($orders)): ?>
                    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Showing <?= count($orders) ?> of <?= $totalOrdersAll ?> orders
                        </small>
                        <?= $orderPager->links('cust_orders') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>