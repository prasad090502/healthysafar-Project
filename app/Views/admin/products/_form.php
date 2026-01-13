<?php
// $mode: 'create' or 'edit'
// $product: array|null
// $errors: validation errors
$prod   = $product ?? [];
$errors = $errors ?? [];
?>
<div class="card border-0">
    <div class="card-header bg-white border-0">
        <!-- Header + Step progress -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-1">
                    <?= $mode === 'edit' ? 'Edit Product Details' : 'Create New Product' ?>
                </h5>
                <p class="text-muted mb-0 small">
                    Fill the details step by step — you can always go back before final submit.
                </p>
            </div>
            <div style="min-width:230px;">
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar"
                         id="productWizardProgress"
                         style="width: 20%;"></div>
                </div>
                <div class="small text-muted text-end mt-1">
                    <span id="productWizardStepLabel">Step 1 of 5</span>
                </div>
            </div>
        </div>

        <!-- Tabs header as wizard steps -->
        <ul class="nav nav-tabs card-header-tabs" id="productWizardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-general"
                        type="button" role="tab">
                    1. General
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-price-stock"
                        type="button" role="tab">
                    2. Pricing & Stock
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-nutrition"
                        type="button" role="tab">
                    3. Nutrition
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-media"
                        type="button" role="tab">
                    4. Media
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-seo"
                        type="button" role="tab">
                    5. SEO & Flags
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">

            <!-- 1. General -->
            <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="productNameInput"
                               class="form-control"
                               placeholder="e.g. Detox Green Salad"
                               value="<?= old('name', $prod['name'] ?? '') ?>">
                        <div class="text-danger small"><?= $errors['name'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">SKU</label>
                        <input type="text"
                               name="sku"
                               id="skuInput"
                               class="form-control"
                               placeholder="Leave empty to auto-generate"
                               value="<?= old('sku', $prod['sku'] ?? '') ?>">
                        <div class="text-danger small"><?= $errors['sku'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <input type="text"
                               name="category"
                               class="form-control"
                               placeholder="Salads / Juices / Soups / Healthy Snacks"
                               value="<?= old('category', $prod['category'] ?? '') ?>">
                        <div class="text-danger small"><?= $errors['category'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tags</label>
                        <input type="text"
                               name="tags"
                               class="form-control"
                               placeholder="comma separated, e.g. weight-loss,detox,high-protein"
                               value="<?= old('tags', $prod['tags'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Short Description</label>
                        <input type="text"
                               name="short_description"
                               id="shortDescInput"
                               class="form-control"
                               placeholder="1–2 line summary for listing card"
                               value="<?= old('short_description', $prod['short_description'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Long Description</label>
                        <textarea name="long_description"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Explain ingredients, taste profile, and health benefits..."><?= old('long_description', $prod['long_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 2. Pricing & Stock -->
            <div class="tab-pane fade" id="tab-price-stock" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Price (₹) <span class="text-danger">*</span></label>
                        <input type="number"
                               step="0.01"
                               name="price"
                               class="form-control"
                               value="<?= old('price', $prod['price'] ?? '') ?>">
                        <div class="text-danger small"><?= $errors['price'] ?? '' ?></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sale Price (₹)</label>
                        <input type="number"
                               step="0.01"
                               name="sale_price"
                               class="form-control"
                               placeholder="Leave empty if not on discount"
                               value="<?= old('sale_price', $prod['sale_price'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Status <span class="text-danger">*</span></label>
                        <?php $stockStatus = old('stock_status', $prod['stock_status'] ?? 'in_stock'); ?>
                        <select name="stock_status" class="form-select">
                            <option value="in_stock"    <?= $stockStatus === 'in_stock' ? 'selected' : '' ?>>In stock</option>
                            <option value="out_of_stock"<?= $stockStatus === 'out_of_stock' ? 'selected' : '' ?>>Out of stock</option>
                            <option value="preorder"   <?= $stockStatus === 'preorder' ? 'selected' : '' ?>>Pre-order</option>
                        </select>
                        <div class="text-danger small"><?= $errors['stock_status'] ?? '' ?></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Quantity</label>
                        <input type="number"
                               name="stock_quantity"
                               class="form-control"
                               value="<?= old('stock_quantity', $prod['stock_quantity'] ?? 0) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Max per order</label>
                        <input type="number"
                               name="max_per_order"
                               class="form-control"
                               placeholder="Limit quantity per order (optional)"
                               value="<?= old('max_per_order', $prod['max_per_order'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Preparation Time (minutes)</label>
                        <input type="number"
                               name="prep_time_minutes"
                               class="form-control"
                               placeholder="e.g. 10"
                               value="<?= old('prep_time_minutes', $prod['prep_time_minutes'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- 3. Nutrition -->
            <div class="tab-pane fade" id="tab-nutrition" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Serving Size</label>
                        <input type="text"
                               name="serving_size"
                               class="form-control"
                               placeholder="Per 100 g / Per 250 ml"
                               value="<?= old('serving_size', $prod['serving_size'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Calories (kcal)</label>
                        <input type="number"
                               name="calories_kcal"
                               class="form-control"
                               value="<?= old('calories_kcal', $prod['calories_kcal'] ?? 0) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Protein (g)</label>
                        <input type="number"
                               step="0.01"
                               name="protein_g"
                               class="form-control"
                               value="<?= old('protein_g', $prod['protein_g'] ?? 0) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Carbs (g)</label>
                        <input type="number"
                               step="0.01"
                               name="carbs_g"
                               class="form-control"
                               value="<?= old('carbs_g', $prod['carbs_g'] ?? 0) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Fat (g)</label>
                        <input type="number"
                               step="0.01"
                               name="fat_g"
                               class="form-control"
                               value="<?= old('fat_g', $prod['fat_g'] ?? 0) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Fibre (g)</label>
                        <input type="number"
                               step="0.01"
                               name="fibre_g"
                               class="form-control"
                               value="<?= old('fibre_g', $prod['fibre_g'] ?? 0) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sugar (g)</label>
                        <input type="number"
                               step="0.01"
                               name="sugar_g"
                               class="form-control"
                               value="<?= old('sugar_g', $prod['sugar_g'] ?? 0) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sodium (mg)</label>
                        <input type="number"
                               name="sodium_mg"
                               class="form-control"
                               value="<?= old('sodium_mg', $prod['sodium_mg'] ?? 0) ?>">
                    </div>
                </div>
            </div>

            <!-- 4. Media -->
           <!-- 4. Media -->
<div class="tab-pane fade" id="tab-media" role="tabpanel">
    <div class="row g-3">
        <!-- Main image -->
        <div class="col-md-4">
            <label class="form-label fw-semibold">Main Image</label>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 text-center">
                    <div class="mb-2">
                        <img
                            id="preview-main-image"
                            src="<?= !empty($prod['main_image']) ? base_url($prod['main_image']) : base_url('assets/img/product/product_1_1.jpg') ?>"
                            alt="Main image"
                            class="rounded"
                            style="width:100%;max-height:180px;object-fit:cover;"
                        >
                    </div>
                    <input type="file" name="main_image" id="input-main-image" class="form-control form-control-sm">
                    <small class="text-muted d-block mt-1">Recommended: square or 4:3 ratio</small>
                </div>
            </div>
        </div>

        <!-- Thumbnail -->
        <div class="col-md-4">
            <label class="form-label fw-semibold">Thumbnail Image</label>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 text-center">
                    <div class="mb-2">
                        <img
                            id="preview-thumb-image"
                            src="<?= !empty($prod['thumbnail_image']) ? base_url($prod['thumbnail_image']) : base_url('assets/img/product/product_1_1.jpg') ?>"
                            alt="Thumbnail"
                            class="rounded"
                            style="width:100%;max-height:140px;object-fit:cover;"
                        >
                    </div>
                    <input type="file" name="thumbnail_image" id="input-thumb-image" class="form-control form-control-sm">
                    <small class="text-muted d-block mt-1">Used in smaller cards or sliders</small>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <div class="col-md-4">
            <label class="form-label fw-semibold">Gallery Images</label>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2">
                    <input type="file" name="gallery_images[]" id="input-gallery-images"
                           class="form-control form-control-sm" multiple>

                    <small class="text-muted d-block mt-1">
                        You can select multiple images at once.
                    </small>

                    <div id="preview-gallery-wrapper" class="mt-2 d-flex flex-wrap gap-2">
                        <?php if (!empty($prod['gallery_images_array'])): ?>
                            <?php foreach ($prod['gallery_images_array'] as $g): ?>
                                <img src="<?= base_url($g) ?>"
                                     alt="Gallery"
                                     width="60"
                                     height="60"
                                     style="object-fit:cover;"
                                     class="rounded shadow-sm">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- 5. SEO & Flags -->
            <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">SEO Title</label>
                        <input type="text"
                               name="seo_title"
                               id="seoTitleInput"
                               class="form-control"
                               placeholder="Meta title for Google search result"
                               value="<?= old('seo_title', $prod['seo_title'] ?? '') ?>">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">SEO Description</label>
                        <textarea name="seo_description"
                                  id="seoDescInput"
                                  rows="2"
                                  class="form-control"
                                  placeholder="Short, benefit-oriented SEO description..."><?= old('seo_description', $prod['seo_description'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-12 mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   <?= old('is_active', $prod['is_active'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_featured"
                                   id="is_featured"
                                   value="1"
                                   <?= old('is_featured', $prod['is_featured'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_featured">Featured product</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="subscription_available"
                                   id="subscription_available"
                                   value="1"
                                   <?= old('subscription_available', $prod['subscription_available'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="subscription_available">Available in subscriptions</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <?php $isVeg = old('is_veg', $prod['is_veg'] ?? 1); ?>
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_veg"
                                   id="is_veg"
                                   value="1"
                                   <?= $isVeg ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_veg">Veg (uncheck if Non-veg)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wizard footer -->
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <a href="<?= base_url('admin/products') ?>" class="btn btn-light">
                Cancel
            </a>

            <div>
                <button type="button" class="btn btn-outline-secondary me-2" id="productWizardPrev">
                    <i class="bi bi-arrow-left"></i> Back
                </button>

                <button type="button" class="btn btn-success" id="productWizardNext">
                    Next <i class="bi bi-arrow-right"></i>
                </button>

                <button type="submit" class="btn btn-success d-none" id="productWizardSubmit">
                    <i class="bi bi-check2-circle me-1"></i>
                    <?= $mode === 'edit' ? 'Save Changes' : 'Create Product' ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ------- Wizard logic -------
    const tabLinks  = document.querySelectorAll('#productWizardTabs .nav-link');
    const stepCount = tabLinks.length;
    let currentStep = 0;

    const btnPrev   = document.getElementById('productWizardPrev');
    const btnNext   = document.getElementById('productWizardNext');
    const btnSubmit = document.getElementById('productWizardSubmit');
    const progress  = document.getElementById('productWizardProgress');
    const stepLabel = document.getElementById('productWizardStepLabel');

    function goToStep(index) {
        if (index < 0 || index >= stepCount) return;
        const tab = new bootstrap.Tab(tabLinks[index]);
        tab.show();
        currentStep = index;
        updateWizardUI();
    }

    function updateWizardUI() {
        const percent = ((currentStep + 1) / stepCount) * 100;
        if (progress) {
            progress.style.width = percent + '%';
        }
        if (stepLabel) {
            stepLabel.textContent = 'Step ' + (currentStep + 1) + ' of ' + stepCount;
        }

        if (btnPrev) {
            btnPrev.disabled = (currentStep === 0);
        }

        const isLast = (currentStep === stepCount - 1);
        if (btnNext) {
            btnNext.classList.toggle('d-none', isLast);
        }
        if (btnSubmit) {
            btnSubmit.classList.toggle('d-none', !isLast);
        }
    }

    if (btnNext) {
        btnNext.addEventListener('click', function () {
            if (currentStep < stepCount - 1) {
                goToStep(currentStep + 1);
            }
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', function () {
            if (currentStep > 0) {
                goToStep(currentStep - 1);
            }
        });
    }

    tabLinks.forEach((link, idx) => {
        link.addEventListener('shown.bs.tab', function () {
            currentStep = idx;
            updateWizardUI();
        });
    });

    updateWizardUI();

    // ------- SEO auto-fill logic -------
    const nameInput      = document.getElementById('productNameInput');
    const shortDescInput = document.getElementById('shortDescInput');
    const seoTitleInput  = document.getElementById('seoTitleInput');
    const seoDescInput   = document.getElementById('seoDescInput');

    let seoTitleTouched = !!(seoTitleInput && seoTitleInput.value.trim() !== '');
    let seoDescTouched  = !!(seoDescInput && seoDescInput.value.trim() !== '');

    if (seoTitleInput) {
        seoTitleInput.addEventListener('input', function () {
            seoTitleTouched = true;
        });
    }

    if (seoDescInput) {
        seoDescInput.addEventListener('input', function () {
            seoDescTouched = true;
        });
    }

    if (nameInput && seoTitleInput) {
        nameInput.addEventListener('input', function () {
            if (!seoTitleTouched) {
                const base = nameInput.value.trim();
                if (base.length > 0) {
                    seoTitleInput.value = base + ' | HealthySafar';
                } else {
                    seoTitleInput.value = '';
                }
            }
        });
    }

    if (shortDescInput && seoDescInput) {
        shortDescInput.addEventListener('input', function () {
            if (!seoDescTouched) {
                seoDescInput.value = shortDescInput.value;
            }
        });
    }

    // ------- Image preview logic -------
    const mainInput    = document.getElementById('input-main-image');
    const mainPreview  = document.getElementById('preview-main-image');
    const thumbInput   = document.getElementById('input-thumb-image');
    const thumbPreview = document.getElementById('preview-thumb-image');
    const galleryInput = document.getElementById('input-gallery-images');
    const galleryWrap  = document.getElementById('preview-gallery-wrapper');

    function readSingleImage(inputEl, imgEl) {
        if (!inputEl || !imgEl || !inputEl.files || !inputEl.files[0]) return;
        const file = inputEl.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            imgEl.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    if (mainInput && mainPreview) {
        mainInput.addEventListener('change', function () {
            readSingleImage(mainInput, mainPreview);
        });
    }

    if (thumbInput && thumbPreview) {
        thumbInput.addEventListener('change', function () {
            readSingleImage(thumbInput, thumbPreview);
        });
    }

    if (galleryInput && galleryWrap) {
        galleryInput.addEventListener('change', function () {
            // Clear old previews only for this selection
            galleryWrap.innerHTML = '';
            if (!galleryInput.files) return;

            Array.from(galleryInput.files).forEach(function (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = 60;
                    img.height = 60;
                    img.style.objectFit = 'cover';
                    img.className = 'rounded shadow-sm';
                    galleryWrap.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
</script>