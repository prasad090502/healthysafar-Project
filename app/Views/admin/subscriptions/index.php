<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
$subscriptions   = $subscriptions   ?? [];
$plans           = $plans           ?? [];
$selectedStatus  = $selectedStatus  ?? '';
$selectedPlanId  = $selectedPlanId  ?? '';
$dateFrom        = $dateFrom        ?? '';
$dateTo          = $dateTo          ?? '';
$deliveredCounts = $deliveredCounts ?? [];
$pager           = $pager ?? null;

$statuses = [
    'pending'   => 'Pending',
    'active'    => 'Active',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
];
?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Subscriptions</h4>
    </div>

    <!-- Filters -->
    <form method="get" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <?php foreach ($statuses as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $selectedStatus === $val ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Plan</label>
                    <select name="plan_id" class="form-select form-select-sm">
                        <option value="">All plans</option>
                        <?php foreach ($plans as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= (string)$selectedPlanId === (string)$p['id'] ? 'selected' : '' ?>>
                                <?= esc($p['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Start From</label>
                    <input type="date" name="date_from"
                           value="<?= esc($dateFrom) ?>"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Start To</label>
                    <input type="date" name="date_to"
                           value="<?= esc($dateTo) ?>"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary w-100 mt-3 mt-md-0">Apply</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Plan</th>
                            <th>Customer</th>
                            <th>Duration</th>
                            <th>Dates</th>
                            <th>Deliveries</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($subscriptions)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">
                                    No subscriptions found for selected filters.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($subscriptions as $s): ?>
                                <?php
                                $delivered = $deliveredCounts[$s['id']] ?? 0;
                                $planned   = (int)($s['total_deliveries_planned'] ?? 0);

                                $customerName = 'Customer #' . ($s['customer_id'] ?? $s['user_id'] ?? '-');
                                ?>
                                <tr>
                                    <td><?= (int)$s['id'] ?></td>
                                    <td>
                                        <?= esc($s['plan_title'] ?? '') ?><br>
                                        <small class="text-muted">
                                            Slot: <?= esc($s['default_slot_key'] ?? '-') ?>
                                        </small>
                                    </td>
                                    <td><?= esc($customerName) ?></td>
                                    <td><?= (int)$s['duration_days'] ?> days</td>
                                    <td>
                                        <small>
                                            <?= esc($s['start_date']) ?> → <?= esc($s['end_date']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <?= $delivered ?> / <?= $planned ?> delivered
                                        </small>
                                    </td>
                                    <td>
                                        ₹<?= number_format((float)$s['total_price'], 2) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $s['status'];
                                        $badgeClass = match ($status) {
                                            'pending'   => 'bg-secondary',
                                            'active'    => 'bg-info',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default     => 'bg-light text-dark',
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($pager): ?>
            <div class="card-footer">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>