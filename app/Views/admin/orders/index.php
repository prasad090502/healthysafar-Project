<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Orders <?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .hs-stat-card {
        border-radius: 14px;
        border: 1px solid #eef1f6;
        padding: 14px 16px;
        background: #ffffff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.03);
    }
    .hs-stat-label {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 600;
        color: #6b7280;
    }
    .hs-stat-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #111827;
    }
    .table-modern tbody tr:hover {
        background-color: #f9fafb;
    }
    .hs-badge-select {
        padding: 0.1rem 0.4rem;
        border-radius: 999px;
        border: none;
        font-size: .75rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #374151;
        cursor: pointer;
    }
    .hs-badge-select:focus {
        outline: none;
        box-shadow: none;
    }
    .hs-order-checkbox {
        width: 1.1rem;
        height: 1.1rem;
        cursor: pointer;
    }
    .hs-bulk-bar {
        position: sticky;
        bottom: 0;
        z-index: 5;
        background: #ffffff;
        border-top: 1px solid #e5e7eb;
        padding: 8px 12px;
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
    }
    .hs-label-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        font-size: .75rem;
        cursor: pointer;
    }
    .hs-label-chip input {
        margin: 0;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Orders</h3>
        <p class="text-muted mb-0">Track all HealthySafar orders, payments, statuses & labels.</p>
    </div>
</div>

<!-- Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Total Orders</div>
            <div class="hs-stat-value"><?= (int)$metrics['total_orders'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Total Revenue</div>
            <div class="hs-stat-value">₹<?= number_format($metrics['total_revenue'], 2) ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Avg. Order Value</div>
            <div class="hs-stat-value">₹<?= number_format($metrics['avg_order_value'], 2) ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Orders Today</div>
            <div class="hs-stat-value"><?= (int)$metrics['today_orders'] ?></div>
        </div>
    </div>
</div>

<!-- Quick Status Summary -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Pending</div>
            <div class="hs-stat-value"><?= (int)$statusSummary['pending'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Processing</div>
            <div class="hs-stat-value"><?= (int)$statusSummary['processing'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Shipped</div>
            <div class="hs-stat-value"><?= (int)$statusSummary['shipped'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Delivered</div>
            <div class="hs-stat-value"><?= (int)$statusSummary['delivered'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Cancelled</div>
            <div class="hs-stat-value"><?= (int)$statusSummary['cancelled'] ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="hs-stat-card">
            <div class="hs-stat-label mb-1">Refunded</div>
            <div class="hs-stat-value"><?= (int)$statusSummary['refunded'] ?></div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 mb-3">
    <div class="card-body pb-2">
        <form class="row g-3 align-items-end" method="get" action="<?= current_url() ?>">
            <div class="col-md-2">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="all">All</option>
                    <?php foreach ($statusOptions as $opt): ?>
                        <option value="<?= $opt ?>" <?= $filters['status'] === $opt ? 'selected' : '' ?>>
                            <?= ucfirst($opt) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Payment</label>
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="all">All</option>
                    <?php foreach ($paymentOptions as $opt): ?>
                        <option value="<?= $opt ?>" <?= $filters['payment_status'] === $opt ? 'selected' : '' ?>>
                            <?= ucfirst($opt) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="<?= esc($filters['date_from']) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="<?= esc($filters['date_to']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Order # or Customer"
                       value="<?= esc($filters['q']) ?>">
            </div>
            <div class="col-md-1 text-end">
                <button class="btn btn-success btn-sm w-100">
                    <i class="bi bi-funnel me-1"></i> Go
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0 table-modern" id="ordersTable">
                <thead class="bg-light">
                <tr>
                    <th class="ps-3" style="width:32px;">
                        <input type="checkbox" id="selectAllOrders" class="form-check-input hs-order-checkbox">
                    </th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Created At</th>
                    <th class="text-end pe-4">Actions</th>
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
                        <tr data-order-id="<?= (int)$order['id'] ?>">
                            <td class="ps-3">
                                <input type="checkbox"
                                       class="form-check-input hs-order-checkbox js-order-check"
                                       value="<?= (int)$order['id'] ?>">
                            </td>
                            <td class="fw-bold text-primary">
                                <a href="<?= base_url('admin/orders/'.$order['id']) ?>">
                                    <?= esc($order['order_number']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary me-2"
                                         style="width:30px;height:30px;font-size:0.8rem">
                                        <?= strtoupper(substr($order['customer_name'] ?? 'G', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= esc($order['customer_name'] ?? 'Guest') ?></div>
                                        <?php if (!empty($order['customer_email'])): ?>
                                            <small class="text-muted"><?= esc($order['customer_email']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold">
                                ₹<?= number_format($order['grand_total'], 2) ?>
                            </td>
                            <td>
                                <select class="hs-badge-select js-inline-status"
                                        data-type="status"
                                        data-order-id="<?= (int)$order['id'] ?>">
                                    <?php foreach ($statusOptions as $opt): ?>
                                        <option value="<?= $opt ?>" <?= $opt === $st ? 'selected' : '' ?>>
                                            <?= ucfirst($opt) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="hs-badge-select js-inline-status"
                                        data-type="payment_status"
                                        data-order-id="<?= (int)$order['id'] ?>">
                                    <?php foreach ($paymentOptions as $opt): ?>
                                        <option value="<?= $opt ?>" <?= $opt === $pst ? 'selected' : '' ?>>
                                            <?= ucfirst($opt) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="text-muted small">
                                <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?= base_url('admin/orders/'.$order['id']) ?>"
                                   class="btn btn-sm btn-light border-0" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No orders found for this filter.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($orders)): ?>
            <div class="p-3 border-top">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>

        <!-- Bulk bar -->
        <div class="hs-bulk-bar" id="bulkBar">
            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted" id="bulkSelectedCount">0 selected</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <select class="form-select form-select-sm" id="bulkStatus">
                    <option value="">Order status…</option>
                    <?php foreach ($statusOptions as $opt): ?>
                        <option value="<?= $opt ?>"><?= ucfirst($opt) ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-select form-select-sm" id="bulkPayment">
                    <option value="">Payment status…</option>
                    <?php foreach ($paymentOptions as $opt): ?>
                        <option value="<?= $opt ?>"><?= ucfirst($opt) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-outline-secondary" type="button"
                        data-bs-toggle="modal" data-bs-target="#labelPrintModal">
                    <i class="bi bi-printer me-1"></i> Print Labels
                </button>
                <button class="btn btn-sm btn-success" type="button" id="applyBulkBtn">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Label Print Modal -->
<div class="modal fade" id="labelPrintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="get" action="<?= base_url('admin/orders/print-labels') ?>" target="_blank" id="labelForm">
                <div class="modal-header">
                    <h5 class="modal-title">Print Shipping / Product Labels</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Select which fields to show on the label and choose label size.
                        Works with A4 sheet labels and thermal label printers also.
                    </p>

                    <input type="hidden" name="order_ids" id="labelOrderIds" value="">

                    <div class="mb-3">
                        <label class="form-label small text-muted">Fields to show</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $fieldOptions = [
                                'order_number'   => 'Order #',
                                'customer_name'  => 'Customer Name',
                                'phone'          => 'Phone',
                                'address'        => 'Full Address',
                                'city'           => 'City',
                                'state'          => 'State',
                                'pincode'        => 'Pincode',
                                'grand_total'    => 'Amount',
                            ];
                            ?>
                            <?php foreach ($fieldOptions as $key => $label): ?>
                                <label class="hs-label-chip">
                                    <input type="checkbox" name="fields[]" value="<?= $key ?>" checked>
                                    <span><?= esc($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Label Size</label>
                            <select name="label_size" class="form-select form-select-sm">
                                <option value="A4_3x8">A4 – 3 x 8 (24 labels)</option>
                                <option value="A4_2x7">A4 – 2 x 7 (14 labels)</option>
                                <option value="TH_100x150">Thermal 100x150mm</option>
                                <option value="TH_50x70">Thermal 50x70mm</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Page Size</label>
                            <select name="page_size" class="form-select form-select-sm">
                                <option value="A4">A4 (210 x 297 mm)</option>
                                <option value="Letter">Letter (US)</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info small mt-3 mb-0">
                        <strong>Tip:</strong> In browser print dialog select correct paper size and “Actual size”
                        (no scaling) for proper label alignment on sheet/roll.
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-printer me-1"></i> Open Print Preview
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$csrfTokenName = csrf_token();
$csrfHash      = csrf_hash();
?>

<script>
    (function() {
        const selectAll = document.getElementById('selectAllOrders');
        const checks    = document.querySelectorAll('.js-order-check');
        const bulkBar   = document.getElementById('bulkBar');
        const bulkCount = document.getElementById('bulkSelectedCount');
        const bulkStatus = document.getElementById('bulkStatus');
        const bulkPayment = document.getElementById('bulkPayment');
        const applyBulkBtn = document.getElementById('applyBulkBtn');
        const labelOrderIds = document.getElementById('labelOrderIds');
        const csrfName = '<?= $csrfTokenName ?>';
        const csrfHash = '<?= $csrfHash ?>';

        function getSelectedIds() {
            const ids = [];
            document.querySelectorAll('.js-order-check:checked').forEach(cb => {
                ids.push(cb.value);
            });
            return ids;
        }

        function updateBulkBar() {
            const ids = getSelectedIds();
            if (ids.length > 0) {
                bulkBar.style.display = 'flex';
                bulkCount.innerText = ids.length + ' selected';
            } else {
                bulkBar.style.display = 'none';
                bulkCount.innerText = '0 selected';
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checks.forEach(cb => { cb.checked = selectAll.checked; });
                updateBulkBar();
            });
        }

        checks.forEach(cb => {
            cb.addEventListener('change', updateBulkBar);
        });

        // Inline single status change
        document.querySelectorAll('.js-inline-status').forEach(sel => {
            sel.addEventListener('change', function() {
                const orderId = this.dataset.orderId;
                const type    = this.dataset.type;
                const payload = new FormData();
                payload.append('mode', 'single');
                payload.append('order_id', orderId);
                if (type === 'status') {
                    payload.append('status', this.value);
                } else {
                    payload.append('payment_status', this.value);
                }
                payload.append(csrfName, csrfHash);

                fetch('<?= base_url('admin/orders/update-status-inline') ?>', {
                    method: 'POST',
                    body: payload,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status !== 'ok') {
                            alert(res.message || 'Failed to update');
                        }
                    })
                    .catch(() => alert('Failed to update'));
            });
        });

        // Bulk apply
        applyBulkBtn.addEventListener('click', function() {
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Select at least one order.');
                return;
            }
            const status  = bulkStatus.value;
            const payment = bulkPayment.value;
            if (!status && !payment) {
                alert('Select status and/or payment to apply.');
                return;
            }

            const payload = new FormData();
            payload.append('mode', 'bulk');
            ids.forEach(id => payload.append('order_ids[]', id));
            if (status)  payload.append('status', status);
            if (payment) payload.append('payment_status', payment);
            payload.append(csrfName, csrfHash);

            fetch('<?= base_url('admin/orders/update-status-inline') ?>', {
                method: 'POST',
                body: payload,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'ok') {
                        // Reload to reflect
                        location.reload();
                    } else {
                        alert(res.message || 'Failed to update');
                    }
                })
                .catch(() => alert('Failed to update'));
        });

        // Fill selected IDs for label modal
        const labelModal = document.getElementById('labelPrintModal');
        if (labelModal) {
            labelModal.addEventListener('show.bs.modal', function () {
                const ids = getSelectedIds();
                if (ids.length === 0) {
                    alert('Select at least one order to print labels.');
                    const modal = bootstrap.Modal.getInstance(labelModal);
                    modal.hide();
                    return;
                }
                labelOrderIds.value = ids.join(',');
            });
        }
    })();
</script>

<?= $this->endSection() ?>