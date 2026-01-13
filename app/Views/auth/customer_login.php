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
    max-width:420px;
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
      <h4>Customer Login</h4>
      <p class="mb-0 small">Welcome back to GreenFarm! Login to continue shopping.</p>
    </div>
    <div class="auth-body">

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success small">
          <?= esc(session()->getFlashdata('success')) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error ?? '')): ?>
        <div class="alert alert-danger small">
          <?= esc($error) ?>
        </div>
      <?php endif; ?>

      <?php $old = $old ?? []; ?>

      <form action="<?= site_url('customer/login') ?>" method="post" autocomplete="off">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Email or Username</label>
          <input type="text" name="login"
                 class="form-control"
                 value="<?= esc($old['login'] ?? '') ?>"
                 placeholder="Enter email or username">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Enter password">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="rememberMe">
            <label class="form-check-label small" for="rememberMe">
              Remember me
            </label>
          </div>
          <a href="#" class="small text-decoration-none">Forgot password?</a>
          <!-- Later, link this to your reset-token based password reset page -->
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-veg btn-lg">
            Login
          </button>
        </div>

        <p class="text-center mt-3 mb-0 small">
          New to GreenFarm?
          <a href="<?= site_url('customer/register') ?>">Create an account</a>
        </p>
      </form>

    </div>
  </div>
</div>

<?= $this->endSection() ?>