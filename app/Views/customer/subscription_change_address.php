<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$delivery       = $delivery ?? [];
$addresses      = $addresses ?? [];
$currentAddress = $currentAddress ?? null;
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
    .hs-addr-box {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 10px 12px;
        background: #f9fafb;
        font-size: .9rem;
    }
    .hs-addr-label {
        font-weight: 600;
        margin-bottom: 4px;
        color: #0f172a;
    }
    .hs-section-title {
        font-weight: 600;
        font-size: .95rem;
        margin-bottom: 8px;
    }
</style>

<div class="hs-page-shell">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">

                <div class="mb-3">
                    <a href="<?= site_url('customer/orders') ?>" class="btn btn-sm btn-outline-secondary">
                        ← Back to My Orders
                    </a>
                </div>

                <div class="hs-card">
                    <div class="hs-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="mb-1">Change Delivery Address</h5>
                            <small class="text-muted">
                                Delivery date: <?= esc(date('d M Y, D', strtotime($delDate))) ?>
                            </small>
                        </div>
                    </div>
                    <div class="hs-card-body">
                        <?php if (! empty($errors['address_id'])): ?>
                            <div class="alert alert-danger py-2"><?= esc($errors['address_id']) ?></div>
                        <?php endif; ?>
                        <?php if (! empty($errors['new_address'])): ?>
                            <div class="alert alert-danger py-2"><?= esc($errors['new_address']) ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <?= csrf_field() ?>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="hs-section-title">Current address</div>
                                    <?php if ($currentAddress): ?>
                                        <div class="hs-addr-box">
                                            <div class="hs-addr-label">
                                                <?= esc($currentAddress['name']) ?>
                                                <?php if ($currentAddress['is_default']): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">Default</span>
                                                <?php endif; ?>
                                            </div>
                                            <div><?= esc($currentAddress['address_line1']) ?></div>
                                            <?php if ($currentAddress['address_line2']): ?>
                                                <div><?= esc($currentAddress['address_line2']) ?></div>
                                            <?php endif; ?>
                                            <div>
                                                <?= esc($currentAddress['city']) ?>,
                                                <?= esc($currentAddress['state']) ?> - <?= esc($currentAddress['pincode']) ?>
                                            </div>
                                            <div><?= esc($currentAddress['country']) ?></div>
                                            <div class="mt-1 text-muted small">
                                                Phone: <?= esc($currentAddress['phone']) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No base address found for this delivery.</p>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <div class="hs-section-title">Choose another address</div>
                                    <div class="mb-2">
                                        <?php foreach ($addresses as $addr): ?>
                                            <?php
                                            $id  = $addr['id'];
                                            $lbl = $addr['address_type'] === 'office' ? 'Office' : 'Home';
                                            ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio"
                                                       name="address_id" id="addr<?= $id ?>" value="<?= $id ?>">
                                                <label class="form-check-label" for="addr<?= $id ?>">
                                                    <strong><?= esc($addr['name']) ?></strong>
                                                    <span class="badge bg-light text-muted border ms-1">
                                                        <?= esc($lbl) ?>
                                                    </span><br>
                                                    <small class="text-muted">
                                                        <?= esc($addr['address_line1']) ?>,
                                                        <?= esc($addr['city']) ?> - <?= esc($addr['pincode']) ?>
                                                    </small>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>

                                        <?php if (empty($addresses)): ?>
                                            <p class="text-muted small mb-0">
                                                You don't have any saved addresses yet. Use the form below to add one.
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <hr>

                                    <div class="hs-section-title">Or add a new address</div>

                                    <div class="row g-2">
                                        <div class="col-12">
                                            <input type="text" name="new_name" class="form-control form-control-sm"
                                                   placeholder="Full Name">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="new_phone" class="form-control form-control-sm"
                                                   placeholder="Phone">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="new_address_line1" class="form-control form-control-sm"
                                                   placeholder="Address line 1">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="new_address_line2" class="form-control form-control-sm"
                                                   placeholder="Address line 2 (optional)">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="new_landmark" class="form-control form-control-sm"
                                                   placeholder="Landmark (optional)">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="new_city" class="form-control form-control-sm"
                                                   placeholder="City">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="new_state" class="form-control form-control-sm"
                                                   placeholder="State">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="new_pincode" class="form-control form-control-sm"
                                                   placeholder="Pincode">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="new_country" class="form-control form-control-sm"
                                                   placeholder="Country (default India)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3 gap-2">
                                <a href="<?= site_url('customer/orders') ?>" class="btn btn-light">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Save Address
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