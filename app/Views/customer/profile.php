<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-4 p-md-5">
                    <h4 class="mb-3">My Profile</h4>
                    <p class="text-muted small mb-4">
                        Review your account details for Healthy Safar.
                    </p>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success small">
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <?php
                      $customer = $customer ?? [];
                    ?>

                    <dl class="row mb-0">
                        <dt class="col-sm-4">Name</dt>
                        <dd class="col-sm-8"><?= esc($customer['name'] ?? '-') ?></dd>

                        <dt class="col-sm-4">Username</dt>
                        <dd class="col-sm-8"><?= esc($customer['username'] ?? '-') ?></dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8"><?= esc($customer['email'] ?? '-') ?></dd>

                        <dt class="col-sm-4">Contact</dt>
                        <dd class="col-sm-8"><?= esc($customer['contact'] ?? '-') ?></dd>

                        <dt class="col-sm-4">Role</dt>
                        <dd class="col-sm-8">
                            <?= esc(ucfirst($customer['role'] ?? 'customer')) ?>
                        </dd>
                    </dl>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <a href="<?= site_url('customer/orders') ?>" class="btn btn-success">
                            View My Orders
                        </a>
                        <a href="<?= site_url('customer/addresses') ?>" class="btn btn-outline-success">
                            Manage Addresses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>