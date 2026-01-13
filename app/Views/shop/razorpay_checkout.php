<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <p class="mb-2 fw-semibold">
                Redirecting you to secure payment...
            </p>
            <p class="text-muted small mb-3">
                Order #<?= esc($orderNumber) ?> ·
                Amount: <?= esc($currency) ?> <?= number_format($razorpayAmount / 100, 2) ?>
            </p>

            <!-- Hidden fallback button (in case popup blocked) -->
            <button id="rzp-button1" class="btn btn-success btn-sm d-none">
                Pay Now
            </button>

            <p class="small text-muted">
                If the payment window does not open automatically,
                <a href="#" id="rzp-manual-link">click here</a>.
            </p>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(function(){
    var options = {
        "key": "<?= esc($razorpayKey) ?>",
        "amount": "<?= esc($razorpayAmount) ?>", // in paise
        "currency": "<?= esc($currency) ?>",
        "name": "Healthy Safar",
        "description": "Order #<?= esc($orderNumber) ?>",
        "order_id": "<?= esc($razorpayOrderId) ?>",
        "prefill": {
            "name": "<?= esc($customer['name'] ?? '') ?>",
            "email": "<?= esc($customer['email'] ?? '') ?>",
            "contact": "<?= esc($customer['contact'] ?? '') ?>"
        },
        "theme": {
            "color": "#22c55e"
        },
        "handler": function (response){
            // On success, POST to CI to verify & update order
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = "<?= site_url('checkout/razorpay-success') ?>";

            var fields = {
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id:   response.razorpay_order_id,
                razorpay_signature:  response.razorpay_signature,
                local_order_id:      "<?= (int)$orderId ?>",
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            };

            for (var key in fields) {
                if (!fields.hasOwnProperty(key)) continue;
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }
    };

    var rzp1 = new Razorpay(options);

    rzp1.on('payment.failed', function (response){
        alert("Payment failed: " + response.error.description);
        // Optional: redirect back
        // window.location.href = "<?= site_url('customer/orders') ?>";
    });

    function openRazorpay(e) {
        if (e) e.preventDefault();
        rzp1.open();
        return false;
    }

    document.getElementById('rzp-button1').onclick   = openRazorpay;
    document.getElementById('rzp-manual-link').onclick = openRazorpay;

    // Auto-open on page load
    document.addEventListener('DOMContentLoaded', function () {
        openRazorpay();
    });
})();
</script>

<?= $this->endSection() ?>