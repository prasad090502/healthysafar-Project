<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $errors = $errors ?? []; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4 p-md-5">
          <h4 class="mb-3">Change Password</h4>
          <p class="text-muted small mb-4">
            Keep your account secure by using a strong, unique password.
          </p>

          <form action="<?= site_url('customer/password') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label">Current Password</label>
              <input type="password" name="current_password"
                     class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>">
              <?php if (isset($errors['current_password'])): ?>
                <div class="invalid-feedback"><?= esc($errors['current_password']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" name="new_password"
                     class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>">
              <?php if (isset($errors['new_password'])): ?>
                <div class="invalid-feedback"><?= esc($errors['new_password']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm New Password</label>
              <input type="password" name="confirm_password"
                     class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>">
              <?php if (isset($errors['confirm_password'])): ?>
                <div class="invalid-feedback"><?= esc($errors['confirm_password']) ?></div>
              <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
              <a href="<?= site_url('customer/profile') ?>" class="btn btn-outline-secondary">
                Back to Profile
              </a>
              <button type="submit" class="btn btn-success">
                Update Password
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>