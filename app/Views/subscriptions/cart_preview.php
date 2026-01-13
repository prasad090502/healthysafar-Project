<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h3 class="mb-3">Subscription Cart Preview</h3>

    <?php if (session('success')): ?>
        <div class="alert alert-success"><?= esc(session('success')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
        <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <p class="text-muted">No subscription items added yet.</p>
    <?php else: ?>
        <div class="table-responsive mb-3">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Duration</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Slot</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it): ?>
                        <tr>
                            <td><?= esc($it['title']) ?></td>
                            <td><?= (int)$it['duration_days'] ?> days</td>
                            <td><?= esc($it['start_date']) ?></td>
                            <td><?= esc($it['end_date']) ?></td>
                            <td><?= esc($it['slot_label']) ?></td>
                            <td>₹<?= number_format((float)$it['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form method="post" action="<?= site_url('subscriptions/confirm-cart') ?>">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-success">
                Confirm &amp; Go to Cart
            </button>
            <a href="<?= site_url('subscriptions') ?>" class="btn btn-outline-secondary ms-2">
                Add more plans
            </a>
        </form>

        <p class="text-muted small mt-3 mb-0">
            After confirming, subscription plans will appear in your main cart along with other products.
        </p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>