<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$customer = $customer ?? [];
$errors   = $errors ?? [];
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4 p-md-5">
          <h4 class="mb-3">Edit Profile</h4>
          <p class="text-muted small mb-4">
            Update your account details for deliveries and notifications.
          </p>

          <form action="<?= site_url('customer/profile/update') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name"
                     class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                     value="<?= esc($customer['name'] ?? '') ?>">
              <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback"><?= esc($errors['name']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email"
                     class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                     value="<?= esc($customer['email'] ?? '') ?>">
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact"
                     class="form-control"
                     value="<?= esc($customer['contact'] ?? '') ?>">
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
              <a href="<?= site_url('customer/profile') ?>" class="btn btn-outline-secondary">
                Cancel
              </a>
              <button type="submit" class="btn btn-success">
                Save Changes
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>