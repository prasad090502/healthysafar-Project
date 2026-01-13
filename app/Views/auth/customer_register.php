<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
  .auth-wrapper{
    min-height: calc(100vh - 120px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 15px;
  }
  .auth-card{
    max-width:480px;
    width:100%;
    border-radius:18px;
    border:1px solid #e5f2e5;
    box-shadow:0 18px 40px rgba(0,0,0,.06);
    overflow:hidden;
  }
  .auth-header{
    background:linear-gradient(135deg,#4caf50,#7ccf6a);
    color:#fff;
    padding:22px 24px;
  }
  .auth-header h4{
    margin:0;
    font-weight:700;
  }
  .auth-body{
    padding:24px 24px 26px;
    background:#ffffff;
  }
  .btn-veg{
    background:#4caf50;
    border-color:#4caf50;
    color:#fff;
  }
  .btn-veg:hover{
    background:#43a047;
    border-color:#43a047;
    color:#fff;
  }
</style>

<div class="auth-wrapper">
  <div class="auth-card bg-white">
    <div class="auth-header">
      <h4>Customer Registration</h4>
      <p class="mb-0 small">Create your GreenFarm account to shop vegetables, fruits & more.</p>
    </div>
    <div class="auth-body">

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success small">
          <?= esc(session()->getFlashdata('success')) ?>
        </div>
      <?php endif; ?>

      <?php $errors = $errors ?? []; $old = $old ?? []; ?>

      <form action="<?= site_url('customer/register') ?>" method="post" autocomplete="off">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name"
                 class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                 value="<?= esc($old['name'] ?? '') ?>"
                 placeholder="e.g. Rohan Patil">
          <?php if (isset($errors['name'])): ?>
            <div class="invalid-feedback"><?= esc($errors['name']) ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username"
                 class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                 value="<?= esc($old['username'] ?? '') ?>"
                 placeholder="Choose a unique username">
          <?php if (isset($errors['username'])): ?>
            <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email"
                 class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                 value="<?= esc($old['email'] ?? '') ?>"
                 placeholder="you@example.com">
          <?php if (isset($errors['email'])): ?>
            <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact"
                 class="form-control"
                 value="<?= esc($old['contact'] ?? '') ?>"
                 placeholder="10-digit mobile number">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password"
                 class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                 placeholder="Minimum 6 characters">
          <?php if (isset($errors['password'])): ?>
            <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirm"
                 class="form-control <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>"
                 placeholder="Re-enter password">
          <?php if (isset($errors['password_confirm'])): ?>
            <div class="invalid-feedback"><?= esc($errors['password_confirm']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Role fixed as customer, but still present -->
        <input type="hidden" name="role" value="customer">

        <!-- Reset token will be NULL initially; generated later when user clicks "Forgot password" -->

        <div class="d-grid mt-3">
          <button type="submit" class="btn btn-veg btn-lg">
            Create Account
          </button>
        </div>

        <p class="text-center mt-3 mb-0 small">
          Already have an account?
          <a href="<?= site_url('customer/login') ?>">Login here</a>
        </p>
      </form>

    </div>
  </div>
</div>

<?= $this->endSection() ?>