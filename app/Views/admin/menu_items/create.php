<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Add Menu Item' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-plus me-2"></i>Add Menu Item
                            </h4>
                            <a href="<?= site_url('admin/menu-items/' . $menu['id']) ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Back to Items
                            </a>
                        </div>
                        <p class="text-muted mb-0 mt-1">Adding item to: <strong><?= esc($menu['menu_name']) ?> (<?= esc($menu['weekday']) ?>)</strong></p>
                    </div>
                    <div class="card-body">
                        <!-- Flash Messages -->
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors:</h6>
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('admin/menu-items/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="item_name" class="form-label">
                                            Item Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['item_name']) ? 'is-invalid' : '' ?>"
                                               id="item_name"
                                               name="item_name"
                                               value="<?= old('item_name') ?>"
                                               required>
                                        <div class="invalid-feedback">
                                            <?= session()->getFlashdata('errors')['item_name'] ?? '' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            Price (₹) <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['price']) ? 'is-invalid' : '' ?>"
                                               id="price"
                                               name="price"
                                               value="<?= old('price', '0.00') ?>"
                                               step="0.01"
                                               min="0"
                                               required>
                                        <div class="invalid-feedback">
                                            <?= session()->getFlashdata('errors')['price'] ?? '' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control"
                                          id="description"
                                          name="description"
                                          rows="3"
                                          placeholder="Optional description of the menu item..."><?= old('description') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_available"
                                           name="is_available"
                                           value="1"
                                           <?= old('is_available', '1') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_available">
                                        Available for ordering
                                    </label>
                                </div>
                                <div class="form-text">Uncheck if this item is temporarily unavailable</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Save Menu Item
                                </button>
                                <a href="<?= site_url('admin/menu-items/' . $menu['id']) ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
