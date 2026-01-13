<?php
$session = session();

// Cart
$cart      = $session->get('cart') ?? [];
$cartCount = 0;
foreach ($cart as $ci) {
    $cartCount += (int)($ci['qty'] ?? 1);
}

// ALSO count subscription_cart (each entry = 1)
$subscriptionCart = $session->get('subscription_cart') ?? [];
foreach ($subscriptionCart as $si) {
    $cartCount += (int)($si['qty'] ?? 1) ?: 1;
}

// Customer login session
$isCustomerLoggedIn = (bool) $session->get('isCustomerLoggedIn');
$customerName       = (string) ($session->get('customer_name') ?? '');
$customerFirstName  = $customerName !== '' ? explode(' ', $customerName)[0] : '';
?>

<!--========================= Mobile Menu ==============================-->
<div class="th-menu-wrapper">
    <div class="th-menu-area text-center">
        <button class="th-menu-toggle">
            <i class="fal fa-times"></i>
        </button>

        <div class="mobile-logo">
            <a href="<?= site_url('/') ?>">
                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Healthy Safar">
            </a>
        </div>

        <div class="th-mobile-menu">
            <ul>
                <li><a href="<?= site_url('/') ?>">Home</a></li>
                <li><a href="<?= site_url('about') ?>">About Us</a></li>
                <li><a href="<?= site_url('contacts') ?>">Contact</a></li>
                <li><a href="<?= site_url('shop') ?>">Shop</a></li>

                <!-- NEW: Subscriptions link (mobile) -->
                <li><a href="<?= site_url('subscriptions') ?>">Subscriptions</a></li>

                <li><a href="<?= site_url('cart') ?>">Cart</a></li>

                <?php if (! $isCustomerLoggedIn): ?>
                    <li><a href="<?= site_url('customer/login') ?>">Login</a></li>
                    <li><a href="<?= site_url('customer/register') ?>">Register</a></li>
                <?php else: ?>
                    <li><a href="<?= site_url('customer/profile') ?>">My Profile</a></li>
                    <li><a href="<?= site_url('customer/orders') ?>">My Orders</a></li>
                    <li><a href="<?= site_url('customer/addresses') ?>">My Addresses</a></li>
                    <!-- (Later) you can add: <li><a href="<?= site_url('customer/subscriptions') ?>">My Subscriptions</a></li> -->
                    <li><a href="<?= site_url('customer/logout') ?>">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<!--==============================
Header Area
==============================-->
<header class="th-header header-layout1 hs-header">
    <div class="sticky-wrapper">
        <div class="menu-area">
            <div class="container">
                <div class="row align-items-center justify-content-between gx-3">

                    <!-- Logo -->
                    <div class="col-auto">
                        <div class="header-logo">
                            <a href="<?= site_url('/') ?>">
                                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Healthy Safar">
                            </a>
                        </div>
                    </div>

                    <!-- Main Menu -->
                    <div class="col-auto">
                        <nav class="main-menu d-none d-lg-inline-block">
                            <ul>
                                <li><a href="<?= site_url('/') ?>">Home</a></li>
                                <li><a href="<?= site_url('about') ?>">About Us</a></li>
                                
                                <!-- Shop -->
                                <li><a href="<?= site_url('shop') ?>">Shop</a></li>

                                <!-- NEW: Subscriptions (desktop top menu) -->
                                <li><a href="<?= site_url('subscriptions') ?>">Subscriptions</a></li>

                                <li><a href="<?= site_url('contacts') ?>">Contact</a></li>
                            </ul>
                        </nav>

                        <!-- Mobile toggle -->
                        <button type="button"
                                class="th-menu-toggle d-block d-lg-none hs-mobile-toggle">
                            <i class="far fa-bars"></i>
                        </button>
                    </div>

                    <!-- Right Side: Cart badge + Profile/Account -->
                    <div class="col-auto">
                        <div class="header-button d-flex align-items-center gap-2">

                            <!-- Cart Icon with badge -->
                            <a href="<?= site_url('cart') ?>"
                               class="simple-icon position-relative cart-icon-wrap"
                               aria-label="Cart">
                                <i class="fa-regular fa-cart-shopping"></i>
                                <?php if ($cartCount > 0): ?>
                                    <span class="badge cart-badge">
                                        <?= $cartCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- Account (login / user dropdown) -->
                            <?php if (! $isCustomerLoggedIn): ?>
                                <a href="<?= site_url('customer/login') ?>"
                                   class="header-avatar d-none d-md-flex align-items-center justify-content-center"
                                   title="Login / Register">
                                    <i class="far fa-user"></i>
                                </a>
                            <?php else: ?>
                                <div class="dropdown">
                                    <button
                                        class="btn btn-link p-0 border-0 d-flex align-items-center gap-2 account-btn account-chip"
                                        type="button"
                                        id="customerAccountDropdown"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        <div class="header-avatar d-none d-md-flex align-items-center justify-content-center">
                                            <i class="far fa-user"></i>
                                        </div>
                                        <span class="d-none d-lg-inline small fw-semibold text-dark">
                                            Hi, <?= esc($customerFirstName ?: $customerName ?: 'Customer') ?>
                                        </span>
                                        <i class="far fa-chevron-down small d-none d-lg-inline"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm account-dropdown"
                                        aria-labelledby="customerAccountDropdown">
                                        <li class="px-3 pt-2 pb-1 small text-muted">
                                            Signed in as<br>
                                            <strong><?= esc($customerName ?: 'Customer') ?></strong>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('customer/profile') ?>">
                                                <i class="far fa-user me-2"></i> My Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('customer/orders') ?>">
                                                <i class="far fa-bags-shopping me-2"></i> My Orders
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('customer/addresses') ?>">
                                                <i class="far fa-location-dot me-2"></i> My Addresses
                                            </a>
                                        </li>

                                        <!-- (Later) “My Subscriptions” when you build that page -->
                                        <!--
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('customer/subscriptions') ?>">
                                                <i class="far fa-calendar-check me-2"></i> My Subscriptions
                                            </a>
                                        </li>
                                        -->

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= site_url('customer/logout') ?>">
                                                <i class="far fa-sign-out-alt me-2"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div>
    </div>
</header>

<style>
    :root{
        --hs-green:#2f7f67;
        --hs-amber:#fbcb32;
        --hs-amber-deep:#f59e0b;
    }

    .hs-header .menu-area {
        padding-block: 10px;
        background-color: #ffffff;
    }

    .header-logo img{
        max-height:52px;
        width:auto;
    }

    .cart-icon-wrap {
        font-size: 18px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:34px;
        height:34px;
        border-radius:999px;
        background:#f2f7f5;
        box-shadow:0 6px 18px rgba(0,0,0,.06);
        color:var(--hs-green);
        text-decoration:none;
    }
    .cart-icon-wrap .fa-cart-shopping {
        font-size: 17px;
    }
    .cart-icon-wrap:hover{
        background:var(--hs-green);
        color:#fff;
    }
    .cart-icon-wrap .cart-badge {
        position: absolute;
        top: -6px;
        right: -10px;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 999px;
        background: var(--hs-amber);
        color: #233d32;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #fff;
    }

    .header-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f2f7f5;
        color: var(--hs-green);
        box-shadow: 0 6px 18px rgba(0,0,0,.08);
        text-decoration: none;
    }
    .header-avatar i {
        font-size: 16px;
    }
    .header-avatar:hover {
        background: #49b1be;
        color: #fff;
    }

    .account-btn {
        text-decoration: none;
        color: inherit;
    }
    .account-btn:focus-visible {
        outline: none;
        box-shadow: none;
    }
    .account-chip {
        padding: 4px 10px;
        border-radius: 999px;
        background: #f8faf9;
        border: 1px solid rgba(0,0,0,.04);
        box-shadow: 0 4px 12px rgba(0,0,0,.04);
    }
    .account-chip:hover {
        background: #e9f7f4;
    }

    .account-dropdown {
        border-radius: 14px;
        border: 1px solid rgba(0,0,0,.06);
        min-width: 220px;
        font-size: 13px;
    }
    .account-dropdown.dropdown-menu-end {
        margin-right: 12px;
        margin-top: 6px;
    }
    .account-dropdown .dropdown-item {
        padding: 7px 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .account-dropdown .dropdown-item i {
        width: 16px;
        text-align: center;
        font-size: 14px;
    }

    .hs-mobile-toggle {
        border-radius: 999px;
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: #f2f7f5;
        box-shadow: 0 6px 18px rgba(0,0,0,.08);
    }
    .hs-mobile-toggle i {
        font-size: 16px;
    }

    @media (max-width: 991.98px) {
        .hs-header .menu-area {
            padding-block: 8px;
        }
    }
    @media (max-width: 767.98px) {
        .header-logo img {
            max-height: 40px;
            width: auto;
        }
        .header-button {
            gap: 6px;
        }
    }

    .hs-header .menu-area .container {
        padding-right: 2.25rem;
    }

    @media (max-width: 991.98px) {
        .hs-header .menu-area .container {
            padding-right: 1rem;
        }
    }
</style>