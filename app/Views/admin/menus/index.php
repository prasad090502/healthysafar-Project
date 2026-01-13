<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menu Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                            <i class="fas fa-plus"></i> Add Menu
                        </button>
                    </div>
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
                        </tbody>
                    </table>
                </div>
            </div>
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
                    <div class="form-group">
                        <label for="menu_name">Menu Name</label>
                        <input type="text" class="form-control" id="menu_name" name="menu_name" required>
                    </div>
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <input type="text" class="form-control" id="short_description" name="short_description">
                    </div>
                    <div class="form-group">
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

<?= $this->endSection() ?>
