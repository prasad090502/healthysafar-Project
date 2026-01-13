<nav id="sidebar">
    <div class="logo-area">
        <div class="logo-icon">
            <i class="bi bi-basket2-fill"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold text-white">HealthySafar</h5>
            <small class="text-muted" style="font-size: 0.75rem;">Admin Panel</small>
        </div>
    </div>

    <ul class="list-unstyled components">
        <div class="nav-title">Main Menu</div>
        
        <li>
            <a href="<?= base_url('admin/dashboard') ?>" class="<?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
        </li>
        
        <li>
            <a href="<?= base_url('admin/orders') ?>" class="<?= uri_string() === 'admin/orders' ? 'active' : '' ?>">
                <i class="bi bi-cart3"></i> Orders
                <?php if (!empty($sidebarPendingOrders)): ?>
                    <span class="badge bg-danger rounded-pill ms-auto"><?= (int) $sidebarPendingOrders ?></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li>
            <a href="<?= base_url('admin/products') ?>" class="<?= uri_string() === 'admin/products' ? 'active' : '' ?>">
                <i class="bi bi-egg-fried"></i> Menu / Products
            </a>
        </li>

        <li>
            <a href="<?= base_url('admin/customers') ?>" class="<?= uri_string() === 'admin/customers' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Customers
            </a>
        </li>

        <!-- Subscriptions block -->
        <div class="nav-title">Subscriptions</div>

        <li>
            <a href="<?= base_url('admin/subscription-plans') ?>" class="<?= uri_string() === 'admin/subscription-plans' ? 'active' : '' ?>">
                <i class="bi bi-card-checklist"></i> Subscription Plans
            </a>
        </li>

        <li>
            <a href="<?= base_url('admin/subscriptions') ?>" class="<?= uri_string() === 'admin/subscriptions' ? 'active' : '' ?>">
                <i class="bi bi-repeat"></i> Subscriptions
            </a>
        </li>

        <li>
            <a href="<?= base_url('admin/subscription-deliveries') ?>" class="<?= uri_string() === 'admin/subscription-deliveries' ? 'active' : '' ?>">
                <i class="bi bi-calendar2-check"></i> Today&rsquo;s Deliveries
            </a>
        </li>

        <div class="nav-title">Reports</div>
        <li>
            <a href="#">
                <i class="bi bi-graph-up"></i> Sales Report
            </a>
        </li>

        <div class="nav-title">Settings</div>
        <li>
            <a href="#">
                <i class="bi bi-gear"></i> General Config
            </a>
        </li>
        <li>
            <a href="#" class="text-danger-hover">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</nav>