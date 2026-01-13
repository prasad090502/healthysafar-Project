<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Add Product <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold">Add New Product</h3>
        <p class="text-muted mb-0">Create a new salad, juice, soup, or healthy product.</p>
    </div>
</div>

<form action="<?= base_url('admin/products/store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?= view('admin/products/_form', ['mode' => $mode, 'product' => $product, 'errors' => $errors]) ?>
</form>

<?= $this->endSection() ?>