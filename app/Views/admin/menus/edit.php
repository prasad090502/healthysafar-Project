<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Menu</h3>
                    <div class="card-tools">
                        <a href="<?= site_url('admin/menus') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Menus
                        </a>
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

                    <form action="<?= site_url('admin/menus/' . $menu['id'] . '/update') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="menu_name">Menu Name</label>
                            <input type="text" class="form-control" id="menu_name" name="menu_name" value="<?= esc($menu['menu_name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="weekday">Weekday</label>
                            <select class="form-control" id="weekday" name="weekday" required>
                                <option value="">Select Weekday</option>
                                <option value="Monday" <?= $menu['weekday'] == 'Monday' ? 'selected' : '' ?>>Monday</option>
                                <option value="Tuesday" <?= $menu['weekday'] == 'Tuesday' ? 'selected' : '' ?>>Tuesday</option>
                                <option value="Wednesday" <?= $menu['weekday'] == 'Wednesday' ? 'selected' : '' ?>>Wednesday</option>
                                <option value="Thursday" <?= $menu['weekday'] == 'Thursday' ? 'selected' : '' ?>>Thursday</option>
                                <option value="Friday" <?= $menu['weekday'] == 'Friday' ? 'selected' : '' ?>>Friday</option>
                                <option value="Saturday" <?= $menu['weekday'] == 'Saturday' ? 'selected' : '' ?>>Saturday</option>
                                <option value="Sunday" <?= $menu['weekday'] == 'Sunday' ? 'selected' : '' ?>>Sunday</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="short_description">Short Description</label>
                            <input type="text" class="form-control" id="short_description" name="short_description" value="<?= esc($menu['short_description'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="long_description">Long Description</label>
                            <textarea class="form-control" id="long_description" name="long_description" rows="4"><?= esc($menu['long_description'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Menu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
