<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// ---------- Defensive defaults ----------
$plan             = $plan ?? [];
$nutrition        = $nutrition ?? [];
$configRow        = $configRow ?? [];
$durations        = $durations ?? [];
$slots            = $slots ?? [];
$offDays          = $offDays ?? [];
$durationPriceMap = $durationPriceMap ?? [];
$startingPrice    = $startingPrice ?? (float)($plan['base_price'] ?? 0);

$title       = $plan['title'] ?? 'Subscription Plan';
$shortDesc   = $plan['short_description'] ?? '';
$longDesc    = $plan['long_description'] ?? '';
$pricingType = $plan['pricing_type'] ?? 'per_day';

// Images (keep UI same; just make URLs safer)
$banner = !empty($plan['banner_url']) ? $plan['banner_url'] : 'assets/img/placeholder-banner.jpg';
$thumb  = !empty($plan['thumbnail_url']) ? $plan['thumbnail_url'] : 'assets/img/placeholder-product.png';

$toUrl = function ($path) {
    $p = (string)($path ?? '');
    if ($p === '') return '';
    if (preg_match('#^https?://#i', $p)) return $p; // already absolute
    return base_url($p);
};

$bannerUrl = $toUrl($banner);
$thumbUrl  = $toUrl($thumb);

// Earliest start
$minOffset      = (int)($configRow['min_start_offset_days'] ?? 1);
$minDateObject  = new DateTime("+{$minOffset} day");
$minDate        = $minDateObject->format('Y-m-d');
$minDateDisplay = $minDateObject->format('d M Y');

// Default duration: prefer 7 if available else first option else 7
$defaultDuration = 7;
if (!empty($durations)) {
    $durInts = array_map('intval', $durations);
    $defaultDuration = in_array(7, $durInts, true) ? 7 : (int)($durInts[0] ?? 7);
}

// Normalize offDays to 3-letter day codes (MON..SUN) so it matches JS + backend computeEndDate format('D')
$dayMap = [
    'MONDAY'=>'MON','MON'=>'MON',
    'TUESDAY'=>'TUE','TUE'=>'TUE','TUES'=>'TUE',
    'WEDNESDAY'=>'WED','WED'=>'WED',
    'THURSDAY'=>'THU','THU'=>'THU','THUR'=>'THU',
    'FRIDAY'=>'FRI','FRI'=>'FRI',
    'SATURDAY'=>'SAT','SAT'=>'SAT',
    'SUNDAY'=>'SUN','SUN'=>'SUN',
];
$normalizedOffDays = [];
foreach ((array)$offDays as $d) {
    $k = strtoupper(trim((string)$d));
    // handle numeric (0-6) if ever used (0=SUN in some systems)
    if (is_numeric($k)) {
        $num = (int)$k;
        $numMap = [0=>'SUN',1=>'MON',2=>'TUE',3=>'WED',4=>'THU',5=>'FRI',6=>'SAT'];
        $normalizedOffDays[] = $numMap[$num] ?? null;
        continue;
    }
    $normalizedOffDays[] = $dayMap[$k] ?? null;
}
$normalizedOffDays = array_values(array_unique(array_filter($normalizedOffDays)));
?>

<style>
    :root {
        --hs-green: #1f7a5b;
        --hs-green-soft: #e9f7f2;
        --hs-green-softer: #f4fbf8;
        --hs-amber: #fbbf24;
        --hs-border: #e5e7eb;
        --hs-muted: #6b7280;
        --hs-bg: #f3f4f6;
    }

    body {
        background-color: #f3f4f6;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .hs-plan-page {
        background:
            radial-gradient(circle at top left, #dcfce7 0, transparent 55%),
            radial-gradient(circle at bottom right, #fee2e2 0, transparent 55%),
            #f9fafb;
        padding-block: 32px 56px;
    }

    /* Breadcrumb */
    .hs-breadcrumb {
        font-size: .8rem;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .hs-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }
    .hs-breadcrumb a:hover {
        text-decoration: underline;
    }

    /* HERO CARD */
    .hs-plan-hero-card {
        background: #ffffff;
        border-radius: 26px;
        box-shadow: 0 20px 60px rgba(15,23,42,.13);
        border: 1px solid rgba(209,213,219,.7);
        overflow: hidden;
        position: relative;
    }
    .hs-plan-hero-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='grid' width='20' height='20' patternUnits='userSpaceOnUse'%3E%3Cpath d='M 20 0 L 0 0 0 20' fill='none' stroke='%23e5e7eb' stroke-width='0.4'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23grid)'/%3E%3C/svg%3E");
        opacity: .16;
        pointer-events: none;
    }
    .hs-plan-hero-inner {
        position: relative;
        z-index: 1;
        padding: 22px 24px 22px;
    }

    .hs-plan-badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: 10px;
    }
    .hs-plan-pill {
        display:inline-flex;
        align-items:center;
        gap:.35rem;
        padding:4px 10px;
        border-radius:999px;
        font-size:.78rem;
        font-weight:600;
        background:#ecfdf5;
        color:#065f46;
        border:1px solid #a7f3d0;
    }
    .hs-plan-pill.secondary {
        background:#eef2ff;
        color:#3730a3;
        border-color:#c7d2fe;
    }

    .hs-plan-title {
        font-weight: 800;
        letter-spacing: -.03em;
    }
    .hs-plan-sub {
        font-size: .95rem;
        color: var(--hs-muted);
        max-width: 580px;
    }

    .hs-plan-rating {
        display:flex;
        align-items:center;
        gap:.4rem;
        font-size:.85rem;
        margin-top:8px;
    }
    .hs-plan-rating i {
        color:#f59e0b;
    }
    .hs-plan-rating span {
        font-weight:600;
    }

    .hs-plan-meta-row {
        display:flex;
        flex-wrap:wrap;
        gap:.7rem;
        margin-top:16px;
        font-size:.85rem;
        color:#4b5563;
    }
    .hs-plan-meta-chip {
        display:flex;
        align-items:center;
        gap:.35rem;
        padding:6px 11px;
        border-radius:999px;
        background:#f9fafb;
        border:1px solid #e5e7eb;
    }
    .hs-plan-meta-chip i {
        font-size:.9rem;
        color:#059669;
    }

    /* HERO IMAGE – smaller but full (no crop) */
    .hs-plan-hero-image-wrap {
        position:relative;
        padding:8px;
        display:flex;
        justify-content:center;
        align-items:center;
    }
    .hs-plan-hero-image-inner {
        border-radius:22px;
        overflow:hidden;
        position:relative;
        box-shadow: 0 18px 40px rgba(15,23,42,.2);
        background:#fff;
        max-width: 420px;
        margin-left:auto;
        margin-right:auto;
    }
    .hs-plan-hero-image-inner img {
        width:100%;
        height:auto;        /* no cropping */
        max-height:320px;   /* lock image size */
        object-fit:contain; /* show full illustration */
        display:block;
    }
    .hs-calorie-tag {
        position:absolute;
        left:14px;
        bottom:14px;
        padding:6px 12px;
        border-radius:999px;
        background:rgba(255,255,255,.96);
        font-size:.8rem;
        display:flex;
        align-items:center;
        gap:.35rem;
        color:#111827;
        box-shadow:0 10px 25px rgba(15,23,42,.25);
    }
    .hs-calorie-tag i {
        color:#ef4444;
    }

    /* MAIN CONTENT */
    .hs-plan-main {
        margin-top: 26px;
    }

    .hs-section-card {
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        background:#ffffff;
        padding:18px 20px 20px;
        margin-bottom: 16px;
    }
    .hs-section-card h5 {
        font-size: 1rem;
        font-weight: 600;
    }

    /* PLAN SUMMARY – nicer UI */
    .hs-summary-card {
        background: radial-gradient(circle at top left, #e0f7ec 0, #f7fdf9 40%, #ffffff 80%);
        border: 1px solid rgba(209,213,219,.7);
    }
    .hs-summary-price {
        font-size: 2rem;
        font-weight: 800;
        color: #16a34a;
        line-height:1;
    }
    .hs-summary-bullets {
        list-style:none;
        padding-left:0;
        margin-bottom:0;
        font-size:.88rem;
        color:#4b5563;
    }
    .hs-summary-bullets li {
        display:flex;
        align-items:flex-start;
        gap:.35rem;
        margin-bottom:.25rem;
    }
    .hs-summary-bullets i {
        color:#16a34a;
        margin-top:2px;
    }

    /* Nutritional grid – 7 boxes (4 + 3) */
    .hs-macro-grid{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:12px;
        margin-top:10px;
    }
    .hs-macro-card{
        border-radius:16px;
        border:1px solid #e5e7eb;
        padding:16px 12px;
        background:#fff;
        text-align:center;
    }
    .hs-macro-value{
        font-size:1.4rem;
        font-weight:800;
        color:#111827;
        line-height:1;
        margin-bottom:4px;
    }
    .hs-macro-unit{
        font-size:.9rem;
        color:#4b5563;
    }
    .hs-macro-label{
        font-size:.78rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#6b7280;
        margin-top:2px;
    }

    @media (max-width: 991.98px){
        .hs-macro-grid{
            grid-template-columns:repeat(3,minmax(0,1fr));
        }
    }
    @media (max-width: 575.98px){
        .hs-macro-grid{
            grid-template-columns:repeat(2,minmax(0,1fr));
        }
    }

    .hs-benefit-list {
        padding-left:1.2rem;
    }
    .hs-benefit-list li {
        margin-bottom:.25rem;
        color:var(--hs-muted);
        font-size:.92rem;
    }

    /* CONFIG CARD (RIGHT) */
    .hs-config-card{
        border-radius:22px;
        border:1px solid #e5e7eb;
        padding:18px 18px 20px;
        background:#f9fafb;
        position:sticky;
        top:96px;
        box-shadow:0 18px 40px rgba(15,23,42,.08);
    }
    .hs-config-header {
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:.75rem;
        margin-bottom:10px;
    }
    .hs-config-price {
        text-align:right;
    }
    .hs-config-price-label {
        font-size:.78rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#6b7280;
        font-weight:600;
    }
    .hs-config-price-value {
        font-size:1.6rem;
        font-weight:800;
        color:#16a34a;
        line-height:1;
    }
    .hs-config-price-sub {
        font-size:.8rem;
        color:#6b7280;
    }
    .hs-config-hint {
        font-size:.78rem;
        color:#6b7280;
        margin-bottom:8px;
    }
    .hs-config-label {
        font-size:.85rem;
        font-weight:600;
        margin-bottom:4px;
    }
    .hs-config-small {
        font-size:.78rem;
        color:#6b7280;
    }

    /* Duration options – lighter, professional */
    .hs-duration-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:8px;
    }
    .hs-duration-wrap{ position:relative; }
    .hs-duration-input{
        position:absolute;
        inset:0;
        opacity:0;
        cursor:pointer;
    }
    .hs-duration-card{
        border-radius:14px;
        border:1px solid #d1d5db;
        background:#ffffff;
        padding:10px 12px;
        text-align:left;
        transition:all .18s ease-out;
    }
    .hs-duration-title{
        font-size:.96rem;
        font-weight:600;
        color:#111827;
        margin-bottom:2px;
    }
    .hs-duration-price{
        font-size:.78rem;
        color:#6b7280;
    }
    .hs-duration-wrap:hover .hs-duration-card{
        border-color:#22c55e;
        background:#f0fdf4;
    }
    .hs-duration-input:checked + .hs-duration-card{
        border-color:#16a34a;
        background:#e9f7f2;
        box-shadow:0 0 0 1px rgba(34,197,94,.4);
    }

    .form-control,
    .form-select{
        border-radius:10px;
        border:1px solid #d1d5db;
        font-size:.9rem;
    }
    .form-control:focus,
    .form-select:focus{
        border-color:#22c55e;
        box-shadow:0 0 0 3px rgba(34,197,94,.2);
    }

    .hs-config-btn {
        border-radius:999px;
        font-weight:600;
        padding:10px 16px;
    }

    .hs-trust-row {
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:.75rem;
        margin-top:10px;
        flex-wrap:wrap;
    }
    .hs-trust-row span {
        font-size:.75rem;
        color:#6b7280;
    }

    @media (max-width: 991.98px) {
        .hs-config-card {
            position:static;
            box-shadow:none;
            margin-top:6px;
        }
    }
</style>

<section class="hs-plan-page">
    <div class="container">

        <!-- Breadcrumb -->
        <div class="hs-breadcrumb">
            <a href="<?= site_url('/') ?>">Home</a> /
            <a href="<?= site_url('subscriptions') ?>">Subscriptions</a> /
            <span><?= esc($title) ?></span>
        </div>

        <!-- HERO CARD -->
        <div class="hs-plan-hero-card mb-3">
            <div class="hs-plan-hero-inner">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="hs-plan-badge-row">
                            <span class="hs-plan-pill">
                                <i class="bi bi-basket3-fill"></i>
                                HealthySafar Subscription
                            </span>
                            <span class="hs-plan-pill secondary">
                                <i class="bi bi-shield-check"></i>
                                Chef &amp; Nutritionist Approved
                            </span>
                        </div>

                        <h1 class="hs-plan-title h2 mb-2">
                            <?= esc($title) ?>
                        </h1>

                        <?php if (!empty($shortDesc)): ?>
                            <p class="hs-plan-sub mb-2">
                                <?= esc($shortDesc) ?>
                            </p>
                        <?php else: ?>
                            <p class="hs-plan-sub mb-2">
                                Fresh, portion-controlled meals designed to support your health goals
                                without compromising on taste.
                            </p>
                        <?php endif; ?>

                        <div class="hs-plan-rating">
                            <i class="bi bi-star-fill"></i>
                            <span>4.9</span>
                            <span class="text-muted">· Loved by HealthySafar members</span>
                        </div>

                        <div class="hs-plan-meta-row">
                            <div class="hs-plan-meta-chip">
                                <i class="bi bi-calendar2-week"></i>
                                Flexible durations (<?= !empty($durations) ? implode(', ', array_map('intval', $durations)) : 'custom' ?> days)
                            </div>
                            <div class="hs-plan-meta-chip">
                                <i class="bi bi-clock-history"></i>
                                Lunch &amp;/or Dinner delivery slots
                            </div>
                            <div class="hs-plan-meta-chip">
                                <i class="bi bi-truck"></i>
                                Fresh delivery on your schedule
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="hs-plan-hero-image-wrap">
                            <div class="hs-plan-hero-image-inner">
                                <img src="<?= esc($bannerUrl) ?>"
                                     alt="<?= esc($title) ?>"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='<?= esc(base_url('assets/img/placeholder-banner.jpg')) ?>';">
                                <?php if (!empty($nutrition['calories_kcal'])): ?>
                                    <div class="hs-calorie-tag">
                                        <i class="bi bi-fire"></i>
                                        ~<?= (int)$nutrition['calories_kcal'] ?> kcal / meal
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="hs-plan-main">
            <div class="row g-4">
                <!-- LEFT: DETAILS -->
                <div class="col-lg-7">
                    <!-- Plan summary -->
                    <div class="hs-section-card hs-summary-card">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <h5 class="mb-1">Plan summary</h5>

                                <div class="text-muted" style="font-size:.8rem;">Starting from</div>
                                <div class="d-flex align-items-end gap-1">
                                    <div class="hs-summary-price">
                                        ₹<?= number_format($startingPrice, 2) ?>
                                    </div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        / selected duration
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <!-- (kept empty as you had) -->
                            </div>
                        </div>
                    </div>

                    <!-- Nutritional breakdown -->
                    <?php if (!empty($nutrition)): ?>
                        <div class="hs-section-card">
                            <h5 class="mb-2">Nutritional snapshot (approx per meal)</h5>

                            <div class="hs-macro-grid">
                                <div class="hs-macro-card">
                                    <div class="hs-macro-value"><?= (int)($nutrition['calories_kcal'] ?? 0) ?></div>
                                    <div class="hs-macro-unit">kcal</div>
                                    <div class="hs-macro-label">Calories</div>
                                </div>
                                <div class="hs-macro-card">
                                    <div class="hs-macro-value"><?= (float)($nutrition['protein_g'] ?? 0) ?>g</div>
                                    <div class="hs-macro-unit">per meal</div>
                                    <div class="hs-macro-label">Protein</div>
                                </div>
                                <div class="hs-macro-card">
                                    <div class="hs-macro-value"><?= (float)($nutrition['carbs_g'] ?? 0) ?>g</div>
                                    <div class="hs-macro-unit">per meal</div>
                                    <div class="hs-macro-label">Carbs</div>
                                </div>
                                <div class="hs-macro-card">
                                    <div class="hs-macro-value"><?= (float)($nutrition['fats_g'] ?? 0) ?>g</div>
                                    <div class="hs-macro-unit">per meal</div>
                                    <div class="hs-macro-label">Fats</div>
                                </div>

                                <div class="hs-macro-card">
                                    <div class="hs-macro-value"><?= isset($nutrition['fibre_g']) ? (float)$nutrition['fibre_g'] : 0 ?>g</div>
                                    <div class="hs-macro-unit">per meal</div>
                                    <div class="hs-macro-label">Fibre</div>
                                </div>
                                <div class="hs-macro-card">
                                    <div class="hs-macro-value"><?= isset($nutrition['sugar_g']) ? (float)$nutrition['sugar_g'] : 0 ?>g</div>
                                    <div class="hs-macro-unit">per meal</div>
                                    <div class="hs-macro-label">Sugar</div>
                                </div>
                                <div class="hs-macro-card">
                                    <div class="hs-macro-value"><?= isset($nutrition['sodium_mg']) ? (int)$nutrition['sodium_mg'] : 0 ?></div>
                                    <div class="hs-macro-unit">mg per meal</div>
                                    <div class="hs-macro-label">Sodium</div>
                                </div>
                            </div>

                            <?php if (!empty($nutrition['notes'])): ?>
                                <p class="mt-2 mb-0 text-muted small">
                                    <?= nl2br(esc($nutrition['notes'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Long description -->
                    <?php if (!empty($longDesc)): ?>
                        <div class="hs-section-card">
                            <h5 class="mb-2">What you can expect</h5>
                            <div class="text-muted" style="font-size:.93rem;">
                                <?= nl2br(esc($longDesc)) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Benefits -->
                    <div class="hs-section-card mb-0">
                        <h5 class="mb-2">Why members choose this plan</h5>
                        <ul class="hs-benefit-list mb-1">
                            <li>Helps you maintain a consistent healthy eating routine.</li>
                            <li>Saves time for busy professionals, students and families.</li>
                            <li>Portion-controlled meals aligned with common health goals.</li>
                            <li>Delivered fresh with flexible time slots to suit your day.</li>
                        </ul>
                        <p class="text-muted small mb-0">
                            Note: Actual menu may vary by day to keep your meals seasonal and interesting.
                        </p>
                    </div>
                </div>

                <!-- RIGHT: CONFIG / ADD TO CART -->
                <div class="col-lg-5" id="hs-config">
                    <div class="hs-config-card">
                        <div class="hs-config-header">
                            <div>
                                <h5 class="mb-1">Configure your subscription</h5>
                                <div class="hs-config-hint">
                                    Select your duration, start date and delivery slot to begin.
                                </div>
                            </div>
                            <div class="hs-config-price">
                                <div class="hs-config-price-label">Estimated from</div>
                                <div class="hs-config-price-value" id="sub_price_preview">
                                    ₹<?= number_format($startingPrice, 2) ?>
                                </div>
                                <div class="hs-config-price-sub">
                                    Depends on chosen duration
                                </div>
                            </div>
                        </div>

                        <form method="post" action="<?= site_url('subscriptions/add-to-cart') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="plan_id" value="<?= (int)($plan['id'] ?? 0) ?>">

                            <!-- Duration -->
                            <div class="mb-3">
                                <label class="hs-config-label">Duration</label>
                                <div class="hs-config-small mb-1">
                                    Longer durations usually give better value per meal.
                                </div>

                                <?php if (!empty($durations)): ?>
                                    <div class="hs-duration-grid">
                                        <?php foreach ($durations as $duration): ?>
                                            <?php
                                                $d = (int)$duration;
                                                $price = null;

                                                if (isset($durationPriceMap[$d])) {
                                                    $price = (float)$durationPriceMap[$d];
                                                } elseif ($pricingType === 'per_day') {
                                                    $price = $d * (float)($plan['base_price'] ?? 0);
                                                }
                                            ?>
                                            <label class="hs-duration-wrap">
                                                <input
                                                    type="radio"
                                                    class="hs-duration-input"
                                                    name="duration_days"
                                                    value="<?= $d ?>"
                                                    <?= ($d === $defaultDuration) ? 'checked' : '' ?>
                                                    required
                                                >
                                                <div class="hs-duration-card">
                                                    <div class="hs-duration-title"><?= $d ?> days</div>
                                                    <?php if (!is_null($price)): ?>
                                                        <div class="hs-duration-price">
                                                            ₹<?= number_format($price, 2) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <input type="number" name="duration_days" class="form-control" min="1" value="7" required>
                                    <small class="hs-config-small d-block mt-1">Enter number of days.</small>
                                <?php endif; ?>
                            </div>

                            <!-- Start date -->
                            <div class="mb-3">
                                <label class="hs-config-label">Start date</label>
                                <input
                                    type="date"
                                    id="sub_start_date"
                                    name="start_date"
                                    class="form-control"
                                    min="<?= esc($minDate) ?>"
                                    required
                                >
                                <small class="hs-config-small d-block mt-1">
                                    Earliest available start: <?= esc($minDateDisplay) ?>.
                                </small>
                            </div>

                            <!-- Time slot -->
                            <div class="mb-3">
                                <label class="hs-config-label">Preferred time slot</label>
                                <?php if (!empty($slots)): ?>
                                    <select name="slot_key" class="form-select" required>
                                        <option value="">Select time slot</option>
                                        <?php foreach ($slots as $slot): ?>
                                            <option value="<?= esc($slot['key']) ?>">
                                                <?= esc($slot['label']) ?>
                                                <?php if (!empty($slot['window'])): ?>
                                                    (<?= esc($slot['window']) ?>)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <select name="slot_key" class="form-select" required>
                                        <option value="default">Standard delivery window</option>
                                    </select>
                                <?php endif; ?>
                                <small class="hs-config-small d-block mt-1">
                                    We’ll do our best to deliver within the selected slot.
                                </small>
                            </div>

                            <!-- Calculated end date -->
                            <div class="mb-3">
                                <label class="hs-config-label">Estimated end date</label>
                                <div class="form-control bg-light" id="sub_end_date_display" style="font-size:.85rem;">
                                    Select duration &amp; start date
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-success w-100 hs-config-btn">
                                    Add to Cart &amp; Continue
                                </button>
                            </div>

                            <div class="hs-trust-row">
                                <span>
                                    <i class="bi bi-shield-lock me-1"></i>
                                    Secure checkout with encrypted payment gateway.
                                </span>
                                <span>
                                    <i class="bi bi-patch-check-fill text-success me-1"></i>
                                    Transparent pricing, no hidden charges.
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const offDays     = <?= json_encode(array_values($normalizedOffDays)) ?>; // normalized to MON..SUN
    const pricing     = <?= json_encode($durationPriceMap) ?>;
    const basePrice   = <?= (float)($plan['base_price'] ?? 0) ?>;
    const pricingType = "<?= esc($pricingType) ?>";

    const durationInputs   = document.querySelectorAll('input[name="duration_days"]');
    const durationNumInput = document.querySelector('input[type="number"][name="duration_days"]');
    const startInput       = document.getElementById('sub_start_date');
    const endDisplay       = document.getElementById('sub_end_date_display');
    const pricePreview     = document.getElementById('sub_price_preview');

    function getSelectedDuration() {
        let duration = <?= (int)$defaultDuration ?>;
        const selectedRadio = document.querySelector('input[name="duration_days"]:checked');

        if (selectedRadio) {
            duration = parseInt(selectedRadio.value, 10);
        } else if (durationNumInput) {
            duration = parseInt(durationNumInput.value, 10);
            if (isNaN(duration) || duration < 1) duration = 1;
        }

        if (isNaN(duration) || duration < 1) duration = 1;
        return duration;
    }

    function calculate() {
        const duration = getSelectedDuration();

        // Price
        let total = 0;
        if (Object.prototype.hasOwnProperty.call(pricing, duration)) {
            total = parseFloat(pricing[duration]);
        } else if (pricingType === 'per_day') {
            total = duration * basePrice;
        } else {
            total = basePrice;
        }

        if (pricePreview && !isNaN(total)) {
            pricePreview.textContent = '₹' + total.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // End date
        if (!startInput || !startInput.value) {
            if (endDisplay) endDisplay.textContent = 'Select duration & start date';
            return;
        }

        const start = new Date(startInput.value + 'T00:00:00');
        if (isNaN(start.getTime())) {
            if (endDisplay) endDisplay.textContent = 'Invalid start date';
            return;
        }

        const offSet = (offDays || []).map(d => String(d).toUpperCase().trim());
        let daysAdded = 0;
        const date = new Date(start.getTime());

        while (daysAdded < duration - 1) {
            date.setDate(date.getDate() + 1);
            const dayCode = date.toLocaleDateString('en-US', { weekday: 'short' }).toUpperCase(); // MON..SUN
            if (!offSet.includes(dayCode)) {
                daysAdded++;
            }
        }

        if (endDisplay) {
            endDisplay.textContent = date.toLocaleDateString('en-IN', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
        }
    }

    durationInputs.forEach(r => r.addEventListener('change', calculate));
    if (durationNumInput) {
        durationNumInput.addEventListener('input', calculate);
        durationNumInput.addEventListener('change', calculate);
    }
    if (startInput) startInput.addEventListener('change', calculate);

    calculate();
})();
</script>

<?= $this->endSection() ?>