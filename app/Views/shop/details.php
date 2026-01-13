<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// --------- Guards & data normalisation ---------
$product = $product ?? [];

$productId    = (int)($product['id'] ?? 0);
$title        = esc($product['name'] ?? $product['title'] ?? 'Product');
$slug         = esc($product['slug'] ?? '');
$sku          = esc($product['sku'] ?? 'N/A');
$category     = esc($product['category'] ?? 'Fresh Produce');
$tags         = $product['tags'] ?? ''; // comma-separated or array
$tagsArray    = is_array($tags) ? $tags : array_filter(array_map('trim', explode(',', (string)$tags)));

$price        = (float)($product['price'] ?? 0);
$salePrice    = (float)($product['sale_price'] ?? 0);
$finalPrice   = (float)($product['final_price'] ?? ($salePrice > 0 && $salePrice < $price ? $salePrice : $price));
$hasSale      = $salePrice > 0 && $salePrice < $price;

$shortDesc    = esc($product['short_description'] ?? '');
$longDesc     = $product['description'] ?? $product['long_description'] ?? $shortDesc;

// Stock
$inStock    = ($product['stock_status'] ?? '') === 'in_stock'
    || (int)($product['stock_qty'] ?? 0) > 0
    || !empty($product['in_stock']);
$stockLabel = $inStock ? 'In Stock' : 'Out of Stock';

// Rating (if present)
$avgRating  = isset($product['average_rating']) ? (float)$product['average_rating'] : null;
$ratingCnt  = isset($product['rating_count'])   ? (int)$product['rating_count']   : null;
$reviewCnt  = isset($product['review_count'])   ? (int)$product['review_count']   : $ratingCnt;

// -------- MAIN IMAGE: use actual path from DB (uploads/products/..) --------
$rawImg = trim((string)($product['main_image'] ?? $product['image_url'] ?? ''));
if ($rawImg === '' || strtolower($rawImg) === 'null') {
    $mainImg = base_url('assets/img/product/product_details_1_1.jpg');
} elseif (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://')) {
    $mainImg = $rawImg;
} else {
    $mainImg = base_url($rawImg); // e.g. uploads/products/xxxx.png
}

// -------- GALLERY (array of image paths from DB `gallery_images`) --------
$galleryImages = [];
if (!empty($product['gallery_images'])) {
    $decoded = json_decode($product['gallery_images'], true);
    if (is_array($decoded)) {
        $galleryImages = array_values(array_filter($decoded, static function ($g) {
            $g = trim((string)$g);
            return $g !== '' && strtolower($g) !== 'null';
        }));
    }
}

// If first gallery image exists, use that as default main (nicer for multi-image products)
if (!empty($galleryImages)) {
    $first = $galleryImages[0];
    if (!str_starts_with($first, 'http://') && !str_starts_with($first, 'https://')) {
        $mainImg = base_url($first);
    } else {
        $mainImg = $first;
    }
}

// -------- Nutrition mapping (DB fields) --------
$servingSize  = esc($product['serving_size']    ?? 'Per 100 g');
$calories     = esc($product['calories_kcal']   ?? '-');
$protein      = esc($product['protein_g']       ?? '-');
$carbs        = esc($product['carbs_g']         ?? '-');
$sugar        = esc($product['sugar_g']         ?? '-');
$fat          = esc($product['fat_g']           ?? '-');
$fibre        = esc($product['fibre_g']         ?? '-');
$sodium       = esc($product['sodium_mg']       ?? '-');

$origin       = esc($product['origin']                 ?? 'India');
$shelfLife    = esc($product['shelf_life']             ?? '');
$storage      = esc($product['storage_instructions']   ?? 'Refrigerate after opening and consume fresh.');
$ingredients  = esc($product['ingredients']            ?? '');
$allergens    = esc($product['allergens']              ?? '');
$isOrganic    = !empty($product['organic_certified']);
$isVegan      = !empty($product['is_vegan']);
$isGlutenFree = !empty($product['is_gluten_free']);
?>

<style>
    :root{
        --hs-teal:#49b1be;
        --hs-amber:#fbcb32;
        --hs-ink:#0f172a;
        --hs-muted:#64748b;
        --hs-line:#e2e8f0;
        --hs-soft:#f7faf8;
    }

    .product-details {
        position: relative;
    }

    .breadcumb-wrapper {
        position: relative;
        padding: 70px 0 60px;
    }

    /* Main image + gallery */
    .product-main-wrap{
        position:relative;
    }

    .product-main-img-wrap {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15,23,42,.16);
        background: radial-gradient(circle at top left,
            rgba(73,177,190,.18),
            rgba(251,203,50,.05),
            #ffffff);
    }
    .product-main-img-inner {
        width: 100%;
        height: 430px;
        overflow: hidden;
        position: relative;
    }
    @media (max-width: 767.98px) {
        .product-main-img-inner {
            height: 320px;
        }
    }
    .product-main-img-inner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease, filter .3s ease;
        transform: scale(1.02);
    }
    .product-main-img-inner:hover img{
        transform: scale(1.05);
        filter: saturate(1.05);
    }

    .product-gallery-thumbs{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-top:10px;
    }
    .product-gallery-thumb{
        width:72px;
        height:72px;
        border-radius:14px;
        overflow:hidden;
        background:#f1f5f9;
        cursor:pointer;
        position:relative;
        border:1px solid transparent;
        transition:border-color .18s ease, box-shadow .18s ease, transform .12s ease;
    }
    .product-gallery-thumb img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
    .product-gallery-thumb.active{
        border-color:rgba(73,177,190,.75);
        box-shadow:0 8px 22px rgba(15,118,110,.18);
        transform:translateY(-1px);
    }

    /* Right side content */
    .product-about {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .product-badge-row{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        align-items:center;
        margin-bottom:10px;
    }

    .price-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--hs-teal), var(--hs-amber));
        color: #fff;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .rating-chip{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:4px 10px;
        border-radius:999px;
        background:rgba(251,191,36,.08);
        border:1px solid rgba(251,191,36,.45);
        font-size:11px;
        color:#92400e;
    }
    .rating-chip i{
        color:#f59e0b;
        font-size:12px;
    }

    .product-price-main {
        display: flex;
        flex-wrap:wrap;
        align-items: baseline;
        gap: 10px;
        margin-bottom: 6px;
    }
    .product-price-main .price-now {
        font-size: 28px;
        font-weight: 800;
        color: #2f7f67;
    }
    .product-price-main .price-old {
        font-size: 16px;
        color: #999;
        text-decoration: line-through;
    }
    .product-price-note{
        font-size:11px;
        color:var(--hs-muted);
        margin-bottom:4px;
    }

    .product-title {
        font-weight: 800;
        letter-spacing: 0.01em;
        margin-bottom: 6px;
        color:var(--hs-ink);
    }

    .meta-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }
    .meta-badge {
        padding: 4px 12px;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,.06);
        background: #fff;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #555;
    }
    .meta-badge i {
        margin-right: 4px;
        font-size: 11px;
    }
    .meta-badge--stock {
        border-color: rgba(40,167,69,.25);
        background: rgba(40,167,69,.06);
        color: #15803d;
    }
    .meta-badge--outstock {
        border-color: rgba(220,53,69,.25);
        background: rgba(220,53,69,.06);
        color: #b91c1c;
    }
    .meta-badge--organic {
        border-color: rgba(73,177,190,.25);
        background: rgba(73,177,190,.07);
        color: #2f7f67;
    }

    .short-text {
        font-size: 14px;
        color: #555;
        margin-bottom: 12px;
    }

    .product-meta-lines p {
        margin-bottom: 4px;
        font-size: 13px;
        color:var(--hs-muted);
    }
    .product-meta-lines .text-title {
        font-weight: 600;
        color: #333;
    }

    .tag-badges {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .tag-badge {
        padding: 2px 8px;
        border-radius: 999px;
        background: rgba(73,177,190,.06);
        color: #2f7f67;
        font-size: 11px;
    }

    .actions {
        margin-top: 16px;
        margin-bottom: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .quantity {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0;
        border-radius:999px;
        border:1px solid rgba(148,163,184,.6);
        overflow:hidden;
        background:#f9fafb;
    }

    /* CLEAN qty input – no caret, no spinners */
    .qty-input {
        width: 84px;
        border:none;
        text-align: center;
        background: transparent;
        font-size: 14px;
        padding: 6px 4px;
        outline: none;
        caret-color: transparent;         /* hide | cursor */
        -moz-appearance: textfield;       /* Firefox: remove spinner */
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;         /* Chrome/Safari/Edge: remove spinner */
        margin: 0;
    }

    .hs-qty-btn {
        border: none;
        background: transparent;
        padding: 0 10px;
        height:34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #6b7280;
        font-size: 12px;
        transition: background .12s ease, color .12s ease;
    }
    .hs-qty-btn:hover {
        background: rgba(73,177,190,.08);
        color: #0f766e;
    }

    .actions .th-btn{
        border-radius:999px;
        padding-inline:20px;
    }
    .icon-circle-btn{
        width:38px;
        height:38px;
        border-radius:999px;
        border:1px solid rgba(148,163,184,.6);
        background:#fff;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        color:#64748b;
        transition: background .15s ease, color .15s ease, box-shadow .15s ease;
    }
    .icon-circle-btn:hover{
        background:#ef4444;
        color:#fff;
        box-shadow:0 8px 20px rgba(239,68,68,.35);
        border-color:transparent;
    }

    /* Info cards below main area */
    .details-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
        gap: 22px;
        margin-top: 26px;
    }
    @media (max-width: 991.98px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
    }
    .product-info-card {
        background: #fff;
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: 0 10px 28px rgba(15,23,42,.05);
        border:1px solid rgba(226,232,240,.8);
    }
    .product-info-card h4 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        color:var(--hs-ink);
    }
    .product-info-card h4 i {
        color: var(--hs-teal);
    }
    .info-list {
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: 13px;
    }
    .info-list li {
        padding: 5px 0;
        border-bottom: 1px dashed rgba(226,232,240,.95);
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }
    .info-list li:last-child {
        border-bottom: none;
    }
    .info-key {
        color: #777;
    }
    .info-value {
        color: #333;
        font-weight: 500;
        text-align: right;
    }

    .highlight-list {
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: 13px;
    }
    .highlight-list li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 4px 0;
    }
    .highlight-list i {
        margin-top: 3px;
        color: #2f7f67;
    }

    /* Tabs */
    .product-tab-style1 .nav-link {
        border-radius: 999px !important;
        margin: 4px 6px 0 0;
        font-size: 13px;
        border:1px solid transparent;
    }
    .product-tab-style1 .nav-link.active{
        border-color:rgba(73,177,190,.65);
        box-shadow:0 8px 22px rgba(15,118,110,.20);
    }

    .nutrition-card,
    .ingredients-card {
        background: #fff;
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: 0 10px 28px rgba(15,23,42,.05);
    }
    .nutrition-card h4,
    .ingredients-card h4 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        color:var(--hs-ink);
    }
    .nutrition-card h4 i,
    .ingredients-card h4 i {
        color: var(--hs-teal);
    }
    .nutrition-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .nutrition-table th,
    .nutrition-table td {
        padding: 6px 4px;
        border-bottom: 1px solid rgba(226,232,240,.9);
    }
    .nutrition-table th {
        font-weight: 600;
        color: #555;
    }
    .nutrition-table .nutri-row-label {
        color: #666;
    }

    .related-title-line {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, rgba(0,0,0,.08), transparent);
    }

    /* Ensure all related product images are same size */
    .th-product .product-img {
        position: relative;
        width: 100%;
        height: 220px;
        background: #f7faf8;
        overflow: hidden;
    }
    .th-product .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
</style>

<!--==============================
 Breadcrumb
==============================-->
<div class="breadcumb-wrapper"
     data-bg-src="<?= base_url('assets/img/bg/breadcumb-bg.jpg') ?>">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title"><?= $title ?></h1>
            <ul class="breadcumb-menu">
                <li><a href="<?= site_url('/') ?>">Home</a></li>
                <li><a href="<?= site_url('shop') ?>">Shop</a></li>
                <li><?= $title ?></li>
            </ul>
        </div>
    </div>
</div>

<!--==============================
 Product Details
==============================-->
<section class="product-details space-top space-extra-bottom">
    <div class="container">
        <div class="row gx-60 align-items-start">
            <!-- IMAGE + GALLERY -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="product-main-wrap">
                    <div class="product-main-img-wrap">
                        <div class="product-main-img-inner">
                            <img src="<?= $mainImg ?>" alt="<?= $title ?>" id="hsMainImage">
                        </div>
                    </div>

                    <?php if (!empty($galleryImages)): ?>
                        <div class="product-gallery-thumbs mt-3">
                            <?php foreach ($galleryImages as $idx => $g): ?>
                                <?php
                                $g = trim((string)$g);
                                if (str_starts_with($g, 'http://') || str_starts_with($g, 'https://')) {
                                    $thumbUrl = $g;
                                } else {
                                    $thumbUrl = base_url($g);
                                }
                                $isActive = $idx === 0 ? 'active' : '';
                                ?>
                                <div class="product-gallery-thumb <?= $isActive ?>"
                                     data-main-src="<?= $thumbUrl ?>">
                                    <img src="<?= $thumbUrl ?>" alt="Gallery image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-lg-6">
                <div class="product-about">

                    <div class="product-badge-row">
                        <?php if ($hasSale && $price > 0): ?>
                            <div class="price-pill">
                                <span>Special Offer</span>
                                <span><?= number_format((($price - $finalPrice) / max($price, 1)) * 100, 0) ?>% OFF</span>
                            </div>
                        <?php endif; ?>

                        <?php if ($avgRating && $ratingCnt): ?>
                            <div class="rating-chip">
                                <i class="far fa-star"></i>
                                <span><?= number_format($avgRating, 1) ?></span>
                                <span>· <?= $ratingCnt ?> rating<?= $ratingCnt > 1 ? 's' : '' ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-price-main">
                        <span class="price-now">₹<?= number_format($finalPrice, 2) ?></span>
                        <?php if ($hasSale): ?>
                            <span class="price-old">₹<?= number_format($price, 2) ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if ($servingSize): ?>
                        <div class="product-price-note">
                            Approx. price for <?= $servingSize ?> (values may vary slightly).
                        </div>
                    <?php endif; ?>

                    <h2 class="product-title"><?= $title ?></h2>

                    <div class="meta-badges">
                        <span class="meta-badge">
                            <i class="far fa-tag"></i><?= $category ?>
                        </span>

                        <span class="meta-badge <?= $inStock ? 'meta-badge--stock' : 'meta-badge--outstock' ?>">
                            <i class="far fa-check-circle"></i><?= $stockLabel ?>
                        </span>

                        <?php if ($isOrganic): ?>
                            <span class="meta-badge meta-badge--organic">
                                <i class="far fa-leaf"></i>Organic
                            </span>
                        <?php endif; ?>

                        <?php if ($isVegan): ?>
                            <span class="meta-badge">
                                <i class="far fa-seedling"></i>Vegan
                            </span>
                        <?php endif; ?>

                        <?php if ($isGlutenFree): ?>
                            <span class="meta-badge">
                                <i class="far fa-bread-slice-slash"></i>Gluten Free
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($shortDesc): ?>
                        <p class="short-text"><?= $shortDesc ?></p>
                    <?php endif; ?>

                    <div class="product-meta-lines mb-1">
                        <p>
                            <strong class="text-title me-2">SKU:</strong>
                            <span><?= $sku ?></span>
                        </p>
                        <p>
                            <strong class="text-title me-2">Category:</strong>
                            <a href="<?= site_url('shop?category=' . urlencode($category)) ?>"><?= $category ?></a>
                        </p>
                        <?php if (!empty($tagsArray)): ?>
                            <p>
                                <strong class="text-title me-2">Tags:</strong>
                                <span class="tag-badges">
                                    <?php foreach ($tagsArray as $tag): ?>
                                        <span class="tag-badge"><?= esc($tag) ?></span>
                                    <?php endforeach; ?>
                                </span>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Actions (Add to cart wired) -->
                    <?php if ($productId > 0): ?>
                        <form action="<?= site_url('cart/add/' . $productId) ?>" method="post" class="mt-3">
                            <?= csrf_field() ?>
                            <div class="actions">
                                <div class="quantity">
                                    <button type="button" class="hs-qty-btn hs-qty-minus">
                                        <i class="far fa-minus"></i>
                                    </button>
                                    <input
                                        type="number"
                                        class="qty-input"
                                        step="1" min="1" max="100"
                                        name="qty" value="1" title="Qty"
                                        readonly
                                    >
                                    <button type="button" class="hs-qty-btn hs-qty-plus">
                                        <i class="far fa-plus"></i>
                                    </button>
                                </div>

                                <button type="submit" class="th-btn">
                                    <i class="far fa-cart-plus me-1"></i>Add to Cart
                                </button>

                                <button type="button" class="icon-circle-btn" title="Save for later">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>

                    <!-- EXTRA INFO (NO NUTRITION MACROS HERE) -->
                    <div class="details-grid">
                        <!-- Key Info -->
                        <div class="product-info-card">
                            <h4><i class="far fa-list-alt"></i> Product Information</h4>
                            <ul class="info-list">
                                <li>
                                    <span class="info-key">Origin</span>
                                    <span class="info-value"><?= $origin ?></span>
                                </li>
                                <?php if ($servingSize): ?>
                                    <li>
                                        <span class="info-key">Serving Size</span>
                                        <span class="info-value"><?= $servingSize ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($shelfLife): ?>
                                    <li>
                                        <span class="info-key">Shelf Life</span>
                                        <span class="info-value"><?= $shelfLife ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($storage): ?>
                                    <li>
                                        <span class="info-key">Storage</span>
                                        <span class="info-value"><?= $storage ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- Highlights -->
                        <div class="product-info-card">
                            <h4><i class="far fa-star"></i> Highlights</h4>
                            <ul class="highlight-list">
                                <?php if ($isOrganic): ?>
                                    <li><i class="far fa-check-circle"></i><span>Curated organic ingredients.</span></li>
                                <?php endif; ?>
                                <?php if ($isVegan): ?>
                                    <li><i class="far fa-check-circle"></i><span>Suitable for vegan diets.</span></li>
                                <?php endif; ?>
                                <?php if ($isGlutenFree): ?>
                                    <li><i class="far fa-check-circle"></i><span>Gluten free as per our data.</span></li>
                                <?php endif; ?>

                                <li><i class="far fa-check-circle"></i><span>Handled and packed for maximum freshness.</span></li>
                                <li><i class="far fa-check-circle"></i><span>Perfect for salads, bowls, smoothies or daily meals.</span></li>
                            </ul>
                        </div>
                    </div><!-- /details-grid -->
                </div>
            </div>
        </div>

        <!--==============================
        Tabs (Description + NUTRITION & INGREDIENTS)
        ==============================-->
        <ul class="nav product-tab-style1 mt-40" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link th-btn active" id="description-tab" data-bs-toggle="tab"
                   href="#description" role="tab" aria-controls="description" aria-selected="true">
                    Product Description
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link th-btn" id="nutrition-tab" data-bs-toggle="tab"
                   href="#nutrition" role="tab" aria-controls="nutrition" aria-selected="false">
                    Nutrition &amp; Ingredients
                </a>
            </li>
        </ul>

        <div class="tab-content" id="productTabContent">
            <!-- DESCRIPTION TAB -->
            <div class="tab-pane fade show active" id="description"
                 role="tabpanel" aria-labelledby="description-tab">
                <div class="mt-3">
                    <?= nl2br(esc($longDesc)) ?>
                </div>
            </div>

            <!-- NUTRITION & INGREDIENTS TAB -->
            <div class="tab-pane fade" id="nutrition"
                 role="tabpanel" aria-labelledby="nutrition-tab">
                <div class="mt-3 row gy-4">
                    <!-- Nutrition facts -->
                    <div class="col-lg-6">
                        <div class="nutrition-card">
                            <h4><i class="far fa-apple-whole"></i> Nutritional Information</h4>
                            <p class="mb-1">
                                <small>Approximate values <?= $servingSize ? '(' . $servingSize . ')' : '' ?></small>
                            </p>
                            <table class="nutrition-table">
                                <tbody>
                                <tr>
                                    <th class="nutri-row-label">Energy</th>
                                    <td><?= $calories ?> kcal</td>
                                </tr>
                                <tr>
                                    <th class="nutri-row-label">Protein</th>
                                    <td><?= $protein ?> g</td>
                                </tr>
                                <tr>
                                    <th class="nutri-row-label">Carbohydrates</th>
                                    <td><?= $carbs ?> g</td>
                                </tr>
                                <tr>
                                    <th class="nutri-row-label">Sugars</th>
                                    <td><?= $sugar ?> g</td>
                                </tr>
                                <tr>
                                    <th class="nutri-row-label">Total Fat</th>
                                    <td><?= $fat ?> g</td>
                                </tr>
                                <tr>
                                    <th class="nutri-row-label">Dietary Fibre</th>
                                    <td><?= $fibre ?> g</td>
                                </tr>
                                <tr>
                                    <th class="nutri-row-label">Sodium</th>
                                    <td><?= $sodium ?> mg</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ingredients + allergen + storage again -->
                    <div class="col-lg-6">
                        <div class="ingredients-card">
                            <h4><i class="far fa-seedling"></i> Ingredients &amp; Notes</h4>
                            <ul class="info-list">
                                <?php if ($ingredients): ?>
                                    <li>
                                        <span class="info-key">Ingredients</span>
                                        <span class="info-value"><?= $ingredients ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($allergens): ?>
                                    <li>
                                        <span class="info-key">Allergen Info</span>
                                        <span class="info-value"><?= $allergens ?></span>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <span class="info-key">Origin</span>
                                    <span class="info-value"><?= $origin ?></span>
                                </li>
                                <?php if ($shelfLife): ?>
                                    <li>
                                        <span class="info-key">Shelf Life</span>
                                        <span class="info-value"><?= $shelfLife ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($storage): ?>
                                    <li>
                                        <span class="info-key">Storage</span>
                                        <span class="info-value"><?= $storage ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($servingSize): ?>
                                    <li>
                                        <span class="info-key">Serving Size</span>
                                        <span class="info-value"><?= $servingSize ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- /nutrition tab -->
        </div><!-- /tab-content -->

        <!--==============================
        Related Products
        ==============================-->
        <?php if (!empty($relatedProducts ?? [])): ?>
            <div class="space-extra-top mb-30">
                <div class="row justify-content-between align-items-center mb-2">
                    <div class="col-md-auto">
                        <h2 class="sec-title text-center mb-0">Related Products</h2>
                    </div>
                    <div class="col-md d-none d-sm-block">
                        <hr class="related-title-line">
                    </div>
                    <div class="col-md-auto d-none d-md-block">
                        <div class="sec-btn">
                            <div class="icon-box">
                                <button data-slider-prev="#productSlider1" class="slider-arrow default">
                                    <i class="far fa-arrow-left"></i>
                                </button>
                                <button data-slider-next="#productSlider1" class="slider-arrow default">
                                    <i class="far fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper th-slider has-shadow" id="productSlider1"
                     data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":2},"768":{"slidesPerView":2},"992":{"slidesPerView":3},"1200":{"slidesPerView":4}}}'>
                    <div class="swiper-wrapper">
                        <?php foreach ($relatedProducts as $rel): ?>
                            <?php
                            $relId      = (int)($rel['id'] ?? 0);
                            $relTitle   = esc($rel['name'] ?? $rel['title'] ?? 'Product');
                            $relSlug    = esc($rel['slug'] ?? '');
                            $relUrl     = $relSlug ? site_url('product/' . $relSlug) : '#';
                            $relCat     = esc($rel['category'] ?? 'Fresh Produce');

                            $relBasePrice  = (float)($rel['price'] ?? 0);
                            $relSalePrice  = (float)($rel['sale_price'] ?? 0);
                            $relFinalPrice = (float)($rel['final_price'] ?? ($relSalePrice > 0 && $relSalePrice < $relBasePrice ? $relSalePrice : $relBasePrice));
                            $relPriceStr   = number_format($relFinalPrice, 2);

                            $relRawImg = trim((string)($rel['main_image'] ?? $rel['image_url'] ?? ''));
                            if ($relRawImg === '' || strtolower($relRawImg) === 'null') {
                                $relImg = base_url('assets/img/product/product_1_1.jpg');
                            } elseif (str_starts_with($relRawImg, 'http://') || str_starts_with($relRawImg, 'https://')) {
                                $relImg = $relRawImg;
                            } else {
                                $relImg = base_url($relRawImg);
                            }

                            $relTag     = esc($rel['tag'] ?? 'Fresh');
                            ?>
                            <div class="swiper-slide">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="<?= $relImg ?>" alt="<?= $relTitle ?>">
                                        <span class="product-tag"><?= $relTag ?></span>
                                        <div class="actions">
                                            <a href="<?= $relUrl ?>" class="icon-btn">
                                                <i class="far fa-eye"></i>
                                            </a>

                                            <?php if ($relId > 0): ?>
                                                <form action="<?= site_url('cart/add/' . $relId) ?>" method="post" style="display:inline;">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="qty" value="1">
                                                    <button type="submit" class="icon-btn" title="Add to cart">
                                                        <i class="far fa-cart-plus"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <a href="<?= site_url('wishlist') ?>" class="icon-btn" title="Add to wishlist">
                                                <i class="far fa-heart"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <a href="<?= $relUrl ?>" class="product-category"><?= $relCat ?></a>
                                        <h3 class="product-title">
                                            <a href="<?= $relUrl ?>"><?= $relTitle ?></a>
                                        </h3>
                                        <span class="price">₹<?= $relPriceStr ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div><!-- /.swiper-wrapper -->
                </div><!-- /.swiper -->

                <div class="d-block d-md-none mt-40 text-center">
                    <div class="icon-box">
                        <button data-slider-prev="#productSlider1" class="slider-arrow default">
                            <i class="far fa-arrow-left"></i>
                        </button>
                        <button data-slider-next="#productSlider1" class="slider-arrow default">
                            <i class="far fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('click', function (e) {
    // Quantity +/-
    const plusBtn  = e.target.closest('.hs-qty-plus');
    const minusBtn = e.target.closest('.hs-qty-minus');

    if (plusBtn || minusBtn) {
        const form  = (plusBtn || minusBtn).closest('form');
        if (!form) return;
        const input = form.querySelector('.qty-input');
        if (!input) return;

        let val = parseInt(input.value || '1', 10);
        if (Number.isNaN(val)) val = 1;

        if (plusBtn) {
            val += 1;
        } else if (minusBtn) {
            val -= 1;
        }

        if (val < 1) val = 1;
        if (val > 100) val = 100;
        input.value = val;

        return;
    }

    // Gallery click
    const thumb = e.target.closest('.product-gallery-thumb');
    if (thumb) {
        const mainImg = document.getElementById('hsMainImage');
        if (!mainImg) return;
        const src = thumb.getAttribute('data-main-src');
        if (!src) return;

        // Swap main image
        mainImg.src = src;

        // Active state
        document.querySelectorAll('.product-gallery-thumb').forEach(function (el) {
            el.classList.remove('active');
        });
        thumb.classList.add('active');
    }
});
</script>

<?= $this->endSection() ?>