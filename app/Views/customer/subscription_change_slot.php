<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$delivery       = $delivery ?? [];
$slots          = $slots ?? [];
$currentSlotKey = $currentSlotKey ?? '';
$errors         = $errors ?? [];

$delDate = $delivery['delivery_date'] ?? null;
?>

<style>
    .hs-page-shell { padding-block: 30px 50px; }
    .hs-card {
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        background: #fff;
        box-shadow: 0 18px 40px rgba(15,23,42,.06);
    }
    .hs-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
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
                        <h5 class="mb-1">Change Delivery Slot</h5>
                        <small class="text-muted">
                            Delivery date: <?= esc(date('d M Y, D', strtotime($delDate))) ?>
                        </small>
                    </div>
                    <div class="hs-card-body">
                        <?php if (! empty($errors['slot_key'])): ?>
                            <div class="alert alert-danger py-2"><?= esc($errors['slot_key']) ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <?= csrf_field() ?>

                            <?php if (empty($slots)): ?>
                                <p class="text-muted">
                                    No slots configured for this plan.
                                </p>
                            <?php else: ?>
                                <?php foreach ($slots as $slot): ?>
                                    <?php
                                    $key    = $slot['key'] ?? '';
                                    $label  = $slot['label'] ?? ucfirst($key);
                                    $window = $slot['window'] ?? '';
                                    ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="slot_key" id="slot<?= esc($key) ?>"
                                               value="<?= esc($key) ?>"
                                            <?= $currentSlotKey === $key ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="slot<?= esc($key) ?>">
                                            <strong><?= esc($label) ?></strong>
                                            <?php if ($window): ?>
                                                <span class="text-muted small">· <?= esc($window) ?></span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="d-flex justify-content-end mt-3 gap-2">
                                <a href="<?= site_url('customer/orders') ?>" class="btn btn-light">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Save Slot
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