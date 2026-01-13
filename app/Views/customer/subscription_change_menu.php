<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$delivery = $delivery ?? [];
$menus = $menus ?? [];
$currentMenu = $currentMenu ?? null;
$errors = $errors ?? [];
$success = $success ?? null;
$weekday = $weekday ?? '';
?>

<style>
    .menu-change-page {
        padding-block: 32px 56px;
        background: radial-gradient(circle at top left,
            rgba(56,189,248,.08),
            rgba(16,185,129,.02),
            #f8fafc);
    }

    .menu-change-card {
        border-radius: 18px;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 20px 40px rgba(15,23,42,.05);
        margin-bottom: 24px;
    }

    .delivery-info {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        border-left: 4px solid #22c55e;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }

    .menu-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .menu-card:hover {
        border-color: #22c55e;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(34,197,94,.15);
    }

    .menu-card.selected {
        border-color: #22c55e;
        background: #f0fdf4;
    }

    .menu-card.selected::after {
        content: '✓';
        position: absolute;
        top: 8px;
        right: 8px;
        background: #22c55e;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .menu-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .menu-weekday {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 8px;
    }

    .menu-description {
        font-size: 0.9rem;
        color: #475569;
        margin-bottom: 12px;
    }

    .skip-option {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .skip-option:hover {
        border-color: #f59e0b;
        background: #fffbeb;
    }

    .skip-option.selected {
        border-color: #f59e0b;
        background: #fffbeb;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 24px;
    }

    .btn-menu {
        border-radius: 999px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .alert-custom {
        border-radius: 12px;
        border: none;
        padding: 16px;
    }

    @media (max-width: 767.98px) {
        .menu-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="menu-change-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">

                <div class="menu-change-card">
                    <h3 class="mb-3" style="color: #0f172a; font-weight: 700;">
                        Change Menu for Your Delivery
                    </h3>

                    <!-- Delivery Info -->
                    <div class="delivery-info">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <strong>Delivery Date:</strong>
                                <?= date('l, d M Y', strtotime($delivery['delivery_date'] ?? 'today')) ?>
                                <br>
                                <strong>Current Menu:</strong>
                                <?= esc($currentMenu['menu_name'] ?? 'No menu assigned') ?>
                            </div>
                            <div class="text-end">
                                <span class="badge" style="background: #22c55e; color: white; padding: 6px 12px; border-radius: 999px;">
                                    <?= ucfirst($weekday) ?> Menu
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-custom">
                            <?= esc($success) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-custom">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form id="menuForm" method="post" action="">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <h5 style="color: #374151; font-weight: 600;">Choose Your <?= ucfirst($weekday) ?> Menu</h5>
                            <p style="color: #6b7280; font-size: 0.9rem;">
                                Select from available menu options for <?= $weekday ?>. All options are active and available for your subscription.
                            </p>
                        </div>

                        <!-- Menu Selection -->
                        <div class="menu-grid">
                            <?php foreach ($menus as $menu): ?>
                                <div class="menu-card <?= ($currentMenu && $currentMenu['id'] == $menu['id']) ? 'selected' : '' ?>"
                                     data-menu-id="<?= $menu['id'] ?>">
                                    <div class="menu-name">
                                        <?= esc($menu['menu_name']) ?>
                                    </div>
                                    <div class="menu-weekday">
                                        <?= ucfirst($menu['weekday']) ?> Special
                                    </div>
                                    <div class="menu-description">
                                        Delicious and healthy meal option for <?= $menu['weekday'] ?>.
                                    </div>
                                    <input type="radio"
                                           name="menu_id"
                                           value="<?= $menu['id'] ?>"
                                           style="display: none;"
                                           <?= ($currentMenu && $currentMenu['id'] == $menu['id']) ? 'checked' : '' ?>>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Skip Option -->
                        <div class="skip-option" id="skipOption">
                            <div style="font-size: 1.1rem; font-weight: 600; color: #92400e; margin-bottom: 4px;">
                                Skip This Delivery
                            </div>
                            <div style="color: #a16207;">
                                Choose this if you don't want any delivery on this date.
                            </div>
                            <input type="radio" name="menu_id" value="skip" style="display: none;">
                        </div>

                        <div class="action-buttons">
                            <a href="<?= site_url('customer/orders') ?>" class="btn btn-outline-secondary btn-menu">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-menu" id="submitBtn" disabled>
                                Update Menu
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuCards = document.querySelectorAll('.menu-card');
    const skipOption = document.getElementById('skipOption');
    const submitBtn = document.getElementById('submitBtn');
    const menuForm = document.getElementById('menuForm');

    function updateSelection() {
        // Remove selected class from all cards
        menuCards.forEach(card => card.classList.remove('selected'));
        skipOption.classList.remove('selected');

        // Check which option is selected
        const selectedRadio = menuForm.querySelector('input[name="menu_id"]:checked');
        if (selectedRadio) {
            if (selectedRadio.value === 'skip') {
                skipOption.classList.add('selected');
            } else {
                const selectedCard = document.querySelector(`.menu-card[data-menu-id="${selectedRadio.value}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                }
            }
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Add click handlers to menu cards
    menuCards.forEach(card => {
        card.addEventListener('click', function() {
            const menuId = this.getAttribute('data-menu-id');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            updateSelection();
        });
    });

    // Add click handler to skip option
    skipOption.addEventListener('click', function() {
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
        updateSelection();
    });

    // Initial state
    updateSelection();

    // Form validation
    menuForm.addEventListener('submit', function(e) {
        const selectedOption = menuForm.querySelector('input[name="menu_id"]:checked');
        if (!selectedOption) {
            e.preventDefault();
            alert('Please select a menu option or choose to skip the delivery.');
            return false;
        }
    });
});
</script>

<?= $this->endSection() ?>
