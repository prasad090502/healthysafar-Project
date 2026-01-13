<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0">My Addresses</h4>
                    <p class="text-muted small mb-0">
                        Manage your delivery addresses for faster checkout.
                    </p>
                </div>
                <a href="<?= site_url('customer/addresses/add') ?>" class="btn btn-sm btn-success">
                    <i class="far fa-plus me-1"></i> Add New
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success small">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (empty($addresses)): ?>
                <div class="alert alert-info">
                    You have not added any delivery addresses yet.
                    <br>
                    Use <strong>"Add New"</strong> to save your first address.
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($addresses as $addr): ?>
                        <?php
                            $type      = ucfirst($addr['address_type'] ?? 'Home');
                            $isDefault = !empty($addr['is_default']);
                        ?>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 rounded-4 h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge bg-light text-success border small">
                                            <i class="far fa-location-dot me-1"></i><?= esc($type) ?>
                                        </span>
                                        <?php if ($isDefault): ?>
                                            <span class="badge bg-success-subtle text-success small">
                                                <i class="far fa-star me-1"></i>Default
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <h6 class="fw-semibold mb-1">
                                        <?= esc($addr['name'] ?? 'Receiver') ?>
                                    </h6>

                                    <p class="small mb-2">
                                        <?= esc($addr['address_line1'] ?? '') ?><br>
                                        <?php if (!empty($addr['address_line2'])): ?>
                                            <?= esc($addr['address_line2']) ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($addr['landmark'])): ?>
                                            <?= esc($addr['landmark']) ?><br>
                                        <?php endif; ?>
                                        <?= esc($addr['city'] ?? '') ?>
                                        <?php if (!empty($addr['pincode'])): ?>
                                            - <?= esc($addr['pincode']) ?>
                                        <?php endif; ?><br>
                                        <?= esc($addr['state'] ?? '') ?>
                                        <?php if (!empty($addr['country'])): ?>
                                            , <?= esc($addr['country']) ?>
                                        <?php endif; ?>
                                    </p>

                                    <p class="small text-muted mb-0">
                                        <i class="far fa-phone-alt me-1"></i>
                                        <?= esc($addr['phone'] ?? '-') ?>
                                        <?php if (!empty($addr['alternate_phone'])): ?>
                                            <span class="ms-2">Alt: <?= esc($addr['alternate_phone']) ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>