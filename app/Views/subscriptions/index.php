<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$plans = $plans ?? [];
?>

<style>
    :root {
        --hs-green: #1f7a5b;
        --hs-green-soft: #e9f7f2;
        --hs-amber: #fbbf24;
        --hs-surface: #ffffff;
        --hs-border: #e5e7eb;
        --hs-muted: #6b7280;
    }

    .hs-sub-hero {
        position: relative;
        padding: 70px 0 40px;
        background: radial-gradient(circle at top left, #d1fae5 0, transparent 55%),
                    radial-gradient(circle at top right, #fee2e2 0, transparent 55%),
                    #f9fafb;
        overflow: hidden;
    }
    .hs-sub-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='grid' width='20' height='20' patternUnits='userSpaceOnUse'%3E%3Cpath d='M 20 0 L 0 0 0 20' fill='none' stroke='%23e5e7eb' stroke-width='0.6'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23grid)'/%3E%3C/svg%3E");
        opacity: 0.22;
        pointer-events: none;
    }
    .hs-sub-hero-inner { position: relative; z-index: 1; }
    .hs-hero-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .8rem; font-weight: 700;
        color: #065f46; background: #ecfdf5;
        border-radius: 999px; padding: 6px 14px;
        border: 1px solid #a7f3d0;
    }
    .hs-hero-title { font-weight: 900; letter-spacing: -.03em; }
    .hs-hero-sub { max-width: 680px; }

    .hs-controls {
        margin-top: 18px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }
    .hs-search {
        flex: 1;
        min-width: 260px;
        max-width: 520px;
        position: relative;
    }
    .hs-search input {
        padding-left: 40px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: rgba(255,255,255,.9);
    }
    .hs-search i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
    }
    .hs-sort select {
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: rgba(255,255,255,.9);
        min-width: 220px;
    }

    .hs-filter-bar { margin-top: 14px; }
    .hs-filter-pill {
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: .8rem;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        cursor: pointer;
        transition: all .15s ease;
    }
    .hs-filter-pill.active,
    .hs-filter-pill:hover {
        background: #ecfdf5;
        border-color: #6ee7b7;
        color: #047857;
    }

    .hs-sub-grid { padding-block: 30px 60px; }

    .subscription-card {
        height: 100%;
        background: var(--hs-surface);
        border-radius: 22px;
        border: 1px solid var(--hs-border);
        box-shadow: 0 18px 45px rgba(15,23,42,.07);
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        position: relative;
    }
    .subscription-card:hover {
        transform: translateY(-4px);
        border-color: #a7f3d0;
        box-shadow: 0 22px 60px rgba(15,23,42,.12);
    }

    .card-img-wrapper { position: relative; overflow: hidden; max-height: 210px; }
    .card-img-wrapper img {
        width: 100%; height: 210px; object-fit: cover;
        transition: transform .25s ease;
    }
    .subscription-card:hover .card-img-wrapper img { transform: scale(1.04); }

    .card-badge {
        position: absolute; top: 14px; left: 14px;
        padding: 4px 12px; border-radius: 999px;
        font-size: .7rem; font-weight: 800;
        letter-spacing: .06em; text-transform: uppercase;
        background: rgba(15,118,110,.95); color: #ecfdf5;
        box-shadow: 0 10px 20px rgba(15,23,42,.45);
    }

    .card-pill-right {
        position: absolute; right: 14px; bottom: 14px;
        padding: 4px 10px; border-radius: 999px;
        font-size: .75rem;
        background: rgba(255,255,255,.9);
        color: #374151;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        backdrop-filter: blur(8px);
    }

    .subscription-card .card-body { padding: 18px 18px 12px; }
    .plan-title { font-weight: 800; font-size: 1.05rem; }
    .plan-desc { font-size: .9rem; color: var(--hs-muted); }

    .feature-strip {
        border-radius: 14px;
        padding: 8px 10px;
        background: #f9fafb;
        border: 1px dashed #e5e7eb;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .feature-item {
        display: flex; align-items: center; gap: .35rem;
        font-size: .8rem; color: #4b5563;
        white-space: nowrap;
    }

    .card-footer-custom {
        padding: 12px 18px 16px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .price-label {
        font-size: .75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .08em;
    }
    .price-value { font-size: 1.35rem; font-weight: 900; line-height: 1; }

    .btn-configure {
        border-radius: 999px;
        padding-inline: 18px;
        font-size: .85rem;
        font-weight: 700;
        background: var(--hs-green);
        border-color: var(--hs-green);
        color: #ecfdf5;
        white-space: nowrap;
    }
    .btn-configure:hover { background: #166148; border-color: #166148; color: #ecfdf5; }

    .btn-outline-soft {
        border-radius: 999px;
        padding-inline: 16px;
        font-size: .8rem;
        border-color: #d1d5db;
        color: #374151;
        background: #ffffff;
        white-space: nowrap;
    }
    .btn-outline-soft:hover {
        background: #f3f4ff;
        border-color: #a5b4fc;
        color: #1f2937;
    }
</style>

<section class="hs-sub-hero">
    <div class="container hs-sub-hero-inner">
        <div class="row align-items-center g-4 justify-content-between">
            <div class="col-lg-7 text-center text-md-start">
                <div class="mb-3">
                    <span class="hs-hero-badge">
                        <i class="bi bi-heart-pulse-fill"></i>
                        HealthySafar Meal Subscriptions
                    </span>
                </div>
                <h1 class="hs-hero-title display-5 mb-3 text-dark">
                    Subscription Plans for a <span style="color:var(--hs-green);">Healthier You</span>
                </h1>
                <p class="text-muted hs-hero-sub mb-0">
                    Fresh salads, juices and wholesome meals — delivered on your schedule.
                    Pick a plan, choose duration and start date, and proceed to checkout.
                </p>

                <div class="hs-controls">
                    <div class="hs-search">
                        <i class="bi bi-search"></i>
                        <input id="hsSearch" type="text" class="form-control" placeholder="Search plans (keto, salad, juice...)">
                    </div>
                    <div class="hs-sort">
                        <select id="hsSort" class="form-select">
                            <option value="featured">Sort: Featured</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name_az">Name: A to Z</option>
                        </select>
                    </div>
                </div>

                <div class="hs-filter-bar">
                    <div class="d-inline-flex flex-wrap gap-2">
                        <button type="button" class="hs-filter-pill active" data-filter="all">
                            <i class="bi bi-stars"></i> All Plans
                        </button>
                        <button type="button" class="hs-filter-pill" data-filter="weight-loss">
                            <i class="bi bi-fire"></i> Weight Loss
                        </button>
                        <button type="button" class="hs-filter-pill" data-filter="high-protein">
                            <i class="bi bi-lightning-charge"></i> High Protein
                        </button>
                        <button type="button" class="hs-filter-pill" data-filter="detox">
                            <i class="bi bi-droplet"></i> Detox / Juices
                        </button>
                    </div>
                </div>

                <div class="text-muted mt-2 small">
                    Showing <span id="hsCount"><?= count($plans) ?></span> plans
                </div>
            </div>

            <div class="col-lg-4 d-none d-lg-block">
                <div class="rounded-4 shadow-sm p-3 bg-white border border-success-subtle">
                    <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=900&auto=format&fit=crop"
                         alt="Healthy meal"
                         class="img-fluid rounded-4" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="hs-sub-grid">
    <div class="container">
        <div class="row g-4 justify-content-center" id="hsGrid">

            <?php if (empty($plans)): ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-light rounded-5">
                        <i class="bi bi-basket3 display-1 text-muted opacity-25"></i>
                        <h3 class="mt-4 text-muted fw-bold">No Plans Available</h3>
                        <p class="text-muted mb-2">Please check back soon.</p>
                    </div>
                </div>
            <?php else: ?>

                <?php foreach ($plans as $plan): ?>
                    <?php
                        $placeholder = 'https://placehold.co/800x600/e5e7eb/a3a3a3?text=Healthy+Food';
                        $titleLower  = strtolower((string)($plan['title'] ?? ''));

                        if (str_contains($titleLower, 'keto')) {
                            $placeholder = 'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=800&auto=format&fit=crop';
                        } elseif (str_contains($titleLower, 'salad')) {
                            $placeholder = 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop';
                        } elseif (str_contains($titleLower, 'juice') || str_contains($titleLower, 'detox')) {
                            $placeholder = 'https://images.unsplash.com/photo-1600271886742-44726e70e453?q=80&w=800&auto=format&fit=crop';
                        }

                        $imageUrl = !empty($plan['thumbnail_url'])
                            ? base_url($plan['thumbnail_url'])
                            : $placeholder;

                        // Filter category from title keywords
                        $categoryTag = 'balanced';
                        if (str_contains($titleLower, 'weight') || str_contains($titleLower, 'loss')) {
                            $categoryTag = 'weight-loss';
                        } elseif (str_contains($titleLower, 'keto') || str_contains($titleLower, 'protein') || str_contains($titleLower, 'muscle')) {
                            $categoryTag = 'high-protein';
                        } elseif (str_contains($titleLower, 'juice') || str_contains($titleLower, 'detox') || str_contains($titleLower, 'cleanse')) {
                            $categoryTag = 'detox';
                        }

                        $badgeText = match ($categoryTag) {
                            'weight-loss'  => 'Weight Loss',
                            'high-protein' => 'High Protein',
                            'detox'        => 'Detox / Juices',
                            default        => 'Balanced Plan',
                        };

                        $short = !empty($plan['short_description'])
                            ? (string)$plan['short_description']
                            : 'Chef-prepared meals crafted to support your wellness goals.';
                        if (mb_strlen($short) > 120) $short = mb_substr($short, 0, 117) . '...';

                        $starting = (float)($plan['starting_price'] ?? ($plan['base_price'] ?? 0));
                        $cal = $plan['calories_kcal'] ?? null;

                        $menuMode = (string)($plan['menu_mode'] ?? 'fixed');
                        $modePill = ($menuMode === 'choice') ? 'Choice Plan' : 'Fixed Menu';
                    ?>

                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-11 hs-plan-col"
                         data-category="<?= esc($categoryTag) ?>"
                         data-name="<?= esc(strtolower($plan['title'] ?? '')) ?>"
                         data-price="<?= esc((string)$starting) ?>">
                        <div class="subscription-card d-flex flex-column">
                            <div class="card-img-wrapper">
                                <div class="card-badge"><?= esc(strtoupper($badgeText)) ?></div>
                                <img src="<?= esc($imageUrl) ?>"
                                     alt="<?= esc($plan['title']) ?>"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='https://placehold.co/800x600/e5e7eb/9ca3af?text=Image+Not+Found';">
                                <div class="card-pill-right">
                                    <i class="bi bi-ui-checks-grid"></i>
                                    <span><?= esc($modePill) ?></span>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column flex-grow-1">
                                <h5 class="plan-title mb-1"><?= esc($plan['title']) ?></h5>
                                <p class="plan-desc mb-3"><?= esc($short) ?></p>

                                <div class="feature-strip d-flex justify-content-between mt-auto">
                                    <div class="feature-item">
                                        <i class="bi bi-fire text-danger"></i>
                                        <span><?= $cal ? (int)$cal : '400-600' ?> kcal</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="bi bi-truck text-success"></i>
                                        <span>Doorstep</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="bi bi-clock-history text-primary"></i>
                                        <span>Slots</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer-custom">
                                <div>
                                    <span class="price-label">Starting from</span>
                                    <div class="d-flex align-items-baseline">
                                        <span class="fs-6 fw-bold me-1">₹</span>
                                        <span class="price-value"><?= number_format($starting, 0) ?></span>
                                        <small class="text-muted fw-bold ms-1">/ pack</small>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-end gap-1">
                                    <a href="<?= site_url('subscriptions/' . esc($plan['slug'])) ?>#hs-config"
                                       class="btn btn-configure">
                                        Subscribe <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                    <a href="<?= site_url('subscriptions/' . esc($plan['slug'])) ?>"
                                       class="btn btn-outline-soft">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const filterButtons = document.querySelectorAll('.hs-filter-pill');
    const cards = Array.from(document.querySelectorAll('.hs-plan-col'));
    const search = document.getElementById('hsSearch');
    const sort = document.getElementById('hsSort');
    const count = document.getElementById('hsCount');

    function apply() {
        const filter = document.querySelector('.hs-filter-pill.active')?.dataset.filter || 'all';
        const q = (search?.value || '').trim().toLowerCase();
        const sortVal = sort?.value || 'featured';

        let visible = cards.filter(card => {
            const cat = card.dataset.category || 'balanced';
            const name = card.dataset.name || '';
            const passFilter = (filter === 'all') || (cat === filter);
            const passSearch = !q || name.includes(q);
            return passFilter && passSearch;
        });

        // sort
        visible.sort((a,b) => {
            const ap = parseFloat(a.dataset.price || '0');
            const bp = parseFloat(b.dataset.price || '0');
            const an = (a.dataset.name || '');
            const bn = (b.dataset.name || '');

            if (sortVal === 'price_low') return ap - bp;
            if (sortVal === 'price_high') return bp - ap;
            if (sortVal === 'name_az') return an.localeCompare(bn);
            return 0; // featured: keep server order
        });

        // hide all
        cards.forEach(c => c.classList.add('d-none'));

        // show visible in new order
        const grid = document.getElementById('hsGrid');
        visible.forEach(c => {
            c.classList.remove('d-none');
            grid.appendChild(c);
        });

        if (count) count.textContent = String(visible.length);
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            apply();
        });
    });

    search?.addEventListener('input', apply);
    sort?.addEventListener('change', apply);

    apply();
});
</script>

<?= $this->endSection() ?>