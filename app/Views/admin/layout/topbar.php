<div class="topbar-custom">
    <!-- Left: Toggle & Search -->
    <div class="d-flex align-items-center">
        <button type="button" id="sidebarCollapse" class="btn btn-light d-lg-none me-3">
            <i class="bi bi-list"></i>
        </button>
        <div class="search-box d-none d-md-block">
            <input type="text" placeholder="Search orders, salads, or users...">
        </div>
    </div>

    <!-- Right: Icons & Profile -->
    <div class="d-flex align-items-center gap-4">
        <!-- Notifications -->
        <div class="position-relative cursor-pointer">
            <i class="bi bi-bell fs-5 text-secondary"></i>
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                <span class="visually-hidden">New alerts</span>
            </span>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=05b46a&color=fff" alt="" width="38" height="38" class="rounded-circle me-2 shadow-sm">
                <div class="d-none d-sm-block text-start">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">Admin User</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Super Admin</div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#">Sign out</a></li>
            </ul>
        </div>
    </div>
</div>