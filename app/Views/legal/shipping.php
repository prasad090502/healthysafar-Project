<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="space-top space-bottom">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <h1 class="mb-3">Shipping &amp; Delivery Policy</h1>
        <p class="text-muted mb-4">
          Last updated: <?= date('d F Y') ?>
        </p>

        <p>
          At <strong>HealthySafar</strong>, we aim to deliver fresh salads, juices and wellness food to you
          in a timely and safe manner. This Shipping &amp; Delivery Policy explains how and when we deliver your orders.
        </p>

        <hr class="my-4">

        <h2 class="h4 mt-4">1. Delivery Areas</h2>
        <p>We currently deliver within selected areas of:</p>
        <ul>
          <li><strong>Chhatrapati Sambhajinagar (Aurangabad)</strong> and nearby localities.</li>
        </ul>
        <p>
          Our exact serviceable locations may depend on pincode, locality and operational feasibility.
          The Platform will generally indicate if your address is serviceable. We may update our delivery
          coverage from time to time.
        </p>

        <h2 class="h4 mt-4">2. Delivery Window</h2>
        <p>
          HealthySafar delivers orders every day within the following standard delivery window:
        </p>
        <p class="fw-semibold">
          <strong>⏰ Delivery Time: 6:00 AM – 10:00 PM</strong>
        </p>
        <p>
          Your order may be delivered at any time within this window depending on:
        </p>
        <ul>
          <li>Your location;</li>
          <li>Order volume and kitchen load;</li>
          <li>Delivery partner availability.</li>
        </ul>

        <h2 class="h4 mt-4">3. Same-Day &amp; Next-Day Delivery</h2>
        <ul>
          <li>Orders placed before the defined cut-off time (for example, 8:00 PM) may be eligible for same-day delivery,
              subject to slot availability.</li>
          <li>Orders placed after the cut-off time may be delivered on the next day or the next available slot.</li>
          <li>Cut-off times and slot availability may change during peak seasons, weekends or festivals.</li>
        </ul>

        <h2 class="h4 mt-4">4. Subscription Deliveries</h2>
        <p>
          For Subscriptions / Memberships that include scheduled deliveries (such as daily or weekly salads/juices):
        </p>
        <ul>
          <li>Deliveries will follow the schedule/plan selected by you;</li>
          <li>Specific time slots may be assigned or indicated in your subscription details;</li>
          <li>You are responsible for ensuring someone is available to receive deliveries at the scheduled times.</li>
        </ul>

        <h2 class="h4 mt-4">5. Delivery Charges</h2>
        <ul>
          <li>Any delivery fee or minimum order value requirement will be shown at checkout before you complete the order.</li>
          <li>From time to time, we may offer free delivery or discounted delivery as part of promotions or subscription plans.</li>
        </ul>

        <h2 class="h4 mt-4">6. Customer Responsibility at Delivery</h2>
        <p>To ensure smooth delivery, you must:</p>
        <ul>
          <li>Provide accurate and complete delivery address and contact details;</li>
          <li>Keep your phone reachable at the time of delivery;</li>
          <li>Ensure that you or an authorised person is available to accept the order.</li>
        </ul>
        <p>
          If delivery fails due to incorrect/incomplete address, unreachable phone number, or unavailability of
          anyone to receive the order, the delivery may be marked as <strong>Delivered</strong>.
          In such cases, no refund, replacement or re-delivery will be provided due to the perishable nature of the food.
        </p>

        <h2 class="h4 mt-4">7. Packaging &amp; Product Handling</h2>
        <ul>
          <li>All items are packed in clean, food-grade containers and bags.</li>
          <li>We follow hygienic preparation and handling processes in our kitchen.</li>
          <li>Once delivered, you are responsible for proper storage (e.g., refrigeration where needed) and timely consumption.</li>
        </ul>
        <p>
          HealthySafar is not responsible for spoilage or quality issues that arise due to delay in consumption
          or improper storage after delivery.
        </p>

        <h2 class="h4 mt-4">8. Delays &amp; Force Majeure</h2>
        <p>
          While we try our best to deliver within the estimated time, delays may occur because of:
        </p>
        <ul>
          <li>Traffic conditions or road closures;</li>
          <li>Adverse weather (rain, fog, extreme heat, etc.);</li>
          <li>Public events, security restrictions, strikes or bandhs;</li>
          <li>Technical issues or sudden spikes in order volume;</li>
          <li>Any force majeure events beyond our reasonable control.</li>
        </ul>
        <p>
          Such delays, when arising from reasons beyond our control, will not be treated as service deficiency and will
          not be eligible for refunds under our <a href="<?= site_url('refund-policy') ?>">Refund Policy</a>.
        </p>

        <h2 class="h4 mt-4">9. Change or Rescheduling of Delivery</h2>
        <p>
          Once the order has been accepted and moved to preparation, change of address, time or date may not be possible.
          In limited cases and subject to feasibility, we may try to accommodate minor changes, but this is not guaranteed.
        </p>

        <h2 class="h4 mt-4">10. Contact for Delivery Support</h2>
        <p>If you face any issue related to delivery, you can reach us at:</p>
        <p>
          Email: <a href="mailto:support@healthysafar.com">support@healthysafar.com</a><br>
          Phone/WhatsApp: 9853298534<br>
          Delivery Support Timings: <strong>6:00 AM – 10:00 PM</strong>
        </p>

      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>