<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$delivery = $delivery ?? [];
$errors   = $errors ?? [];

$delDate  = $delivery['delivery_date'] ?? null;
$current  = $delivery['notes'] ?? '';
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
                        <h5 class="mb-1">Add Note for Delivery</h5>
                        <small class="text-muted">
                            Delivery date: <?= esc(date('d M Y, D', strtotime($delDate))) ?>
                        </small>
                    </div>
                    <div class="hs-card-body">
                        <?php if (! empty($errors['note'])): ?>
                            <div class="alert alert-danger py-2"><?= esc($errors['note']) ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label class="form-label">Special instructions (optional)</label>
                                <textarea name="note" class="form-control" rows="4"
                                          placeholder="Example: Please call when you reach the gate, no onion, leave at reception if not available."><?= esc($current) ?></textarea>
                                <div class="form-text">
                                    We will do our best to follow your instructions for this specific delivery.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= site_url('customer/orders') ?>" class="btn btn-light">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Save Note
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