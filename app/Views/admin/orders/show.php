<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Order <?= esc($order['order_number']) ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Order <?= esc($order['order_number']) ?></h3>
        <p class="text-muted mb-0">
            Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?> ·
            <span class="text-muted">ID: #<?= $order['id'] ?></span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/orders') ?>" class="btn btn-light">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
        <a href="<?= base_url('admin/orders?status='.$order['status'].'&q='.$order['order_number']) ?>"
           class="btn btn-outline-secondary">
            View in List
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Top Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted text-uppercase small fw-bold">Order Status</span>
                <?php
                $st  = $order['status'];
                $statusClassMap = [
                    'pending'    => 'badge bg-warning-subtle text-warning',
                    'processing' => 'badge bg-info-subtle text-info',
                    'shipped'    => 'badge bg-primary-subtle text-primary',
                    'delivered'  => 'badge bg-success-subtle text-success',
                    'cancelled'  => 'badge bg-secondary-subtle text-secondary',
                    'refunded'   => 'badge bg-dark-subtle text-dark',
                ];
                ?>
                <span class="<?= $statusClassMap[$st] ?? 'badge bg-light text-dark' ?>">
                    <?= ucfirst($st) ?>
                </span>
            </div>
            <div class="fw-bold fs-4 mb-1">
                ₹<?= number_format($order['grand_total'], 2) ?>
            </div>
            <div class="text-muted small">
                Subtotal: ₹<?= number_format($order['subtotal'], 2) ?> ·
                Tax: ₹<?= number_format($order['tax_amount'], 2) ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="text-muted text-uppercase small fw-bold mb-2">Customer</div>
            <div class="d-flex align-items-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary me-3"
                     style="width:42px;height:42px;font-size:1rem">
                    <?= strtoupper(substr($order['customer_name'] ?? 'G', 0, 1)) ?>
                </div>
                <div>
                    <div class="fw-bold"><?= esc($order['customer_name'] ?? 'Guest') ?></div>
                    <?php if (!empty($order['customer_email'])): ?>
                        <div class="small text-muted"><?= esc($order['customer_email']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($order['customer_contact'])): ?>
                        <div class="small text-muted">📞 <?= esc($order['customer_contact']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="text-muted text-uppercase small fw-bold mb-2">Payment</div>
            <?php
            $pst = $order['payment_status'];
            $payClassMap = [
                'pending' => 'badge bg-warning-subtle text-warning',
                'paid'    => 'badge bg-success-subtle text-success',
                'failed'  => 'badge bg-danger-subtle text-danger',
                'refunded'=> 'badge bg-dark-subtle text-dark',
            ];
            ?>
            <div class="mb-2">
                <span class="<?= $payClassMap[$pst] ?? 'badge bg-light text-dark' ?>">
                    <?= ucfirst($pst) ?>
                </span>
                <?php if (!empty($order['payment_method'])): ?>
                    <span class="ms-2 small text-muted">via <?= esc(ucfirst($order['payment_method'])) ?></span>
                <?php endif; ?>
            </div>
            <div class="text-muted small">
                Shipping: ₹<?= number_format($order['shipping_amount'], 2) ?> ·
                Discount: ₹<?= number_format($order['discount_amount'], 2) ?>
            </div>
        </div>
    </div>
</div>

<!-- Addresses + Status Update -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="fw-bold mb-0">Shipping Address</h6>
            </div>
            <div class="card-body">
                <?php if ($shipping): ?>
                    <div class="fw-semibold mb-1"><?= esc($shipping['name']) ?></div>
                    <div class="small text-muted mb-2">
                        <?= esc($shipping['address_line1']) ?><br>
                        <?php if (!empty($shipping['address_line2'])): ?>
                            <?= esc($shipping['address_line2']) ?><br>
                        <?php endif; ?>
                        <?php if (!empty($shipping['landmark'])): ?>
                            Landmark: <?= esc($shipping['landmark']) ?><br>
                        <?php endif; ?>
                        <?= esc($shipping['city']) ?>, <?= esc($shipping['state']) ?> - <?= esc($shipping['pincode']) ?><br>
                        <?= esc($shipping['country']) ?>
                    </div>
                    <div class="small text-muted">
                        📞 <?= esc($shipping['phone']) ?>
                        <?php if (!empty($shipping['alternate_phone'])): ?>
                            <br>Alt: <?= esc($shipping['alternate_phone']) ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small mb-0">No shipping address set.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="fw-bold mb-0">Billing Address</h6>
            </div>
            <div class="card-body">
                <?php if ($billing): ?>
                    <div class="fw-semibold mb-1"><?= esc($billing['name']) ?></div>
                    <div class="small text-muted mb-2">
                        <?= esc($billing['address_line1']) ?><br>
                        <?php if (!empty($billing['address_line2'])): ?>
                            <?= esc($billing['address_line2']) ?><br>
                        <?php endif; ?>
                        <?php if (!empty($billing['landmark'])): ?>
                            Landmark: <?= esc($billing['landmark']) ?><br>
                        <?php endif; ?>
                        <?= esc($billing['city']) ?>, <?= esc($billing['state']) ?> - <?= esc($billing['pincode']) ?><br>
                        <?= esc($billing['country']) ?>
                    </div>
                    <div class="small text-muted">
                        📞 <?= esc($billing['phone']) ?>
                        <?php if (!empty($billing['alternate_phone'])): ?>
                            <br>Alt: <?= esc($billing['alternate_phone']) ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small mb-0">No billing address set.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Status Update Form -->
    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="fw-bold mb-0">Update Status</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/orders/'.$order['id'].'/update-status') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <label class="form-label small text-muted mb-1">Order Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <?php foreach ($statusOptions as $opt): ?>
                                <option value="<?= $opt ?>" <?= $opt === $order['status'] ? 'selected' : '' ?>>
                                    <?= ucfirst($opt) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Payment Status</label>
                        <select name="payment_status" class="form-select form-select-sm">
                            <?php foreach ($paymentOptions as $opt): ?>
                                <option value="<?= $opt ?>" <?= $opt === $order['payment_status'] ? 'selected' : '' ?>>
                                    <?= ucfirst($opt) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-success w-100">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Items + Totals -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0">
            <div class="card-header bg-white border-0">
                <h6 class="fw-bold mb-0">Order Items</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                        <tr>
                            <th>Item</th>
                            <th>SKU</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= esc($item['product_name']) ?></td>
                                    <td><?= esc($item['product_sku']) ?></td>
                                    <td class="text-center"><?= (int) $item['qty'] ?></td>
                                    <td class="text-end">₹<?= number_format($item['unit_price'], 2) ?></td>
                                    <td class="text-end pe-4">₹<?= number_format($item['total_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No items found for this order.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Totals -->
    <div class="col-lg-4">
        <div class="card border-0">
            <div class="card-header bg-white border-0">
                <h6 class="fw-bold mb-0">Invoice Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>₹<?= number_format($order['subtotal'], 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax</span>
                    <span>₹<?= number_format($order['tax_amount'], 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Shipping</span>
                    <span>₹<?= number_format($order['shipping_amount'], 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Discount</span>
                    <span>- ₹<?= number_format($order['discount_amount'], 2) ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-2">
                    <span>Grand Total</span>
                    <span>₹<?= number_format($order['grand_total'], 2) ?></span>
                </div>
                <p class="small text-muted mb-0">
                    Currency: <?= esc($order['currency']) ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Customer Order History -->
<?php if (!empty($customerOrders) && count($customerOrders) > 1): ?>
    <div class="card border-0">
        <div class="card-header bg-white border-0">
            <h6 class="fw-bold mb-0">Customer Order History</h6>
            <p class="small text-muted mb-0">
                Last <?= count($customerOrders) ?> orders for <?= esc($order['customer_name'] ?? 'this customer') ?>.
            </p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                    <tr>
                        <th>Order #</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Placed On</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($customerOrders as $custOrder): ?>
                        <tr <?= $custOrder['id'] == $order['id'] ? 'class="table-success"' : '' ?>>
                            <td class="fw-semibold">
                                <?= esc($custOrder['order_number']) ?>
                                <?php if ($custOrder['id'] == $order['id']): ?>
                                    <span class="badge bg-success-subtle text-success ms-1">Current</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?= ucfirst($custOrder['status']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?= ucfirst($custOrder['payment_status']) ?></span>
                            </td>
                            <td>₹<?= number_format($custOrder['grand_total'], 2) ?></td>
                            <td class="small text-muted">
                                <?= date('d M Y, h:i A', strtotime($custOrder['created_at'])) ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?= base_url('admin/orders/'.$custOrder['id']) ?>"
                                   class="btn btn-sm btn-light border-0">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>