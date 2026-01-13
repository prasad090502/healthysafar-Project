<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
$plans      = $plans ?? [];
$pager      = $pager ?? null;
$searchTerm = $searchTerm ?? '';
$menus      = $menus ?? [];
?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Subscription Plans</h4>
        <a href="<?= site_url('admin/subscription-plans/create') ?>" class="btn btn-success btn-sm">
            + New Plan
        </a>
    </div>

    <!-- Search -->
    <form method="get" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q"
                       value="<?= esc($searchTerm) ?>"
                       class="form-control form-control-sm"
                       placeholder="Search by title or slug">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-outline-secondary w-100">Search</button>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Base Price</th>
                            <th>Pricing Type</th>
                            <th>Menu</th>
                            <th>Limit</th>
                            <th>Active</th>
                            <th>Sort</th>
                            <th style="width: 220px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($plans)): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    No subscription plans found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($plans as $p): ?>
                                <?php
                                    $menuMode = $p['menu_mode'] ?? 'fixed';
                                    $limit    = (int)($p['choice_per_day_limit'] ?? 1);
                                ?>
                                <tr>
                                    <td><?= (int)$p['id'] ?></td>
                                    <td>
                                        <?= esc($p['title']) ?><br>
                                        <small class="text-muted">
                                            <?= esc($p['short_description'] ?? '') ?>
                                        </small>
                                    </td>
                                    <td><code><?= esc($p['slug']) ?></code></td>
                                    <td>₹<?= number_format((float)$p['base_price'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= esc($p['pricing_type']) ?>
                                        </span>
                                    </td>

                                    <!-- Menu Mode -->
                                    <td>
                                        <?php if ($menuMode === 'choice'): ?>
                                            <span class="badge bg-primary">Choice</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border">Fixed</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Choice Limit -->
                                    <td>
                                        <?php if ($menuMode === 'choice'): ?>
                                            <span class="badge bg-light text-dark border"><?= $limit ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Active -->
                                    <td>
                                        <?php if (!empty($p['is_active'])): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= (int)$p['sort_order'] ?></td>

                                    <td class="d-flex gap-1 flex-wrap">
                                        <a href="<?= site_url('admin/subscription-plans/' . $p['id'] . '/edit') ?>"
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>

                                        <?php if ($menuMode === 'choice'): ?>
                                            <a href="<?= site_url('admin/subscription-plans/' . $p['id'] . '/choices') ?>"
                                               class="btn btn-sm btn-outline-success">
                                                Choices
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= site_url('admin/subscription-plans/' . $p['id'] . '/delete') ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Delete this plan?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($pager): ?>
            <div class="card-footer">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Menu Management Section -->
    <div class="card shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Menu Management</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                <i class="fas fa-plus"></i> Add Menu
            </button>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Menu Name</th>
                            <th>Short Description</th>
                            <th>Long Description</th>
                            <th>Weekday</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($menus)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">
                                    No menus found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($menus as $menu): ?>
                                <tr>
                                    <td><?= esc($menu['id']) ?></td>
                                    <td><?= esc($menu['menu_name']) ?></td>
                                    <td><?= esc($menu['short_description'] ?? '-') ?></td>
                                    <td><?= esc($menu['long_description'] ?? '-') ?></td>
                                    <td><?= esc($menu['weekday']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $menu['is_active'] ? 'success' : 'danger' ?>">
                                            <?= $menu['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td><?= esc($menu['created_at']) ?></td>
                                    <td>
                                        <a href="<?= site_url('admin/menus/' . $menu['id'] . '/edit') ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning toggle-status"
                                                data-id="<?= $menu['id'] ?>" data-status="<?= $menu['is_active'] ?>">
                                            <i class="fas fa-toggle-<?= $menu['is_active'] ? 'on' : 'off' ?>"></i>
                                            <?= $menu['is_active'] ? 'Deactivate' : 'Activate' ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Menu Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuModalLabel">Add New Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= site_url('admin/menus/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="menu_name">Menu Name</label>
                            <input type="text" class="form-control" id="menu_name" name="menu_name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="weekday">Weekday</label>
                            <select class="form-control" id="weekday" name="weekday" required>
                                <option value="">Select Weekday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="short_description">Short Description</label>
                            <input type="text" class="form-control" id="short_description" name="short_description">
                        </div>
                        <div class="form-group mb-3">
                            <label for="long_description">Long Description</label>
                            <textarea class="form-control" id="long_description" name="long_description" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.toggle-status').on('click', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var button = $(this);

            $.post('<?= site_url('admin/menus/toggle-status/') ?>' + id, {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error updating menu status');
                }
            }, 'json');
        });
    });
    </script>
</div>

<?= $this->endSection() ?>
