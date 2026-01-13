<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$subscriptions = $subscriptions ?? [];

function hs_sub_date(?string $d): string {
    if (!$d) return '-';
    $ts = strtotime($d);
    return $ts ? date('d M Y', $ts) : $d;
}
?>

<style>
    .hs-sub-page {
        padding-block: 32px 56px;
        background: radial-gradient(circle at top left,
            rgba(56,189,248,.08),
            rgba(16,185,129,.02),
            #f8fafc);
    }
    .hs-sub-header-title {
        font-weight: 800;
        font-size: 1.7rem;
        letter-spacing: -.03em;
        color: #0f172a;
    }
    .hs-sub-header-sub {
        color: #64748b;
        font-size: .9rem;
    }
    .hs-sub-card {
        border-radius: 18px;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 16px 18px 12px;
        margin-bottom: 14px;
        box-shadow: 0 20px 40px rgba(15,23,42,.05);
    }
    .hs-sub-plan-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .hs-sub-plan-meta {
        font-size: .82rem;
        color: #64748b;
    }
    .hs-sub-status-badge {
        font-size: .7rem;
        border-radius: 999px;
        padding: 2px 8px;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .hs-sub-status-active {
        background: #dcfce7;
        color: #166534;
        border-color: #22c55e;
    }
    .hs-sub-status-completed {
        background: #e5e7eb;
        color: #374151;
        border-color: #d1d5db;
    }
    .hs-sub-status-cancelled {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .hs-sub-progress-meta {
        display: flex;
        justify-content: space-between;
        font-size: .78rem;
        color: #6b7280;
        margin-top: 8px;
    }
    .hs-sub-progress-bar {
        height: 7px;
        border-radius: 999px;
        background: #e5e7eb;
        overflow: hidden;
        margin-top: 4px;
    }
    .hs-sub-progress-bar span {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, #22c55e, #16a34a);
    }

    .hs-sub-pill {
        font-size: .72rem;
        border-radius: 999px;
        padding: 2px 8px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 600;
        margin-right: 4px;
    }

    .hs-sub-footer {
        border-top: 1px dashed #e5e7eb;
        margin-top: 10px;
        padding-top: 8px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: .75rem;
        font-size: .8rem;
        color: #6b7280;
    }

    .btn-sub-details {
        border-radius: 999px;
        padding: 5px 14px;
        font-size: .8rem;
    }

    @media (max-width: 767.98px) {
        .hs-sub-card {
            padding-inline: 14px;
        }
    }
</style>

<div class="hs-sub-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">

                <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h2 class="hs-sub-header-title mb-1">
                            My Subscriptions
                        </h2>
                        <p class="hs-sub-header-sub mb-0">
                            See all your active and past meal plans in one place.
                        </p>
                    </div>
                </div>

                <?php if (empty($subscriptions)): ?>
                    <div class="alert alert-info rounded-4 border-0 shadow-sm">
                        You don’t have any subscriptions yet. Explore
                        <a href="<?= site_url('subscriptions') ?>">HealthySafar meal plans</a>.
                    </div>
                <?php else: ?>
                    <?php foreach ($subscriptions as $s): ?>
                        <?php
                            $status = strtolower((string)($s['status'] ?? 'active'));
                            $badgeClass = 'hs-sub-status-active';
                            if ($status === 'completed') {
                                $badgeClass = 'hs-sub-status-completed';
                            } elseif ($status === 'cancelled') {
                                $badgeClass = 'hs-sub-status-cancelled';
                            }

                            $total    = (int)($s['total_days'] ?? $s['duration_days'] ?? 0);
                            $deliv    = (int)($s['delivered_days'] ?? 0);
                            $remain   = (int)($s['remaining_days'] ?? max(0, $total - $deliv));
                            $progress = $total > 0 ? round(($deliv / $total) * 100) : 0;

                            $upcoming = $s['upcoming_deliveries'] ?? [];
                            $nextDate = $upcoming[0]['delivery_date'] ?? null;
                            $nextSlot = $upcoming[0]['slot_label'] ?? null;
                        ?>
                        <div class="hs-sub-card">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <div>
                                    <div class="hs-sub-plan-title">
                                        <?= esc($s['plan_title'] ?? 'Subscription Plan') ?>
                                    </div>
                                    <div class="hs-sub-plan-meta">
                                        #<?= esc($s['id']) ?> ·
                                        <?= hs_sub_date($s['start_date'] ?? null) ?> –
                                        <?= hs_sub_date($s['end_date'] ?? null) ?>
                                    </div>
                                </div>
                                <div class="text-sm-end">
                                    <span class="hs-sub-status-badge <?= $badgeClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                    <div class="mt-1">
                                        <span class="hs-sub-pill">
                                            <?= $deliv ?> delivered
                                        </span>
                                        <span class="hs-sub-pill">
                                            <?= $remain ?> remaining
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="hs-sub-progress-meta">
                                <span><?= $deliv ?>/<?= $total ?> days completed</span>
                                <span><?= $progress ?>%</span>
                            </div>
                            <div class="hs-sub-progress-bar">
                                <span style="width: <?= $progress ?>%;"></span>
                            </div>

                            <div class="hs-sub-footer">
                                <div>
                                    <?php if ($nextDate): ?>
                                        Next delivery
                                        <strong><?= hs_sub_date($nextDate) ?></strong>
                                        <?php if ($nextSlot): ?>
                                            · <strong><?= esc($nextSlot) ?></strong>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        No upcoming deliveries.
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="<?= site_url('customer/subscriptions/view/' . $s['id']) ?>"
                                       class="btn btn-outline-secondary btn-sub-details">
                                        View full schedule
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>