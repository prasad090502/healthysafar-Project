<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Products <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Salad & Product Catalog</h3>
        <p class="text-muted mb-0">Manage HealthySafar salads, juices, soups, and healthy products.</p>
    </div>
    <a href="<?= base_url('admin/products/new') ?>" class="btn btn-success btn-lg px-4 shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>Add New Product
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card small h-100">
            <div class="text-muted text-uppercase small fw-bold mb-1">Total Products</div>
            <h4 class="fw-bold mb-0"><?= (int) $stats['total'] ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card small h-100">
            <div class="text-muted text-uppercase small fw-bold mb-1">Active</div>
            <h4 class="fw-bold mb-0"><?= (int) $stats['active'] ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card small h-100">
            <div class="text-muted text-uppercase small fw-bold mb-1">Out of Stock</div>
            <h4 class="fw-bold mb-0 text-danger"><?= (int) $stats['oos'] ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card small h-100">
            <div class="text-muted text-uppercase small fw-bold mb-1">Low Stock (&lt; 5)</div>
            <h4 class="fw-bold mb-0 text-warning"><?= (int) $stats['low'] ?></h4>
        </div>
    </div>
</div>

<!-- Filters + Bulk Actions -->
<div class="card border-0 mb-3">
    <div class="card-body pb-2">
        <form class="row g-3 align-items-end" method="get" action="<?= current_url() ?>">
            <div class="col-md-2">
                <label class="form-label small text-muted">Category</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="all">All</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat['category']) ?>"
                            <?= $filters['category'] === $cat['category'] ? 'selected' : '' ?>>
                            <?= esc($cat['category']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Stock Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="all">All</option>
                    <option value="in_stock"    <?= $filters['status'] === 'in_stock' ? 'selected' : '' ?>>In stock</option>
                    <option value="out_of_stock"<?= $filters['status'] === 'out_of_stock' ? 'selected' : '' ?>>Out of stock</option>
                    <option value="preorder"   <?= $filters['status'] === 'preorder' ? 'selected' : '' ?>>Pre-order</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Active</label>
                <select name="active" class="form-select form-select-sm">
                    <option value="all" <?= $filters['active'] === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="1"   <?= $filters['active'] === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0"   <?= $filters['active'] === '0' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Sort By</label>
                <select name="sort" class="form-select form-select-sm">
                    <option value="created_desc" <?= $filters['sort'] === 'created_desc' ? 'selected' : '' ?>>Newest</option>
                    <option value="name_asc"     <?= $filters['sort'] === 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
                    <option value="name_desc"    <?= $filters['sort'] === 'name_desc' ? 'selected' : '' ?>>Name Z-A</option>
                    <option value="price_asc"    <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Price Low→High</option>
                    <option value="price_desc"   <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Price High→Low</option>
                    <option value="stock_low"    <?= $filters['sort'] === 'stock_low' ? 'selected' : '' ?>>Stock Low First</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Name / SKU / Category"
                       value="<?= esc($filters['q']) ?>">
            </div>
            <div class="col-md-1 text-end">
                <button class="btn btn-success btn-sm w-100">
                    <i class="bi bi-funnel me-1"></i> Go
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<form method="post" action="<?= base_url('admin/products/bulk-action') ?>">
    <?= csrf_field() ?>
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
                <div class="d-flex align-items-center gap-2">
                    <input type="checkbox" class="form-check-input" id="selectAllProducts">
                    <label for="selectAllProducts" class="small text-muted mb-0">Select all</label>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select name="action" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Bulk actions</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button class="btn btn-outline-secondary btn-sm" type="submit">
                        Apply
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width:36px;"></th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Flags</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <?php
                                    $price = $product['sale_price'] ?: $product['price'];
                                    $oos   = $product['stock_status'] === 'out_of_stock';
                                    $low   = !$oos && $product['stock_status'] === 'in_stock' && (int)$product['stock_quantity'] < 5;
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" class="form-check-input product-checkbox"
                                               name="ids[]" value="<?= $product['id'] ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url($product['thumbnail_image'] ?: 'assets/img/placeholder-product.png') ?>"
                                                 class="rounded-3 me-3" width="50" height="50" style="object-fit:cover;" alt="Product">
                                            <div>
                                                <span class="fw-bold text-dark d-block"><?= esc($product['name']) ?></span>
                                                <small class="text-muted d-block">
                                                    SKU: <?= esc($product['sku']) ?>
                                                </small>
                                                <?php if (!empty($product['tags'])): ?>
                                                    <small class="text-muted">
                                                        Tags: <?= esc($product['tags']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal">
                                            <?= esc($product['category']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold">
                                        ₹<?= number_format($price, 2) ?>
                                        <?php if (!empty($product['sale_price'])): ?>
                                            <div class="small text-muted">
                                                <span class="text-decoration-line-through">
                                                    ₹<?= number_format($product['price'], 2) ?>
                                                </span>
                                                <span class="ms-1 text-success">On sale</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($oos): ?>
                                            <span class="badge bg-danger-subtle text-danger">Out of stock</span>
                                        <?php elseif ($product['stock_status'] === 'preorder'): ?>
                                            <span class="badge bg-warning-subtle text-warning">Pre-order</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success">
                                                In stock (<?= (int) $product['stock_quantity'] ?>)
                                            </span>
                                            <?php if ($low): ?>
                                                <div class="small text-warning">Low stock</div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($product['is_active'])): ?>
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        <?php endif; ?>

                                        <?php if (isset($product['is_featured']) && $product['is_featured']): ?>
                                            <span class="badge bg-warning-subtle text-warning ms-1">
                                                <i class="bi bi-star-fill"></i> Featured
                                            </span>
                                        <?php endif; ?>

                                        <?php if (isset($product['subscription_available']) && $product['subscription_available']): ?>
                                            <span class="badge bg-primary-subtle text-primary ms-1">
                                                Subscriptions
                                            </span>
                                        <?php endif; ?>

                                        <?php if (isset($product['is_veg']) && !$product['is_veg']): ?>
                                            <span class="badge bg-danger-subtle text-danger ms-1">Non-veg</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success ms-1">Veg</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="<?= base_url('admin/products/'.$product['id'].'/toggle-status') ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-icon btn-sm btn-light me-1" type="submit"
                                                    title="Toggle active">
                                                <i class="bi bi-power"></i>
                                            </button>
                                        </form>
                                        <a href="<?= base_url('admin/products/'.$product['id'].'/edit') ?>"
                                           class="btn btn-icon btn-sm btn-light text-muted me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url('admin/products/'.$product['id'].'/delete') ?>"
                                              method="post" class="d-inline"
                                              onsubmit="return confirm('Delete this product?')">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-icon btn-sm btn-light text-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No products found for these filters.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($products)): ?>
                <div class="p-3 border-top">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllProducts');
    const checkboxes = document.querySelectorAll('.product-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    }
});
</script>

<?= $this->endSection() ?>