<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Customers <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Customers</h3>
        <p class="text-muted mb-0">Your HealthySafar community – see activity and value.</p>
    </div>
</div>

<div class="card border-0 mb-3">
    <div class="card-body pb-2">
        <form class="row g-2 align-items-end" method="get" action="<?= current_url() ?>">
            <div class="col-md-4">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="q" class="form-control" placeholder="Name, email, mobile"
                       value="<?= esc($q) ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100 mt-3 mt-md-0">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0 table-modern">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Customer</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Total Orders</th>
                        <th>Total Spent</th>
                        <th>Joined On</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $cust): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary me-2"
                                             style="width:32px;height:32px;font-size:0.85rem">
                                            <?= strtoupper(substr($cust['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block"><?= esc($cust['name']) ?></span>
                                            <small class="text-muted">Username: <?= esc($cust['username']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($cust['email']) ?></td>
                                <td><?= esc($cust['contact']) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?= (int) ($cust['total_orders'] ?? 0) ?>
                                    </span>
                                </td>
                                <td class="fw-bold">
                                    ₹<?= number_format($cust['total_spent'] ?? 0, 2) ?>
                                </td>
                                <td class="text-muted small">
                                    <?= date('d M Y', strtotime($cust['created_at'])) ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= base_url('admin/customers/'.$cust['id']) ?>" class="btn btn-sm btn-light border-0">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No customers found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($customers)): ?>
            <div class="p-3 border-top">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>