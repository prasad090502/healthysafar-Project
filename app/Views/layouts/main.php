<?php helper('url'); ?>
<!doctype html>
<html class="no-js" lang="zxx">
<?= $this->include('partials/head') ?>
<body>

    <?= $this->include('partials/header') ?>

    <!-- Page content -->
    <?= $this->renderSection('content') ?>

    <?= $this->include('partials/footer') ?>
    <?= $this->include('partials/scripts') ?>

</body>
</html>