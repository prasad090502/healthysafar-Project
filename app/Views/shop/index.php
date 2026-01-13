<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    /* Make all shop product images same size + nicer card */
    .th-product.product-grid {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 16px 40px rgba(0,0,0,.05);
        background-color: #fff;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .th-product.product-grid:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 50px rgba(0,0,0,.07);
    }

    .th-product .product-img {
        position: relative;
        width: 100%;
        height: 230px; /* adjust as needed */
        background: #f7faf8;
        overflow: hidden;
    }
    .th-product .product-img img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform .25s ease;
    }
    .th-product:hover .product-img img {
        transform: scale(1.04);
    }

    .th-product .product-content {
        padding-bottom: 18px;
    }
    .th-product .product-title a {
        text-decoration: none;
    }
    .th-product .price {
        font-weight: 700;
        color: #2f7f67;
    }
    .th-product .price del {
        font-weight: 400;
        color: #999;
        font-size: 0.85em;
        margin-left: 4px;
    }

    .th-sort-bar {
        margin-bottom: 20px;
    }

    .th-product .product-img .product-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(73,177,190,.9);
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: .08em;
    }
    .th-product .product-img .actions {
        position: absolute;
        right: 10px;
        bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .th-product .product-img .icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        background: rgba(255,255,255,.96);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 14px;
        color: #2f7f67;
        box-shadow: 0 10px 26px rgba(0,0,0,.12);
    }
    .th-product .product-img .icon-btn:hover {
        background: #2f7f67;
        color: #fff;
    }
</style>

<!--==============================
Breadcumb
==============================-->
<div class="breadcumb-wrapper" data-bg-src="<?= base_url('assets/img/bg/breadcumb-bg.jpg') ?>">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Shop</h1>
            <ul class="breadcumb-menu">
                <li><a href="<?= site_url('/') ?>">Home</a></li>
                <li>Shop</li>
            </ul>
        </div>
    </div>
</div>

<!--==============================
Product Area
==============================-->
<section class="space-top space-extra-bottom">
    <div class="container">

        <!-- Sort bar -->
        <div class="th-sort-bar">
            <div class="row justify-content-between align-items-center g-2">
                <div class="col-md-auto">
                    <?php
                    $currentPage = isset($currentPage) ? (int)$currentPage : 1;
                    $perPage     = isset($perPage) ? (int)$perPage : 12;

                    if (!empty($products)) {
                        $showingFrom = ($currentPage - 1) * $perPage + 1;
                        $showingTo   = $showingFrom + count($products) - 1;
                    } else {
                        $showingFrom = 0;
                        $showingTo   = 0;
                    }
                    $totalCount  = isset($totalProducts) ? (int)$totalProducts : $showingTo;
                    ?>
                    <p class="woocommerce-result-count mb-0">
                        Showing <?= $showingFrom ?>–<?= $showingTo ?> of <?= $totalCount ?> results
                    </p>
                </div>

                <div class="col-md-auto">
                    <form class="woocommerce-ordering d-flex align-items-center gap-2" method="get">
                        <?php
                        $qValue        = esc($q ?? '');
                        $currentOrder  = $currentOrderby ?? 'menu_order';
                        $currentCat    = esc($currentCategory ?? '');
                        ?>
                        <?php if ($qValue !== ''): ?>
                            <input type="hidden" name="q" value="<?= $qValue ?>">
                        <?php endif; ?>
                        <?php if ($currentCat !== ''): ?>
                            <input type="hidden" name="category" value="<?= $currentCat ?>">
                        <?php endif; ?>

                        <select name="orderby" class="orderby form-select form-select-sm" aria-label="Shop order" onchange="this.form.submit()">
                            <option value="menu_order" <?= $currentOrder === 'menu_order' ? 'selected' : '' ?>>Default Sorting</option>
                            <option value="popularity" <?= $currentOrder === 'popularity' ? 'selected' : '' ?>>Sort by popularity</option>
                            <option value="rating" <?= $currentOrder === 'rating' ? 'selected' : '' ?>>Sort by average rating</option>
                            <option value="date" <?= $currentOrder === 'date' ? 'selected' : '' ?>>Sort by latest</option>
                            <option value="price" <?= $currentOrder === 'price' ? 'selected' : '' ?>>Sort by price: low to high</option>
                            <option value="price-desc" <?= $currentOrder === 'price-desc' ? 'selected' : '' ?>>Sort by price: high to low</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="row gy-40">
            <?php if (! empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <?php
                        $id        = (int)($product['id'] ?? 0);
                        $slug      = esc($product['slug'] ?? '');
                        $detailUrl = $slug ? site_url('product/' . $slug) : '#';

                        // ---------- IMAGE: USE REAL DB PATH, FALLBACK TO PLACEHOLDER ----------
                        $rawImg = $product['image_url']
                            ?? $product['main_image']
                            ?? $product['thumbnail_image']
                            ?? '';

                        $rawImg = trim((string)$rawImg);

                        if ($rawImg === '' || strtolower($rawImg) === 'null') {
                            // Fallback: put your keyboard_arrow_right or default product image here
                            $img = base_url('assets/img/product/product_1_1.jpg');
                            // e.g. base_url('assets/img/product/keyboard_arrow_right.png');
                        } elseif (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://')) {
                            // Absolute URL already
                            $img = $rawImg;
                        } else {
                            // Relative path from DB, e.g. uploads/products/1763....png
                            $img = base_url($rawImg);
                        }
                        // ----------------------------------------------------------------------

                        $category  = esc($product['category'] ?? 'Fresh Produce');
                        $title     = esc($product['name'] ?? $product['title'] ?? 'Product');

                        $basePrice = (float)($product['price'] ?? 0);
                        $salePrice = (float)($product['sale_price'] ?? 0);
                        $final     = (float)($product['final_price'] ?? ($salePrice > 0 && $salePrice < $basePrice ? $salePrice : $basePrice));
                        $hasSale   = $salePrice > 0 && $salePrice < $basePrice;

                        $finalStr  = number_format($final, 2);
                        $baseStr   = number_format($basePrice, 2);

                        $tag       = esc($product['tag'] ?? ($hasSale ? 'Sale' : 'Fresh'));
                        $rating    = number_format((float)($product['average_rating'] ?? 5), 1);
                        $reviews   = (int)($product['review_count'] ?? ($product['rating_count'] ?? 1));
                    ?>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-product product-grid">
                            <div class="product-img">
                                <img src="<?= $img ?>" alt="<?= $title ?>">
                                <span class="product-tag"><?= $tag ?></span>
                                <div class="actions">
                                    <!-- Quick View -->
                                    <a href="<?= $detailUrl ?>" class="icon-btn" title="View details">
                                        <i class="far fa-eye"></i>
                                    </a>

                                    <!-- Add to Cart -->
                                    <?php if ($id > 0): ?>
                                        <form action="<?= site_url('cart/add/' . $id) ?>" method="post" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit" class="icon-btn" title="Add to cart">
                                                <i class="far fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="product-content">
                                <a href="<?= $detailUrl ?>" class="product-category"><?= $category ?></a>
                                <h3 class="product-title">
                                    <a href="<?= $detailUrl ?>"><?= $title ?></a>
                                </h3>

                                <span class="price">
                                    ₹<?= $finalStr ?>
                                    <?php if ($hasSale): ?>
                                        <del>₹<?= $baseStr ?></del>
                                    <?php endif; ?>
                                </span>

                                <div class="woocommerce-product-rating">
                                    <span class="count">(<?= $reviews ?> Reviews)</span>
                                    <div class="star-rating" role="img" aria-label="Rated <?= $rating ?> out of 5">
                                        <span>
                                            Rated <strong class="rating"><?= $rating ?></strong>
                                            out of 5 based on
                                            <span class="rating"><?= $reviews ?></span> customer rating
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <!-- Fallback when no products -->
                <div class="col-12">
                    <div class="alert alert-info">
                        No products available right now. Please check back soon.
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && $pager): ?>
            <div class="th-pagination text-center pt-50">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?= $this->endSection() ?>