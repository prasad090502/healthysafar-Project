<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
$selectedDate   = $selectedDate   ?? date('Y-m-d');
$selectedSlot   = $selectedSlot   ?? '';
$selectedPlanId = $selectedPlanId ?? '';
$selectedStatus = $selectedStatus ?? '';
$plans          = $plans          ?? [];
$deliveries     = $deliveries     ?? [];
$slotMap        = $slotMap        ?? [];

/**
 * Status labels (used in filter + update dropdown)
 */
$statuses = [
    'pending'          => 'Pending',
    'out_for_delivery' => 'Out for Delivery',
    'delivered'        => 'Delivered',
    'skipped'          => 'Skipped',
    'cancelled'        => 'Cancelled',
];

/**
 * Slot dropdown options:
 * - If you want fully dynamic slots, pass $allSlotKeys from controller.
 * - For now, keep common keys + show "All slots".
 */
$slotOptions = [
    ''        => 'All slots',
    'morning' => 'Morning',
    'lunch'   => 'Lunch',
    'dinner'  => 'Dinner',
];
?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Subscription Deliveries</h4>
            <div class="small text-muted">Filter by date, slot, plan and status. Update delivery status instantly.</div>
        </div>
    </div>

    <!-- Filters -->
    <form method="get" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Date</label>
                    <input type="date"
                           name="date"
                           value="<?= esc($selectedDate) ?>"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Time Slot</label>
                    <select name="slot" class="form-select form-select-sm">
                        <?php foreach ($slotOptions as $val => $label): ?>
                            <option value="<?= esc($val) ?>" <?= (string)$selectedSlot === (string)$val ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Uses delivery base/override slot keys.</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Subscription Plan</label>
                    <select name="plan_id" class="form-select form-select-sm">
                        <option value="">All plans</option>
                        <?php foreach ($plans as $p): ?>
                            <option value="<?= (int)$p['id'] ?>" <?= (string)$selectedPlanId === (string)$p['id'] ? 'selected' : '' ?>>
                                <?= esc($p['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <?php foreach ($statuses as $val => $label): ?>
                            <option value="<?= esc($val) ?>" <?= (string)$selectedStatus === (string)$val ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary w-100">Apply</button>
                </div>
            </div>
        </div>
    </form>

    <?php if (session('success')): ?>
        <div class="alert alert-success"><?= esc(session('success')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
        <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>
                Deliveries on <strong><?= esc(date('d M Y', strtotime($selectedDate))) ?></strong>
            </span>
            <span class="text-muted small">
                Total: <?= count($deliveries) ?>
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">#</th>
                            <th style="min-width:220px;">Plan / Selection</th>
                            <th style="min-width:170px;">Slot</th>
                            <th style="min-width:170px;">Customer</th>
                            <th style="min-width:200px;">Address</th>
                            <th style="min-width:180px;">Notes</th>
                            <th style="min-width:150px;">Menu</th>
                            <th style="width:120px;">Status</th>
                            <th style="width:200px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($deliveries)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">
                                    No deliveries found for this date / filters.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($deliveries as $row): ?>
                                <?php
                                $deliveryId = (int)($row['id'] ?? 0);
                                $planId     = (int)($row['subscription_plan_id'] ?? 0);

                                // Slot resolve: override > base
                                $slotKey    = !empty($row['override_slot_key']) ? $row['override_slot_key'] : ($row['base_slot_key'] ?? '');
                                $slotLabel  = $slotKey ?: '-';
                                $slotWindow = '';

                                if ($slotKey && isset($slotMap[$planId][$slotKey])) {
                                    $slotLabel  = $slotMap[$planId][$slotKey]['label'] ?? $slotLabel;
                                    $slotWindow = $slotMap[$planId][$slotKey]['window'] ?? '';
                                }

                                // Address resolve: override > base
                                $addrId = !empty($row['override_address_id']) ? $row['override_address_id'] : ($row['base_address_id'] ?? null);
                                $addressShort = $addrId ? ('Address ID ' . $addrId) : '-';

                                // Customer display (fallback)
                                $customerName  = 'Customer #' . ($row['customer_id'] ?? $row['user_id'] ?? '-');

                                // Choice-based selection info
                                $isChoice = !empty($row['is_choice_based']) && (int)$row['is_choice_based'] === 1;

                                $selectedType = $row['selected_type'] ?? null; // plan/menu_item
                                $selectedText = '';

                                // If your query joins titles, these keys will show. Otherwise fallback to IDs.
                                if ($isChoice) {
                                    if ($selectedType === 'menu_item') {
                                        $selectedText = $row['selected_menu_item_title']
                                            ?? (!empty($row['selected_menu_item_id']) ? ('Menu Item #' . (int)$row['selected_menu_item_id']) : 'Not Selected');
                                    } elseif ($selectedType === 'plan') {
                                        $selectedText = $row['selected_plan_title']
                                            ?? (!empty($row['selected_plan_id']) ? ('Plan #' . (int)$row['selected_plan_id']) : 'Not Selected');
                                    } else {
                                        // Selected type not saved yet
                                        $selectedText = 'Not Selected';
                                    }
                                }

                                $planTitle = $row['plan_title'] ?? '';

                                // Status badge
                                $status = $row['status'] ?? 'pending';
                                $badgeClass = match ($status) {
                                    'pending'          => 'bg-secondary',
                                    'out_for_delivery' => 'bg-warning text-dark',
                                    'delivered'        => 'bg-success',
                                    'skipped'          => 'bg-info text-dark',
                                    'cancelled'        => 'bg-danger',
                                    default            => 'bg-light text-dark',
                                };

                                $selectionNotes = $row['selection_notes'] ?? '';
                                $notes          = $row['notes'] ?? '';
                                ?>
                                <tr>
                                    <td><?= $deliveryId ?></td>

                                    <td>
                                        <div class="fw-semibold">
                                            <?= esc($planTitle) ?>
                                            <?php if ($isChoice): ?>
                                                <span class="badge bg-primary ms-1">Choice</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="small text-muted">
                                            Sub ID: <?= (int)($row['subscription_id'] ?? 0) ?>
                                            <?php if (!empty($row['delivery_date'])): ?>
                                                • <?= esc($row['delivery_date']) ?>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($isChoice): ?>
                                            <div class="mt-1">
                                                <span class="small text-muted">Selected:</span>
                                                <span class="small fw-semibold"><?= esc($selectedText) ?></span>
                                            </div>
                                            <?php if ($selectionNotes): ?>
                                                <div class="small text-muted">
                                                    Note: <?= esc($selectionNotes) ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="fw-semibold"><?= esc($slotLabel) ?></div>
                                        <?php if ($slotWindow): ?>
                                            <div class="small text-muted"><?= esc($slotWindow) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($row['override_slot_key'])): ?>
                                            <div class="small text-muted">Override applied</div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= esc($customerName) ?>
                                        <div class="small text-muted">
                                            UID: <?= esc($row['user_id'] ?? '-') ?> • CID: <?= esc($row['customer_id'] ?? '-') ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="small"><?= esc($addressShort) ?></div>
                                        <?php if (!empty($row['override_address_id'])): ?>
                                            <div class="small text-muted">Override applied</div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($notes): ?>
                                            <div class="small"><?= esc($notes) ?></div>
                                        <?php else: ?>
                                            <span class="small text-muted">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($row['menu_name'])): ?>
                                            <div class="fw-semibold"><?= esc($row['menu_name']) ?></div>
                                            <div class="small text-muted">Weekday: <?= esc($row['weekday'] ?? 'N/A') ?></div>
                                        <?php else: ?>
                                            <span class="small text-muted">No menu assigned</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-1">
                                            <form method="post"
                                                  action="<?= site_url('admin/subscription-deliveries/' . $deliveryId . '/status') ?>">
                                                <?= csrf_field() ?>
                                                <div class="input-group input-group-sm">
                                                    <select name="status" class="form-select form-select-sm">
                                                        <?php foreach ($statuses as $val => $label): ?>
                                                            <option value="<?= esc($val) ?>" <?= (string)$status === (string)$val ? 'selected' : '' ?>>
                                                                <?= esc($label) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button class="btn btn-outline-primary btn-sm" type="submit">
                                                        Go
                                                    </button>
                                                </div>
                                            </form>

                                            <button class="btn btn-outline-secondary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#changeMenuModal"
                                                    data-delivery-id="<?= $deliveryId ?>"
                                                    data-current-menu="<?= esc($row['menu_name'] ?? '') ?>"
                                                    data-weekday="<?= esc($row['menu_weekday'] ?? '') ?>">
                                                Change Menu
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Change Menu Modal -->
<div class="modal fade" id="changeMenuModal" tabindex="-1" aria-labelledby="changeMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeMenuModalLabel">Change Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changeMenuForm" method="post" action="<?= site_url('admin/subscription-deliveries/change-menu') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="delivery_id" id="modalDeliveryId">

                    <div class="mb-3">
                        <label class="form-label">Current Menu</label>
                        <input type="text" class="form-control" id="currentMenuDisplay" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Weekday</label>
                        <input type="text" class="form-control" id="weekdayDisplay" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="menuSelect" class="form-label">Select New Menu</label>
                        <select name="menu_id" id="menuSelect" class="form-select" required>
                            <option value="">Choose a menu...</option>
                            <!-- Menus will be loaded via JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const changeMenuModal = document.getElementById('changeMenuModal');
    const currentMenuDisplay = document.getElementById('currentMenuDisplay');
    const weekdayDisplay = document.getElementById('weekdayDisplay');
    const menuSelect = document.getElementById('menuSelect');
    const modalDeliveryId = document.getElementById('modalDeliveryId');

    changeMenuModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const deliveryId = button.getAttribute('data-delivery-id');
        const currentMenu = button.getAttribute('data-current-menu');
        const weekday = button.getAttribute('data-weekday');

        modalDeliveryId.value = deliveryId;
        currentMenuDisplay.value = currentMenu || 'No menu assigned';
        weekdayDisplay.value = weekday || 'N/A';

        // Load available menus for this weekday
        loadMenusForWeekday(weekday);
    });

    function loadMenusForWeekday(weekday) {
        fetch(`<?= site_url('admin/menus/get-by-weekday') ?>?weekday=${weekday}&active=1`)
            .then(response => response.json())
            .then(data => {
                menuSelect.innerHTML = '<option value="">Choose a menu...</option>';
                if (data.menus) {
                    data.menus.forEach(menu => {
                        const option = document.createElement('option');
                        option.value = menu.id;
                        option.textContent = menu.menu_name;
                        menuSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading menus:', error);
                menuSelect.innerHTML = '<option value="">Error loading menus</option>';
            });
    }
});
</script>

<?= $this->endSection() ?>
