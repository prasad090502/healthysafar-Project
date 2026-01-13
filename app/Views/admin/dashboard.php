<?= $this->extend('admin/layout/master') ?>

<?= $this->section('title') ?> Dashboard <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h3 class="fw-bold text-dark">Good Morning, Admin! 🥗</h3>
        <p class="text-muted mb-0">Here’s what’s happening with your HealthySafar orders.</p>
    </div>
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-success text-white px-4 py-2 rounded-pill shadow-sm">
        <i class="bi bi-bag-plus me-1"></i> View Orders
    </a>
</div>

<!-- 1. Stats Row -->
<div class="row g-4 mb-4">
    <!-- Revenue Card -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="icon-bubble bubble-green">
                <i class="bi bi-currency-rupee"></i>
            </div>
            <h6 class="text-muted text-uppercase small fw-bold">Total Revenue</h6>
            <h3 class="fw-bold mb-1">₹<?= number_format($stats['revenue'], 2) ?></h3>
            <span class="text-success small fw-bold">
                <i class="bi bi-arrow-up-short"></i> Healthy Growth
            </span>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="icon-bubble bubble-orange">
                <i class="bi bi-bag-check"></i>
            </div>
            <h6 class="text-muted text-uppercase small fw-bold">Total Orders</h6>
            <h3 class="fw-bold mb-1"><?= (int) $stats['orders'] ?></h3>
            <span class="text-muted small">Lifetime</span>
        </div>
    </div>

    <!-- Customers Card -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="icon-bubble bubble-blue">
                <i class="bi bi-people"></i>
            </div>
            <h6 class="text-muted text-uppercase small fw-bold">Total Customers</h6>
            <h3 class="fw-bold mb-1"><?= (int) $stats['customers'] ?></h3>
            <span class="text-primary small fw-bold">Community</span>
        </div>
    </div>

    <!-- Pending Card -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="icon-bubble bubble-red">
                <i class="bi bi-clock-history"></i>
            </div>
            <h6 class="text-muted text-uppercase small fw-bold">Pending Orders</h6>
            <h3 class="fw-bold mb-1"><?= (int) $stats['pending_orders'] ?></h3>
            <span class="text-danger small fw-bold">Needs Attention</span>
        </div>
    </div>
</div>

<!-- Extra KPI Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="icon-bubble bubble-green">
                <i class="bi bi-brightness-alt-high"></i>
            </div>
            <h6 class="text-muted text-uppercase small fw-bold">Today’s Revenue</h6>
            <h3 class="fw-bold mb-1">₹<?= number_format($stats['today_revenue'], 2) ?></h3>
            <span class="text-muted small">From all paid orders today</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="icon-bubble bubble-orange">
                <i class="bi bi-calendar3"></i>
            </div>
            <h6 class="text-muted text-uppercase small fw-bold">This Month</h6>
            <h3 class="fw-bold mb-1">₹<?= number_format($stats['month_revenue'], 2) ?></h3>
            <span class="text-muted small">Revenue till now</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="icon-bubble bubble-blue">
                <i class="bi bi-graph-up"></i>
            </div>
            <h6 class="text-muted text-uppercase small fw-bold">Avg. Order Value</h6>
            <h3 class="fw-bold mb-1">₹<?= number_format($stats['avg_order_value'], 2) ?></h3>
            <span class="text-muted small">Paid orders only</span>
        </div>
    </div>
</div>

<!-- 2. Charts & Top Selling Item -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Monthly Revenue</h5>
                <span class="text-muted small">Last 6 months</span>
            </div>
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="stat-card h-100 text-white" style="background: linear-gradient(45deg, #05b46a, #049055);">
            <div class="p-2">
                <h5 class="fw-bold mb-4">Top Selling Item</h5>
                <div class="text-center mb-4">
                    <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-trophy-fill fs-1 text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-1">
                        <?= esc($stats['top_product']) ?>
                    </h4>
                    <p class="opacity-75 mb-0">
                        Ordered <?= (int) $stats['top_qty'] ?> times
                    </p>
                </div>
                <hr class="bg-white opacity-25">
                <div class="d-flex justify-content-between pt-2 small">
                    <span>Keep this hero in stock ✅</span>
                    <a href="<?= base_url('admin/products') ?>" class="text-white text-decoration-underline">Manage</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Top Products & Top Categories -->
<div class="row g-4 mb-4">
    <!-- Top Products -->
    <div class="col-lg-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Top Selling Products</h5>
                <span class="text-muted small">By quantity</span>
            </div>
            <canvas id="topProductsChart" height="220" class="mb-3"></canvas>

            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                   <thead>
    <tr>
        <th>#</th>
        <th>Product</th>
        <th class="text-end">Qty</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($topProducts)): ?>
        <?php foreach ($topProducts as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($p['product_name']) ?></td>
                <td class="text-end"><?= (int) $p['total_qty'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="3" class="text-center text-muted py-3">No product data yet.</td></tr>
    <?php endif; ?>
</tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="col-lg-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Top Categories</h5>
                <span class="text-muted small">By quantity</span>
            </div>
            <canvas id="topCategoriesChart" height="220" class="mb-3"></canvas>

            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Revenue (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topCategories)): ?>
                            <?php foreach ($topCategories as $i => $c): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($c['category_name'] ?? 'Uncategorised') ?></td>
                                    <td class="text-end"><?= (int) $c['total_qty'] ?></td>
                                    <td class="text-end"><?= number_format($c['total_amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">No category data yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- 4. Top Customers (More Ordered Customers) -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Most Valuable Customers</h5>
                <span class="text-muted small">Customers who ordered more</span>
            </div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th class="text-end">Total Orders</th>
                            <th class="text-end">Total Spent (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topCustomers)): ?>
                            <?php foreach ($topCustomers as $i => $c): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($c['customer_name'] ?? 'Guest') ?></td>
                                    <td class="text-end"><?= (int) $c['total_orders'] ?></td>
                                    <td class="text-end"><?= number_format($c['total_spent'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">No customer data yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 5. Recent Orders Table (same as your code) -->
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Orders</h5>
                <a href="<?= base_url('admin/orders') ?>" class="btn btn-sm btn-light text-primary fw-bold">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_orders)): ?>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td class="fw-bold text-primary">
                                        <a href="<?= base_url('admin/orders/'.$order['id']) ?>">
                                            <?= esc($order['order_number']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary me-2" style="width:32px;height:32px;font-size:0.8rem">
                                                <?= strtoupper(substr($order['customer_name'] ?? 'G', 0, 1)) ?>
                                            </div>
                                            <?= esc($order['customer_name'] ?? 'Guest') ?>
                                        </div>
                                    </td>
                                    <td><?= esc($order['item']) ?></td>
                                    <td class="fw-bold">₹<?= number_format($order['grand_total'], 2) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= esc($order['status']) ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?= date('d M, h:i A', strtotime($order['created_at'])) ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/orders/'.$order['id']) ?>" class="btn btn-sm btn-light border-0">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">No orders yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Charts Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Ensure Chart.js is loaded globally in your master layout
    const monthlyLabels   = <?= json_encode($charts['monthlyRevenue']['labels'] ?? []) ?>;
    const monthlyData     = <?= json_encode($charts['monthlyRevenue']['data'] ?? []) ?>;

    const topProdLabels   = <?= json_encode($charts['topProducts']['labels'] ?? []) ?>;
    const topProdData     = <?= json_encode($charts['topProducts']['data'] ?? []) ?>;

    const topCatLabels    = <?= json_encode($charts['topCategories']['labels'] ?? []) ?>;
    const topCatData      = <?= json_encode($charts['topCategories']['data'] ?? []) ?>;

    // Monthly Revenue Chart
    const revCanvas = document.getElementById('revenueChart');
    if (revCanvas && monthlyLabels.length) {
        new Chart(revCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: monthlyData,
                    borderColor: '#05b46a',
                    backgroundColor: 'rgba(5, 180, 106, 0.05)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#05b46a',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Top Products Chart
    const topProdCanvas = document.getElementById('topProductsChart');
    if (topProdCanvas && topProdLabels.length) {
        new Chart(topProdCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: topProdLabels,
                datasets: [{
                    label: 'Qty',
                    data: topProdData,
                    backgroundColor: 'rgba(5, 180, 106, 0.8)'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Top Categories Chart
    const topCatCanvas = document.getElementById('topCategoriesChart');
    if (topCatCanvas && topCatLabels.length) {
        new Chart(topCatCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: topCatLabels,
                datasets: [{
                    label: 'Qty',
                    data: topCatData,
                    backgroundColor: [
                        '#05b46a',
                        '#049055',
                        '#fb923c',
                        '#3b82f6',
                        '#6366f1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '65%'
            }
        });
    }
});
</script>

<?= $this->endSection() ?>