<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
$mode      = $mode ?? 'create';
$plan      = $plan ?? [];
$nutrition = $nutrition ?? [];
$configRow = $configRow ?? [];
$durations = $durations ?? [];
$slots     = $slots ?? [];
$offDays   = $offDays ?? [];
$priceMap  = $priceMap ?? [];

/**
 * NEW from controller:
 * $existingSubs: list of existing subscription plans to pick as options
 *   each: id, title, short_description, thumbnail_url
 */
$existingSubs = $existingSubs ?? [];

/**
 * NEW from controller (edit mode)
 * $choices: rows from subscription_plan_choices
 *   each: id, subscription_plan_id, ref_type, ref_id, title, description, image_url, calories_kcal, is_active, sort_order
 */
$choices   = $choices ?? [];

$actionUrl = $mode === 'edit'
    ? site_url('admin/subscription-plans/' . $plan['id'] . '/update')
    : site_url('admin/subscription-plans/store');

$titleValue = old('title', $plan['title'] ?? '');
$slugValue  = old('slug', $plan['slug'] ?? '');

$thumbnailUrl = old('thumbnail_url', $plan['thumbnail_url'] ?? '');
$bannerUrl    = old('banner_url', $plan['banner_url'] ?? '');

/** Choice fields */
$menuMode    = old('menu_mode', $plan['menu_mode'] ?? 'fixed');
$choiceLimit = (int) old('choice_per_day_limit', $plan['choice_per_day_limit'] ?? 1);

/**
 * Build Choice Pool rows to show in UI:
 * Priority: old() -> $choices from DB -> default empty
 */
$choiceTitlesOld = old('choice_title');
if (!empty($choiceTitlesOld)) {
    $choiceIdsOld    = old('choice_id') ?? [];
    $choiceRefTypeOld= old('choice_ref_type') ?? [];
    $choiceRefIdOld  = old('choice_ref_id') ?? [];
    $choiceDescsOld  = old('choice_description') ?? [];
    $choiceImgsOld   = old('choice_image_url') ?? [];
    $choiceCalsOld   = old('choice_calories_kcal') ?? [];
    $choiceSortOld   = old('choice_sort_order') ?? [];
    $choiceActiveOld = old('choice_is_active') ?? [];

    $choicesToUse = [];
    foreach ($choiceTitlesOld as $i => $t) {
        $choicesToUse[] = [
            'id'            => $choiceIdsOld[$i] ?? '',
            'ref_type'      => $choiceRefTypeOld[$i] ?? 'menu',
            'ref_id'        => $choiceRefIdOld[$i] ?? '',
            'title'         => $t ?? '',
            'description'   => $choiceDescsOld[$i] ?? '',
            'image_url'     => $choiceImgsOld[$i] ?? '',
            'calories_kcal' => $choiceCalsOld[$i] ?? '',
            'sort_order'    => $choiceSortOld[$i] ?? 0,
            'is_active'     => isset($choiceActiveOld[$i]) ? (int)$choiceActiveOld[$i] : 1,
        ];
    }
} elseif (!empty($choices)) {
    $choicesToUse = [];
    foreach ($choices as $c) {
        $choicesToUse[] = [
            'id'            => $c['id'] ?? '',
            'ref_type'      => $c['ref_type'] ?? 'menu',
            'ref_id'        => $c['ref_id'] ?? '',
            'title'         => $c['title'] ?? '',
            'description'   => $c['description'] ?? '',
            'image_url'     => $c['image_url'] ?? '',
            'calories_kcal' => $c['calories_kcal'] ?? '',
            'sort_order'    => $c['sort_order'] ?? 0,
            'is_active'     => (int)($c['is_active'] ?? 1),
        ];
    }
} else {
    $choicesToUse = [];
}
?>

<div class="container-fluid py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <?= $mode === 'edit' ? 'Edit Subscription Plan' : 'Create Subscription Plan' ?>
            </h4>
            <small class="text-muted">
                Configure plan content, nutrition, pricing and delivery rules in one place.
            </small>
        </div>
        <a href="<?= site_url('admin/subscription-plans') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to list
        </a>
    </div>

    <!-- Flash + validation -->
    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Please fix the following:</div>
            <ul class="mb-0">
                <?php foreach (session('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <?php if (session('success')): ?>
        <div class="alert alert-success"><?= esc(session('success')) ?></div>
    <?php endif; ?>

    <!-- MAIN FORM -->
    <form method="post" action="<?= $actionUrl ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row gy-3">
            <!-- LEFT COLUMN -->
            <div class="col-lg-8">

                <!-- Basic Info -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Plan Content</strong>
                                <div class="small text-muted">What customer will see on subscription details page.</div>
                            </div>
                            <?php if (!empty($plan['is_active'])): ?>
                                <span class="badge bg-success-subtle text-success">Live</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Plan Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg"
                                   placeholder="e.g. Keto Weight Loss Plan"
                                   value="<?= esc($titleValue) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-flex align-items-center gap-2">
                                Slug <span class="badge bg-light text-muted border">URL</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">
                                    /subscriptions/
                                </span>
                                <input type="text" name="slug" class="form-control"
                                       placeholder="keto-weight-loss-plan"
                                       value="<?= esc($slugValue) ?>" required>
                            </div>
                            <small class="text-muted">Used in URLs and SEO. Only lowercase letters, numbers and hyphens.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" rows="2" class="form-control"
                                      placeholder="One or two lines that summarise the plan..."><?= esc(old('short_description', $plan['short_description'] ?? '')) ?></textarea>
                            <small class="text-muted">Shown on listing cards and quick previews.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Long Description / Health Benefits</label>
                            <textarea name="long_description" rows="5" class="form-control"
                                      placeholder="Explain what the plan includes, who it is for, and key health benefits..."><?= esc(old('long_description', $plan['long_description'] ?? '')) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Nutrition -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Nutrition (per meal approx.)</strong>
                                <div class="small text-muted">These values power the macro bars on the frontend.</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Calories (kcal)</label>
                                <input type="number" name="calories_kcal" class="form-control"
                                       placeholder="e.g. 500"
                                       value="<?= esc(old('calories_kcal', $nutrition['calories_kcal'] ?? '')) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Protein (g)</label>
                                <input type="text" name="protein_g" class="form-control"
                                       placeholder="e.g. 30"
                                       value="<?= esc(old('protein_g', $nutrition['protein_g'] ?? '')) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Carbs (g)</label>
                                <input type="text" name="carbs_g" class="form-control"
                                       placeholder="e.g. 20"
                                       value="<?= esc(old('carbs_g', $nutrition['carbs_g'] ?? '')) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fats (g)</label>
                                <input type="text" name="fats_g" class="form-control"
                                       placeholder="e.g. 25"
                                       value="<?= esc(old('fats_g', $nutrition['fats_g'] ?? '')) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mt-2">Fibre (g)</label>
                                <input type="text" name="fibre_g" class="form-control"
                                       placeholder="e.g. 6"
                                       value="<?= esc(old('fibre_g', $nutrition['fibre_g'] ?? '')) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mt-2">Sugar (g)</label>
                                <input type="text" name="sugar_g" class="form-control"
                                       placeholder="e.g. 8"
                                       value="<?= esc(old('sugar_g', $nutrition['sugar_g'] ?? '')) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mt-2">Sodium (mg)</label>
                                <input type="number" name="sodium_mg" class="form-control"
                                       placeholder="e.g. 500"
                                       value="<?= esc(old('sodium_mg', $nutrition['sodium_mg'] ?? '')) ?>">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Nutrition Notes</label>
                            <textarea name="nutrition_notes" rows="2" class="form-control"
                                      placeholder="Any notes about variations, allergen information, etc."><?= esc(old('nutrition_notes', $nutrition['notes'] ?? '')) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-lg-4">

                <!-- Pricing -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <strong>Pricing</strong>
                        <div class="small text-muted">Configure base price and duration-wise packages.</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Base Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="base_price" class="form-control"
                                       placeholder="e.g. 299"
                                       value="<?= esc(old('base_price', $plan['base_price'] ?? '0')) ?>" required>
                            </div>
                            <small class="text-muted">
                                Used when there is no duration-specific pricing or if pricing type is <strong>Per Day</strong>.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pricing Type</label>
                            <select name="pricing_type" class="form-select" required>
                                <option value="per_package" <?= (old('pricing_type', $plan['pricing_type'] ?? '') === 'per_package') ? 'selected' : '' ?>>Per Package</option>
                                <option value="per_day" <?= (old('pricing_type', $plan['pricing_type'] ?? '') === 'per_day') ? 'selected' : '' ?>>Per Day</option>
                            </select>
                        </div>

                        <!-- Menu Mode -->
                        <div class="mb-3">
                            <label class="form-label">Menu Mode</label>
                            <select name="menu_mode" id="menu_mode" class="form-select" required>
                                <option value="fixed" <?= $menuMode === 'fixed' ? 'selected' : '' ?>>Fixed (same item daily)</option>
                                <option value="choice" <?= $menuMode === 'choice' ? 'selected' : '' ?>>Choice (select per day)</option>
                            </select>
                            <small class="text-muted">
                                If set to <strong>Choice</strong>, define items below in <strong>Choice Pool</strong>.
                            </small>
                        </div>

                        <div class="mb-3" id="choiceLimitWrap" style="<?= $menuMode === 'choice' ? '' : 'display:none;' ?>">
                            <label class="form-label">Choice Per Day Limit</label>
                            <input type="number" name="choice_per_day_limit" class="form-control"
                                   min="1" value="<?= esc($choiceLimit) ?>">
                            <small class="text-muted">Usually 1 (one delivery box per day).</small>
                        </div>

                        <!-- Duration Pricing -->
                        <div class="mb-3">
                            <label class="form-label">Duration Pricing (optional)</label>
                            <small class="text-muted d-block mb-1">
                                Map duration → package price. If blank, base price logic will apply.
                            </small>
                            <div id="priceContainer">
                                <?php
                                $priceDaysOld    = old('price_days');
                                $priceAmountsOld = old('price_amounts');

                                if (!empty($priceDaysOld)) {
                                    $pricesToUse = [];
                                    foreach ($priceDaysOld as $idx => $d) {
                                        $pricesToUse[] = [
                                            'days'   => $d,
                                            'amount' => $priceAmountsOld[$idx] ?? '',
                                        ];
                                    }
                                } elseif (!empty($priceMap)) {
                                    $pricesToUse = [];
                                    foreach ($priceMap as $d => $amt) {
                                        $pricesToUse[] = [
                                            'days'   => $d,
                                            'amount' => $amt,
                                        ];
                                    }
                                } else {
                                    $pricesToUse = [
                                        ['days' => 7, 'amount' => ''],
                                        ['days' => 15, 'amount' => ''],
                                        ['days' => 30, 'amount' => ''],
                                    ];
                                }

                                foreach ($pricesToUse as $p):
                                ?>
                                    <div class="input-group mb-1 price-row">
                                        <input type="number" min="1" name="price_days[]" class="form-control form-control-sm"
                                               placeholder="Days" value="<?= esc($p['days']) ?>">
                                        <input type="number" step="0.01" name="price_amounts[]" class="form-control form-control-sm"
                                               placeholder="Amount (₹)" value="<?= esc($p['amount']) ?>">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePriceRow(this)">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="addPriceRow()">
                                <i class="bi bi-plus"></i> Add pricing
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                   value="<?= esc(old('sort_order', $plan['sort_order'] ?? '0')) ?>">
                            <small class="text-muted">Lower number appears higher in listing.</small>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" <?= old('is_active', $plan['is_active'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active (show on website)</label>
                        </div>
                    </div>
                </div>

                <!-- CHOICE POOL (INLINE) -->
                <div class="card mb-3 shadow-sm border-0" id="choicePoolCard" style="<?= $menuMode === 'choice' ? '' : 'display:none;' ?>">
                    <div class="card-header bg-light">
                        <strong>Choice Pool (User Options)</strong>
                        <div class="small text-muted">
                            Add existing subscriptions as options OR create custom menu options.
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- Existing subscription selector -->
                        <div class="mb-3">
                            <label class="form-label">Add Existing Subscription</label>
                            <div class="input-group">
                                <input class="form-control"
                                       id="existingSubSearch"
                                       list="existingSubsList"
                                       placeholder="Type and select… (e.g. 12 | Fruit Box 300ml)">
                                <button type="button" class="btn btn-outline-primary" onclick="addExistingChoice()">
                                    <i class="bi bi-plus"></i> Add
                                </button>
                            </div>

                            <datalist id="existingSubsList">
                                <?php foreach ($existingSubs as $s): ?>
                                    <option
                                        value="<?= esc($s['id']) ?> | <?= esc($s['title']) ?>"
                                        data-id="<?= (int)$s['id'] ?>"
                                        data-title="<?= esc($s['title']) ?>"
                                        data-desc="<?= esc($s['short_description'] ?? '') ?>"
                                        data-img="<?= esc($s['thumbnail_url'] ?? '') ?>"
                                    ></option>
                                <?php endforeach; ?>
                            </datalist>

                            <small class="text-muted d-block mt-1">
                                Adds a linked option (<code>ref_type=plan</code>) and pre-fills title/desc/image.
                            </small>
                        </div>

                        <!-- Chips -->
                        <div class="mb-3">
                            <label class="form-label mb-1">Selected Linked Options</label>
                            <div id="linkedChoiceChips" class="d-flex flex-wrap gap-2"></div>
                        </div>

                        <div class="alert alert-info py-2 small mb-2">
                            Tip: Keep titles short. You can override details even for linked options.
                        </div>

                        <div id="choiceContainer">
                            <?php if (empty($choicesToUse)): ?>
                                <div class="text-muted small mb-2">
                                    No options added yet. Use <strong>Add Existing</strong> or <strong>Add option</strong>.
                                </div>
                            <?php else: ?>
                                <?php foreach ($choicesToUse as $i => $c): ?>
                                    <div class="border rounded p-2 mb-2 choice-row bg-light-subtle" data-index="<?= (int)$i ?>">
                                        <input type="hidden" name="choice_id[]" value="<?= esc($c['id']) ?>">
                                        <input type="hidden" name="choice_ref_type[]" value="<?= esc($c['ref_type'] ?? 'menu') ?>">
                                        <input type="hidden" name="choice_ref_id[]" value="<?= esc($c['ref_id'] ?? '') ?>">

                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="fw-semibold small">
                                                Option #<?= (int)$i + 1 ?>
                                                <?php if (($c['ref_type'] ?? 'menu') === 'plan'): ?>
                                                    <span class="badge bg-success-subtle text-success ms-1">Linked</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary ms-1">Custom</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center">
                                                <input type="hidden" name="choice_is_active[<?= (int)$i ?>]" value="0">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="choice_is_active[<?= (int)$i ?>]" value="1"
                                                           <?= !empty($c['is_active']) ? 'checked' : '' ?>>
                                                    <label class="form-check-label small">Active</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-1">
                                            <input type="text" name="choice_title[]" class="form-control form-control-sm"
                                                   placeholder="Option title"
                                                   value="<?= esc($c['title']) ?>" required>
                                        </div>

                                        <div class="mb-1">
                                            <textarea name="choice_description[]" class="form-control form-control-sm" rows="2"
                                                      placeholder="Short description (optional)"><?= esc($c['description']) ?></textarea>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-8">
                                                <input type="text" name="choice_image_url[]" class="form-control form-control-sm"
                                                       placeholder="Image URL (optional)"
                                                       value="<?= esc($c['image_url']) ?>">
                                            </div>
                                            <div class="col-4">
                                                <input type="number" name="choice_calories_kcal[]" class="form-control form-control-sm"
                                                       placeholder="kcal"
                                                       value="<?= esc($c['calories_kcal']) ?>">
                                            </div>
                                        </div>

                                        <div class="row g-2 mt-1">
                                            <div class="col-6">
                                                <input type="number" name="choice_sort_order[]" class="form-control form-control-sm"
                                                       placeholder="Sort"
                                                       value="<?= esc($c['sort_order']) ?>">
                                            </div>
                                            <div class="col-6 d-grid">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeChoiceRow(this)">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addChoiceRow({ref_type:'menu', ref_id:''})">
                            <i class="bi bi-plus"></i> Add custom option
                        </button>

                        <small class="text-muted d-block mt-2">
                            Saved into <code>subscription_plan_choices</code>. Linked options use <code>ref_type=plan</code>.
                        </small>
                    </div>
                </div>

                <style>
                    .hs-chip{
                        display:inline-flex; align-items:center; gap:.35rem;
                        padding:.35rem .6rem; border-radius:999px;
                        border:1px solid #d1d5db; background:#fff; font-size:.82rem;
                    }
                    .hs-chip button{
                        border:none; background:transparent; padding:0; margin:0;
                        line-height:1; color:#ef4444; font-weight:700; cursor:pointer;
                    }
                </style>

                <!-- Images -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <strong>Images</strong>
                        <div class="small text-muted">Thumbnail is used in cards, banner is used on top of detail page.</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Thumbnail Image</label>
                            <div class="mb-2 text-center">
                                <div class="border rounded p-2 bg-light">
                                    <img id="thumbPreview"
                                         src="<?= $thumbnailUrl ? base_url($thumbnailUrl) : 'https://via.placeholder.com/300x200?text=Thumbnail' ?>"
                                         alt="Thumbnail preview"
                                         class="img-fluid rounded"
                                         style="max-height: 160px; object-fit: cover;">
                                </div>
                            </div>
                            <input type="file" name="thumbnail_file" class="form-control form-control-sm"
                                   accept="image/*" onchange="previewImage(this, 'thumbPreview')">
                            <small class="text-muted d-block">
                                Recommended: 4:3 ratio, around 800×600 px.
                            </small>
                            <input type="text" name="thumbnail_url" class="form-control form-control-sm mt-1"
                                   placeholder="uploads/subscriptions/keto-thumb.jpg"
                                   value="<?= esc($thumbnailUrl) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Image</label>
                            <div class="mb-2 text-center">
                                <div class="border rounded p-2 bg-light">
                                    <img id="bannerPreview"
                                         src="<?= $bannerUrl ? base_url($bannerUrl) : 'https://via.placeholder.com/600x250?text=Banner' ?>"
                                         alt="Banner preview"
                                         class="img-fluid rounded"
                                         style="max-height: 180px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                            <input type="file" name="banner_file" class="form-control form-control-sm"
                                   accept="image/*" onchange="previewImage(this, 'bannerPreview')">
                            <small class="text-muted d-block">Recommended: 1200×400 px or similar.</small>
                            <input type="text" name="banner_url" class="form-control form-control-sm mt-1"
                                   placeholder="uploads/subscriptions/keto-banner.jpg"
                                   value="<?= esc($bannerUrl) ?>">
                        </div>
                    </div>
                </div>

                <!-- Logistics -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-light">
                        <strong>Logistics & Rules</strong>
                        <div class="small text-muted">
                            Duration options, delivery slots, off days and postponement rules.
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Duration Options (days)</label>
                            <div id="durationContainer">
                                <?php
                                $durationOld = old('duration_options');
                                if (!empty($durationOld)) {
                                    $durationsToUse = $durationOld;
                                } elseif (!empty($durations)) {
                                    $durationsToUse = $durations;
                                } else {
                                    $durationsToUse = [7, 15, 30];
                                }
                                foreach ($durationsToUse as $d): ?>
                                    <div class="input-group mb-1 duration-row">
                                        <input type="number" name="duration_options[]" class="form-control form-control-sm"
                                               value="<?= esc($d) ?>" min="1">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="removeDurationRow(this)">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addDurationRow()">
                                <i class="bi bi-plus"></i> Add duration
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Delivery Time Slots</label>
                            <div id="slotContainer">
                                <?php
                                $slotsOld = old('slot_key');
                                if (!empty($slotsOld)) {
                                    $keys   = old('slot_key') ?? [];
                                    $labels = old('slot_label') ?? [];
                                    $wins   = old('slot_window') ?? [];
                                    $slotsToUse = [];
                                    foreach ($keys as $idx => $k) {
                                        $slotsToUse[] = [
                                            'key'    => $k,
                                            'label'  => $labels[$idx] ?? '',
                                            'window' => $wins[$idx] ?? '',
                                        ];
                                    }
                                } else {
                                    $slotsToUse = $slots;
                                }

                                if (empty($slotsToUse)) {
                                    $slotsToUse = [
                                        ['key' => 'lunch', 'label' => 'Lunch', 'window' => '12 PM - 2 PM']
                                    ];
                                }

                                foreach ($slotsToUse as $s):
                                ?>
                                    <div class="border rounded p-2 mb-2 slot-row bg-light-subtle">
                                        <div class="mb-1">
                                            <input type="text" name="slot_key[]" class="form-control form-control-sm"
                                                   placeholder="Key (e.g. lunch)"
                                                   value="<?= esc($s['key'] ?? '') ?>">
                                        </div>
                                        <div class="mb-1">
                                            <input type="text" name="slot_label[]" class="form-control form-control-sm"
                                                   placeholder="Label (e.g. Lunch)"
                                                   value="<?= esc($s['label'] ?? '') ?>">
                                        </div>
                                        <div class="mb-1">
                                            <input type="text" name="slot_window[]" class="form-control form-control-sm"
                                                   placeholder="Window (e.g. 12 PM - 2 PM)"
                                                   value="<?= esc($s['window'] ?? '') ?>">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSlotRow(this)">Remove</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSlotRow()">
                                <i class="bi bi-plus"></i> Add slot
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Off Days (no delivery)</label>
                            <div class="row gx-3">
                                <?php
                                $days = ['MON','TUE','WED','THU','FRI','SAT','SUN'];
                                foreach ($days as $d):
                                    $checked = in_array($d, $offDays ?: []);
                                ?>
                                    <div class="col-4">
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" type="checkbox"
                                                   name="off_days[]" value="<?= $d ?>"
                                                   id="off_<?= $d ?>" <?= $checked ? 'checked' : '' ?>>
                                            <label class="form-check-label small" for="off_<?= $d ?>"><?= $d ?></label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Postponement Limit</label>
                            <input type="number" name="postponement_limit" class="form-control"
                                   value="<?= esc(old('postponement_limit', $configRow['postponement_limit'] ?? 0)) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cut-off Hour (0–23)</label>
                            <input type="number" name="cut_off_hour" class="form-control"
                                   value="<?= esc(old('cut_off_hour', $configRow['cut_off_hour'] ?? 22)) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Min Start Offset (days)</label>
                            <input type="number" name="min_start_offset_days" class="form-control"
                                   value="<?= esc(old('min_start_offset_days', $configRow['min_start_offset_days'] ?? 1)) ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-4">
                    <?= $mode === 'edit' ? 'Update Plan' : 'Create Plan' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function addDurationRow() {
    const container = document.getElementById('durationContainer');
    const div = document.createElement('div');
    div.className = 'input-group mb-1 duration-row';
    div.innerHTML = `
        <input type="number" name="duration_options[]" class="form-control form-control-sm" min="1" value="7">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeDurationRow(this)">×</button>
    `;
    container.appendChild(div);
}
function removeDurationRow(btn) {
    const row = btn.closest('.duration-row');
    if (row) row.remove();
}

function addSlotRow() {
    const container = document.getElementById('slotContainer');
    const div = document.createElement('div');
    div.className = 'border rounded p-2 mb-2 slot-row bg-light-subtle';
    div.innerHTML = `
        <div class="mb-1"><input type="text" name="slot_key[]" class="form-control form-control-sm" placeholder="Key (e.g. lunch)"></div>
        <div class="mb-1"><input type="text" name="slot_label[]" class="form-control form-control-sm" placeholder="Label (e.g. Lunch)"></div>
        <div class="mb-1"><input type="text" name="slot_window[]" class="form-control form-control-sm" placeholder="Window (e.g. 12 PM - 2 PM)"></div>
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSlotRow(this)">Remove</button>
    `;
    container.appendChild(div);
}
function removeSlotRow(btn) {
    const row = btn.closest('.slot-row');
    if (row) row.remove();
}

function addPriceRow() {
    const container = document.getElementById('priceContainer');
    const div = document.createElement('div');
    div.className = 'input-group mb-1 price-row';
    div.innerHTML = `
        <input type="number" min="1" name="price_days[]" class="form-control form-control-sm" placeholder="Days">
        <input type="number" step="0.01" name="price_amounts[]" class="form-control form-control-sm" placeholder="Amount (₹)">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePriceRow(this)">×</button>
    `;
    container.appendChild(div);
}
function removePriceRow(btn) {
    const row = btn.closest('.price-row');
    if (row) row.remove();
}

/**
 * Choice Pool
 */
function addChoiceRow(data = {ref_type:'menu', ref_id:''}) {
    const container = document.getElementById('choiceContainer');
    const index = container.querySelectorAll('.choice-row').length;

    const refType = data.ref_type || 'menu';
    const refId   = (data.ref_id ?? '');
    const title   = data.title || '';
    const desc    = data.description || '';
    const img     = data.image_url || '';

    const div = document.createElement('div');
    div.className = 'border rounded p-2 mb-2 choice-row bg-light-subtle';
    div.setAttribute('data-index', index);

    div.innerHTML = `
        <input type="hidden" name="choice_id[]" value="">
        <input type="hidden" name="choice_ref_type[]" value="${escapeAttr(refType)}">
        <input type="hidden" name="choice_ref_id[]" value="${escapeAttr(refId)}">

        <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="fw-semibold small">
              Option #${index + 1}
              ${refType === 'plan' ? '<span class="badge bg-success-subtle text-success ms-1">Linked</span>' : '<span class="badge bg-secondary-subtle text-secondary ms-1">Custom</span>'}
            </div>
            <div class="d-flex gap-2 align-items-center">
                <input type="hidden" name="choice_is_active[${index}]" value="0">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" name="choice_is_active[${index}]" value="1" checked>
                    <label class="form-check-label small">Active</label>
                </div>
            </div>
        </div>

        <div class="mb-1">
            <input type="text" name="choice_title[]" class="form-control form-control-sm" placeholder="Option title" value="${escapeAttr(title)}" required>
        </div>

        <div class="mb-1">
            <textarea name="choice_description[]" class="form-control form-control-sm" rows="2" placeholder="Short description (optional)">${escapeHtml(desc)}</textarea>
        </div>

        <div class="row g-2">
            <div class="col-8">
                <input type="text" name="choice_image_url[]" class="form-control form-control-sm" placeholder="Image URL (optional)" value="${escapeAttr(img)}">
            </div>
            <div class="col-4">
                <input type="number" name="choice_calories_kcal[]" class="form-control form-control-sm" placeholder="kcal">
            </div>
        </div>

        <div class="row g-2 mt-1">
            <div class="col-6">
                <input type="number" name="choice_sort_order[]" class="form-control form-control-sm" placeholder="Sort" value="0">
            </div>
            <div class="col-6 d-grid">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeChoiceRow(this)">Remove</button>
            </div>
        </div>
    `;

    container.appendChild(div);
}

function removeChoiceRow(btn) {
    const row = btn.closest('.choice-row');
    if (row) {
        // if linked, also remove chip
        const refType = row.querySelector('input[name="choice_ref_type[]"]')?.value;
        const refId   = row.querySelector('input[name="choice_ref_id[]"]')?.value;
        if (refType === 'plan' && refId) {
            document.querySelectorAll('#linkedChoiceChips .hs-chip').forEach(ch => {
                if (ch.getAttribute('data-ref-id') === String(refId)) ch.remove();
            });
        }
        row.remove();
    }
    renumberChoiceRows();
}

function renumberChoiceRows() {
    const container = document.getElementById('choiceContainer');
    container.querySelectorAll('.choice-row').forEach((r, i) => {
        r.setAttribute('data-index', i);

        const label = r.querySelector('.fw-semibold.small');
        const refType = r.querySelector('input[name="choice_ref_type[]"]')?.value || 'menu';
        if (label) {
            label.innerHTML = `Option #${i + 1} ` + (refType === 'plan'
                ? `<span class="badge bg-success-subtle text-success ms-1">Linked</span>`
                : `<span class="badge bg-secondary-subtle text-secondary ms-1">Custom</span>`);
        }

        const hiddenActive = r.querySelector('input[type="hidden"][name^="choice_is_active"]');
        const checkActive  = r.querySelector('input[type="checkbox"][name^="choice_is_active"]');
        if (hiddenActive) hiddenActive.name = `choice_is_active[${i}]`;
        if (checkActive)  checkActive.name  = `choice_is_active[${i}]`;
    });
}

/**
 * Existing subscription add (chip + row)
 */
function addExistingChoice() {
    const input = document.getElementById('existingSubSearch');
    const list  = document.getElementById('existingSubsList');
    const chips = document.getElementById('linkedChoiceChips');
    if (!input || !input.value) return;

    const raw = input.value.trim();
    const idPart = raw.split('|')[0]?.trim();
    const refId = parseInt(idPart, 10);
    if (!refId) { alert('Please select a valid subscription from the list.'); return; }

    // prevent duplicate (same ref_id + ref_type=plan)
    const container = document.getElementById('choiceContainer');
    const rows = container.querySelectorAll('.choice-row');
    for (const r of rows) {
        const rt = r.querySelector('input[name="choice_ref_type[]"]')?.value;
        const rid = r.querySelector('input[name="choice_ref_id[]"]')?.value;
        if (rt === 'plan' && String(rid) === String(refId)) {
            input.value = '';
            return;
        }
    }

    // best-effort get data from datalist
    let title = raw.split('|')[1]?.trim() || 'Selected';
    let desc = '';
    let img  = '';
    if (list && list.options) {
        const opt = Array.from(list.options).find(o => (o.value || '').startsWith(refId + ' |'));
        if (opt) {
            title = opt.getAttribute('data-title') || title;
            desc  = opt.getAttribute('data-desc') || '';
            img   = opt.getAttribute('data-img') || '';
        }
    }

    // add chip
    const chip = document.createElement('span');
    chip.className = 'hs-chip';
    chip.setAttribute('data-ref-id', refId);
    chip.innerHTML = `
      <i class="bi bi-check2-circle text-success"></i>
      <span>${escapeHtml(title)}</span>
      <button type="button" title="Remove" onclick="removeLinkedChoice(${refId})">×</button>
    `;
    chips.appendChild(chip);

    // add row (linked)
    addChoiceRow({
        ref_type: 'plan',
        ref_id: refId,
        title: title,
        description: desc,
        image_url: img
    });

    input.value = '';
}

function removeLinkedChoice(refId) {
    // remove chip
    document.querySelectorAll('#linkedChoiceChips .hs-chip').forEach(ch => {
        if (ch.getAttribute('data-ref-id') === String(refId)) ch.remove();
    });

    // remove row(s)
    document.querySelectorAll('#choiceContainer .choice-row').forEach(r => {
        const rt = r.querySelector('input[name="choice_ref_type[]"]')?.value;
        const rid = r.querySelector('input[name="choice_ref_id[]"]')?.value;
        if (rt === 'plan' && String(rid) === String(refId)) r.remove();
    });

    renumberChoiceRows();
}

/**
 * Simple image preview
 */
function previewImage(input, targetId) {
    const file = input.files && input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById(targetId);
        if (img) img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

/**
 * Toggle choice UI based on menu mode
 */
function syncChoiceUI() {
    const modeEl = document.getElementById('menu_mode');
    const wrap = document.getElementById('choiceLimitWrap');
    const card = document.getElementById('choicePoolCard');
    if (!modeEl) return;
    const show = (modeEl.value === 'choice');
    if (wrap) wrap.style.display = show ? '' : 'none';
    if (card) card.style.display = show ? '' : 'none';
}
document.getElementById('menu_mode')?.addEventListener('change', syncChoiceUI);
syncChoiceUI();

/** helpers */
function escapeHtml(str){
  return String(str ?? '')
    .replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;')
    .replaceAll('"','&quot;').replaceAll("'","&#039;");
}
function escapeAttr(str){ return escapeHtml(str); }

// On load: build chips for already-linked rows (edit mode)
(function(){
  const chips = document.getElementById('linkedChoiceChips');
  if(!chips) return;
  document.querySelectorAll('#choiceContainer .choice-row').forEach(r => {
    const rt = r.querySelector('input[name="choice_ref_type[]"]')?.value;
    const rid = r.querySelector('input[name="choice_ref_id[]"]')?.value;
    const title = r.querySelector('input[name="choice_title[]"]')?.value || 'Linked';
    if(rt === 'plan' && rid){
      const exists = chips.querySelector(`.hs-chip[data-ref-id="${rid}"]`);
      if(exists) return;
      const chip = document.createElement('span');
      chip.className = 'hs-chip';
      chip.setAttribute('data-ref-id', rid);
      chip.innerHTML = `<i class="bi bi-check2-circle text-success"></i><span>${escapeHtml(title)}</span>
        <button type="button" title="Remove" onclick="removeLinkedChoice(${parseInt(rid,10)})">×</button>`;
      chips.appendChild(chip);
    }
  });
})();
</script>

<?= $this->endSection() ?>