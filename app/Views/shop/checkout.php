<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// Initialize variables
$items             = $cartItems         ?? [];
$subTotal          = (float)($subTotal  ?? 0);
$shippingAmount    = (float)($shippingAmount ?? 0);
$orderTotal        = (float)($orderTotal ?? 0);
$currencySymbol    = $currencySymbol    ?? '₹';
$addresses         = $addresses         ?? [];
$selectedAddressId = $selectedAddressId ?? null;
?>

<style>
    :root {
        --hs-primary: #10b981;      /* Emerald 500 */
        --hs-primary-dark: #059669; /* Emerald 600 */
        --hs-bg: #f3f4f6;
        --hs-border: #e5e7eb;
        --hs-muted: #6b7280;
        --hs-text: #111827;
    }

    body {
        background-color: var(--hs-bg);
    }

    /* ---------- Stepper (2 steps) ---------- */
    .hs-stepper-wrap {
        display: flex;
        justify-content: center;
        margin-bottom: 2.25rem;
    }
    .hs-stepper {
        display: flex;
        align-items: center;
        gap: 1.75rem;
        max-width: 520px;
        width: 100%;
    }
    .hs-step {
        display: flex;
        align-items: center;
        gap: .65rem;
        flex: 0 0 auto;
    }
    .hs-step-circle {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 2px solid var(--hs-border);
        background: #fff;
        color: var(--hs-muted);
        font-size: .9rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 0 transparent;
        transition: all .2s ease;
    }
    .hs-step-label {
        font-size: .9rem;
        font-weight: 500;
        color: var(--hs-muted);
        white-space: nowrap;
    }
    .hs-step.completed .hs-step-circle {
        border-color: var(--hs-primary);
        background: var(--hs-primary);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(16,185,129,.15);
    }
    .hs-step.completed .hs-step-label {
        color: var(--hs-text);
        font-weight: 600;
    }
    .hs-step.active .hs-step-circle {
        border-color: var(--hs-primary);
        background: #ecfdf5;
        color: var(--hs-primary-dark);
        box-shadow: 0 0 0 4px rgba(16,185,129,.1);
    }
    .hs-step.active .hs-step-label {
        color: var(--hs-text);
        font-weight: 700;
    }

    .hs-step-line {
        flex: 1;
        height: 2px;
        border-radius: 999px;
        background: linear-gradient(
            to right,
            var(--hs-primary) 0%,
            var(--hs-primary) 40%,
            var(--hs-border) 40%,
            var(--hs-border) 100%
        );
    }

    /* ---------- Cards ---------- */
    .hs-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid var(--hs-border);
        box-shadow: 0 10px 30px rgba(15,23,42,.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .hs-card-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }
    .hs-card-title {
        margin: 0;
        display: flex;
        align-items: center;
        gap: .85rem;
        font-size: 1.02rem;
        font-weight: 700;
        color: var(--hs-text);
    }
    .hs-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
    }

    /* ---------- Address selection ---------- */
    .hs-address-col {
        transition: transform .15s ease;
    }
    .hs-address-label {
        display: block;
        cursor: pointer;
        height: 100%;
        position: relative;
    }
    .hs-address-box {
        border-radius: 14px;
        border: 2px solid var(--hs-border);
        padding: 1.1rem 1.1rem .9rem;
        height: 100%;
        background: #fff;
        transition: all .18s ease;
    }
    .hs-address-radio:checked + .hs-address-label .hs-address-box {
        border-color: var(--hs-primary);
        background: #ecfdf5;
        box-shadow: 0 10px 28px rgba(16,185,129,.2);
        transform: translateY(-1px);
    }
    .hs-address-check {
        position: absolute;
        top: .85rem;
        right: .9rem;
        color: var(--hs-primary);
        opacity: 0;
        transform: scale(.6);
        transition: all .18s ease;
    }
    .hs-address-radio:checked + .hs-address-label .hs-address-check {
        opacity: 1;
        transform: scale(1);
    }
    .hs-badge-type {
        font-size: .68rem;
        padding: .18rem .6rem;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 600;
        background: #e5e7eb;
        color: #374151;
    }
    .hs-badge-default {
        font-size: .65rem;
        padding: .18rem .65rem;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-weight: 600;
    }
    .hs-address-name {
        font-weight: 700;
        font-size: .98rem;
        color: var(--hs-text);
        margin-bottom: .1rem;
    }
    .hs-address-text {
        font-size: .8rem;
        color: var(--hs-muted);
        line-height: 1.45;
        min-height: 2.4em;
    }
    .hs-address-phone {
        font-size: .8rem;
        padding-top: .45rem;
        border-top: 1px dashed rgba(148,163,184,.5);
        color: #065f46;
        font-weight: 600;
    }

    /* ---------- Order notes ---------- */
    .hs-notes-textarea {
        min-height: 90px;
        resize: vertical;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: .9rem;
    }
    .hs-notes-textarea:focus {
        border-color: var(--hs-primary);
        box-shadow: 0 0 0 3px rgba(16,185,129,.15);
        background: #fff;
    }

    /* ---------- Bottom payment bar ---------- */
    .hs-payment-bar {
        background: #fff;
        border-radius: 18px;
        border: 1px solid var(--hs-border);
        padding: 1.25rem 1.5rem;
        margin-top: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 -6px 26px rgba(15,23,42,.05);
    }
    .hs-pay-total-label {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .15em;
        color: var(--hs-muted);
        font-weight: 600;
    }
    .hs-pay-total-amount {
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--hs-text);
        line-height: 1.1;
    }

    .hs-btn-back-circle {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        border: none;
        background: #f3f4f6;
        color: #4b5563;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .18s ease;
    }
    .hs-btn-back-circle:hover {
        background: #e5e7eb;
        color: #111827;
        transform: translateX(-2px);
    }

    .hs-btn-pay {
        border: none;
        background: var(--hs-primary);
        color: #fff;
        border-radius: 999px;
        padding: .85rem 2.7rem;
        font-size: 1.02rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        min-width: 210px;
        transition: all .18s ease;
    }
    .hs-btn-pay:hover:not(:disabled) {
        background: var(--hs-primary-dark);
        box-shadow: 0 8px 26px rgba(16,185,129,.35);
        transform: translateY(-1px);
        color: #fff;
    }
    .hs-btn-pay:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    .hs-validation-error {
        font-size: .8rem;
        color: #b91c1c;
        margin-top: .15rem;
    }

    @media (max-width: 768px) {
        .hs-stepper-wrap { display: none; }
        .checkout-wrapper { padding-bottom: 90px; }
        .hs-payment-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 18px 18px 0 0;
            border-bottom: none;
            padding-inline: 1.25rem;
            z-index: 1040;
        }
        .hs-btn-pay {
            width: 100%;
            min-width: 0;
        }
        .hs-pay-total-amount {
            font-size: 1.35rem;
        }
    }
</style>

<!-- Tiny breadcrumb strip -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    <a href="<?= site_url('/') ?>" class="text-decoration-none text-muted">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= site_url('cart') ?>" class="text-decoration-none text-muted">Cart</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<div class="checkout-wrapper py-5">
    <div class="container">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="row justify-content-center mb-3">
                <div class="col-lg-8">
                    <div class="alert alert-danger border-0 rounded-3 shadow-sm d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fs-5 me-3"></i>
                        <div><?= esc(session()->getFlashdata('error')) ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="row justify-content-center mb-3">
                <div class="col-lg-8">
                    <div class="alert alert-success border-0 rounded-3 shadow-sm d-flex align-items-center">
                        <i class="fas fa-check-circle fs-5 me-3"></i>
                        <div><?= esc(session()->getFlashdata('success')) ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 2-step stepper -->
        <div class="hs-stepper-wrap d-none d-md-flex">
            <div class="hs-stepper">
                <div class="hs-step completed">
                    <div class="hs-step-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="hs-step-label">Cart</div>
                </div>

                <div class="hs-step-line"></div>

                <div class="hs-step active">
                    <div class="hs-step-circle">2</div>
                    <div class="hs-step-label">Delivery &amp; Payment</div>
                </div>
            </div>
        </div>

        <!-- MAIN CHECKOUT FORM (ONLY FOR PAYMENT & SHIPPING SELECTION) -->
        <form id="checkout-form" action="<?= site_url('checkout/place') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="from_checkout" value="1">
            <input type="hidden" name="payment_method" value="online">

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <!-- Address card -->
                    <div class="hs-card">
                        <div class="hs-card-header d-flex justify-content-between align-items-center">
                            <h2 class="hs-card-title">
                                <span class="hs-card-icon bg-success-subtle text-success">
                                    <i class="fas fa-location-dot"></i>
                                </span>
                                Select Delivery Address
                            </h2>

                            <?php if (!empty($addresses)): ?>
                                <button type="button"
                                        class="btn btn-sm btn-outline-dark rounded-pill px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addAddressModal">
                                    <i class="fas fa-plus me-1"></i> Add New
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="p-4" id="hs-address-wrapper" data-hs-address-wrapper>
                            <?php if (empty($addresses)): ?>
                                <div class="text-center py-4" id="hs-address-empty">
                                    <div class="mb-3">
                                        <i class="fas fa-map-marked-alt text-muted"
                                           style="font-size: 3rem; opacity:.3;"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">No addresses found</h5>
                                    <p class="text-muted mb-3">
                                        Add a delivery address to continue with your order.
                                    </p>
                                    <button type="button"
                                            class="btn btn-success rounded-pill px-4"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal">
                                        Create Address
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="row g-3" id="hs-address-grid">
                                    <?php foreach ($addresses as $addr): ?>
                                        <?php
                                            $id        = (int) $addr['id'];
                                            $isDefault = !empty($addr['is_default']);
                                            $type      = strtoupper($addr['address_type'] ?? 'Home');
                                            $isChecked = ($selectedAddressId == $id)
                                                || (!$selectedAddressId && $isDefault);
                                        ?>
                                        <div class="col-md-6 hs-address-col">
                                            <input type="radio"
                                                   class="d-none hs-address-radio"
                                                   name="shipping_address_id"
                                                   id="addr-<?= $id ?>"
                                                   value="<?= $id ?>"
                                                   <?= $isChecked ? 'checked' : '' ?>>

                                            <label for="addr-<?= $id ?>" class="hs-address-label">
                                                <div class="hs-address-box">
                                                    <i class="fas fa-check-circle hs-address-check"></i>

                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="hs-badge-type"><?= esc($type) ?></span>
                                                        <?php if ($isDefault): ?>
                                                            <span class="hs-badge-default">Default</span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="hs-address-name">
                                                        <?= esc($addr['name'] ?? 'Receiver') ?>
                                                    </div>

                                                    <p class="hs-address-text mb-2">
                                                        <?= esc($addr['address_line1']) ?>
                                                        <?php if (!empty($addr['address_line2'])): ?>
                                                            , <?= esc($addr['address_line2']) ?>
                                                        <?php endif; ?>,
                                                        <?= esc($addr['city']) ?> - <?= esc($addr['pincode']) ?>
                                                    </p>

                                                    <div class="hs-address-phone">
                                                        <i class="fas fa-phone-alt me-1" style="font-size:.8rem"></i>
                                                        <?= esc($addr['phone']) ?>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Order instructions -->
                    <div class="hs-card">
                        <div class="hs-card-header">
                            <h2 class="hs-card-title">
                                <span class="hs-card-icon bg-warning-subtle text-warning">
                                    <i class="fas fa-comment-dots"></i>
                                </span>
                                Order Instructions
                                <small class="text-muted fw-normal ms-1" style="font-size:.9rem;">
                                    (Optional)
                                </small>
                            </h2>
                        </div>
                        <div class="p-4">
                            <textarea
                                name="order_note"
                                class="form-control hs-notes-textarea"
                                placeholder="Any specific preferences, building landmark, or delivery notes?"></textarea>
                        </div>
                    </div>

                    <!-- Bottom payment strip -->
                    <div class="hs-payment-bar">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= site_url('cart') ?>"
                               class="d-none d-md-inline-flex hs-btn-back-circle"
                               title="Back to cart">
                                <i class="fas fa-arrow-left"></i>
                            </a>

                            <div>
                                <div class="hs-pay-total-label">Total Amount</div>
                                <div class="hs-pay-total-amount">
                                    <?= $currencySymbol . number_format($orderTotal, 2) ?>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="hs-btn-pay">
                            Pay Securely
                            <i class="fas fa-lock"></i>
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<!-- ADD ADDRESS MODAL (SEPARATE FORM, NO RAZORPAY HERE) -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="addAddressModalLabel">
            <i class="fas fa-location-dot me-1 text-success"></i>
            Add New Address
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- SEPARATE FORM: POSTS DIRECTLY TO /customer/addresses/store -->
      
      <form id="hs-add-address-form"
      action="<?= site_url('customer/addresses/store') ?>"
      method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="redirect_to" value="checkout">
      

        <div class="modal-body">
          <div class="row g-3">
            <!-- your existing fields as-is -->
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input type="text" name="name"
                     class="form-control form-control-sm"
                     placeholder="Receiver's full name" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Mobile Number</label>
              <input type="text" name="phone"
                     class="form-control form-control-sm"
                     placeholder="10-digit mobile" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Alternate Mobile (optional)</label>
              <input type="text" name="alternate_phone"
                     class="form-control form-control-sm">
            </div>

            <div class="col-12">
              <label class="form-label">Address Line 1</label>
              <input type="text" name="address_line1"
                     class="form-control form-control-sm"
                     placeholder="House / Flat / Building / Street" required>
            </div>

            <div class="col-12">
              <label class="form-label">Address Line 2 (optional)</label>
              <input type="text" name="address_line2"
                     class="form-control form-control-sm"
                     placeholder="Area / Locality">
            </div>

            <div class="col-md-6">
              <label class="form-label">Landmark (optional)</label>
              <input type="text" name="landmark"
                     class="form-control form-control-sm"
                     placeholder="Near temple, mall, etc.">
            </div>

            <div class="col-md-4">
              <label class="form-label">City</label>
              <input type="text" name="city"
                     class="form-control form-control-sm" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">State</label>
              <input type="text" name="state"
                     class="form-control form-control-sm" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">Pincode</label>
              <input type="text" name="pincode"
                     class="form-control form-control-sm" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Country</label>
              <input type="text" name="country"
                     class="form-control form-control-sm"
                     value="India">
            </div>

            <div class="col-md-6">
              <label class="form-label">Address Type</label>
              <select name="address_type"
                      class="form-select form-select-sm" required>
                <option value="home">Home</option>
                <option value="office">Office</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="col-12 mt-1">
              <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       name="is_default" id="hsAddIsDefault"
                       value="1" checked>
                <label class="form-check-label" for="hsAddIsDefault">
                  Make this my default delivery address
                </label>
              </div>
            </div>

            <!-- Optional: lat/long hidden -->
            <input type="hidden" name="latitude" id="hs-latitude">
            <input type="hidden" name="longitude" id="hs-longitude">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-sm btn-success px-4">
            Save Address
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    if (!checkoutForm) return;

    /* ---------- Helpers for main Pay button ---------- */
    function setBtnLoading(isLoading) {
        const btn = checkoutForm.querySelector('.hs-btn-pay');
        if (!btn) return;

        if (isLoading) {
            btn.dataset.originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        } else {
            btn.disabled = false;
            btn.innerHTML = btn.dataset.originalText || 'Pay Securely <i class="fas fa-lock"></i>';
        }
    }

    /* ---------- Main checkout submit & Razorpay ---------- */
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const selectedAddr = checkoutForm.querySelector('input[name="shipping_address_id"]:checked');
        if (!selectedAddr) {
            alert('Please select a delivery address.');
            document.querySelector('.hs-card')?.scrollIntoView({behavior: 'smooth'});
            return;
        }

        setBtnLoading(true);
        const formData = new FormData(checkoutForm);

        fetch("<?= site_url('checkout/place') ?>", {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (!data || !data.success) {
                throw new Error(data && data.message ? data.message : 'Could not initiate order.');
            }

            const options = {
                key: data.razorpay.key,
                amount: data.razorpay.amount,
                currency: data.razorpay.currency,
                name: "<?= esc(config('App')->siteName ?? 'Healthy Safar') ?>",
                description: "Order #" + data.orderNumber,
                order_id: data.razorpay.orderId,
                prefill: {
                    name:  data.customer && data.customer.name  ? data.customer.name  : "",
                    email: data.customer && data.customer.email ? data.customer.email : "",
                    contact: data.customer && data.customer.phone ? data.customer.phone : ""
                },
                theme: { color: "#10b981" },
                modal: {
                    ondismiss: function () {
                        handlePaymentFailure(data.orderId, 'Payment cancelled by user.');
                    }
                },
                handler: function (response) {
                    verifyPayment(response, data.orderId);
                }
            };

            const rzp = new Razorpay(options);

            rzp.on('payment.failed', function (response) {
                const desc = response && response.error && response.error.description
                    ? response.error.description
                    : 'Payment failed.';
                handlePaymentFailure(data.orderId, desc);
            });

            rzp.open();
        })
        .catch(err => {
            console.error(err);
            alert(err.message || 'Something went wrong. Please try again.');
            setBtnLoading(false);
        });
    });

    function verifyPayment(rzpResponse, localOrderId) {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = "<?= site_url('checkout/razorpay-success') ?>";

        const fields = {
            razorpay_payment_id: rzpResponse.razorpay_payment_id,
            razorpay_order_id:   rzpResponse.razorpay_order_id,
            razorpay_signature:  rzpResponse.razorpay_signature,
            local_order_id:      localOrderId,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        };

        for (const k in fields) {
            const i = document.createElement('input');
            i.type  = 'hidden';
            i.name  = k;
            i.value = fields[k];
            f.appendChild(i);
        }
        document.body.appendChild(f);
        f.submit();
    }

    function handlePaymentFailure(localOrderId, reason) {
        fetch("<?= site_url('checkout/razorpay-failed') ?>", {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
            body: new URLSearchParams({
                local_order_id: localOrderId,
                reason: reason,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        }).finally(() => {
            alert('Payment failed: ' + reason);
            window.location.href = "<?= site_url('checkout') ?>";
        });
    }
});
</script>

<?= $this->endSection() ?>