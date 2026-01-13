<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Edit Product <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Edit Product</h3>
        <p class="text-muted mb-0">Update details, stock and media.</p>
    </div>
</div>

<form action="<?= base_url('admin/products/'.$product['id'].'/update') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?= view('admin/products/_form', ['mode' => $mode, 'product' => $product, 'errors' => $errors]) ?>
</form>

<?= $this->endSection() ?>