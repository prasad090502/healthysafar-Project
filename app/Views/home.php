<?php helper('url'); ?>
<!doctype html>
<html class="no-js" lang="zxx">
<head>
    ...
    <!-- Replace this: -->
    <!-- <link rel="stylesheet" href="assets/css/bootstrap.min.css"> -->

    <!-- With this: -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/fontawesome.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/magnific-popup.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/swiper-bundle.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?= base_url('assets/img/favicons/apple-icon.png') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicons/android-icon.png') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicons/favicon.png') ?>">
    ...
</head>
<body>
    ...
    <!-- For images, do the same: -->
    <!-- <img src="assets/img/logo.png"> -->
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Healthy Safar">
    ...
    <!-- JS files at bottom: -->
    <script src="<?= base_url('assets/js/vendor/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/swiper-bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.magnific-popup.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.counterup.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/imagesloaded.pkgd.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/isotope.pkgd.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>