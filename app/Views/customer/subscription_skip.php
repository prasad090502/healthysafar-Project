<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$delivery = $delivery ?? [];
$minDate  = $minDate ?? date('Y-m-d');
$maxDate  = $maxDate ?? date('Y-m-d', strtotime('+6 days'));
$errors   = $errors ?? [];

$delDate  = $delivery['delivery_date'] ?? null;
?>

<style>
    .hs-page-shell { padding-block: 30px 50px; }
    .hs-card {
        border-radius: 18px;
        border: 1px solid #fee2e2;
        background: #fff7ed;
        box-shadow: 0 18px 40px rgba(248,113,113,.15);
    }
    .hs-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #fed7aa;
    }
    .hs-card-body { padding: 18px 20px; }
</style>

<div class="hs-page-shell">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">

                <div class="mb-3">
                    <a href="<?= site_url('customer/orders') ?>" class="btn btn-sm btn-outline-secondary">
                        ← Back to My Orders
                    </a>
                </div>

                <div class="hs-card">
                    <div class="hs-card-header">
                        <h5 class="mb-1">Postpone this Delivery</h5>
                        <small class="text-muted">
                            Current delivery date: <?= esc(date('d M Y, D', strtotime($delDate))) ?>
                        </small>
                    </div>
                    <div class="hs-card-body">
                        <p class="small text-muted">
                            You can postpone this delivery to a future date. Your subscription end date
                            may extend accordingly. You can pick any valid date between
                            <strong><?= esc(date('d M Y', strtotime($minDate))) ?></strong> and
                            <strong><?= esc(date('d M Y', strtotime($maxDate))) ?></strong>, excluding off-days.
                        </p>

                        <?php if (! empty($errors['new_date'])): ?>
                            <div class="alert alert-danger py-2"><?= esc($errors['new_date']) ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label class="form-label">New delivery date</label>
                                <input type="date"
                                       name="new_date"
                                       class="form-control"
                                       min="<?= esc($minDate) ?>"
                                       max="<?= esc($maxDate) ?>"
                                       required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= site_url('customer/orders') ?>" class="btn btn-light">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    Confirm Postpone
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>