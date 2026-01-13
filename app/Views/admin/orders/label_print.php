<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= esc($title ?? 'Print Order Labels') ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        :root {
            --page-margin: 10mm;
            --label-width: 70mm;
            --label-height: 35mm;
        }

        @page {
            size: <?= $pageSize === 'Letter' ? 'letter' : 'A4' ?>;
            margin: 10mm;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .page {
            padding: var(--page-margin);
        }

        .grid {
            display: grid;
            grid-gap: 3mm;
        }

        <?php
        // Basic grid based on label size
        if ($labelSize === 'A4_3x8'): ?>
        .grid {
            grid-template-columns: repeat(3, 1fr);
        }
        :root {
            --label-height: 35mm;
        }
        <?php elseif ($labelSize === 'A4_2x7'): ?>
        .grid {
            grid-template-columns: repeat(2, 1fr);
        }
        :root {
            --label-height: 45mm;
        }
        <?php elseif ($labelSize === 'TH_100x150'): ?>
        .grid {
            grid-template-columns: 1fr;
        }
        :root {
            --label-width: 100mm;
            --label-height: 150mm;
        }
        <?php elseif ($labelSize === 'TH_50x70'): ?>
        .grid {
            grid-template-columns: 1fr;
        }
        :root {
            --label-width: 50mm;
            --label-height: 70mm;
        }
        <?php endif; ?>

        .label {
            border: 1px dashed #d1d5db;
            padding: 3mm 4mm;
            box-sizing: border-box;
            min-height: var(--label-height);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .lbl-header {
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 1mm;
        }

        .lbl-line {
            line-height: 1.3;
        }

        .lbl-meta {
            font-size: 10px;
            margin-top: 2mm;
            display: flex;
            justify-content: space-between;
            gap: 3mm;
        }

        .lbl-amount {
            font-weight: 700;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div class="no-print" style="padding:10px 14px;border-bottom:1px solid #e5e7eb;margin-bottom:6px;">
    <button onclick="window.print()" style="padding:6px 10px;border-radius:4px;border:1px solid #16a34a;background:#16a34a;color:#fff;cursor:pointer;">
        Print
    </button>
    <span style="font-size:12px;color:#6b7280;margin-left:8px;">
        Use browser print dialog to select correct paper & scaling (Actual size).
    </span>
</div>

<div class="page">
    <div class="grid">
        <?php foreach ($orders as $order): ?>
            <?php $ship = $order['shipping'] ?? null; ?>
            <div class="label">
                <div>
                    <?php if (in_array('order_number', $selectedFields, true)): ?>
                        <div class="lbl-header">
                            Order #<?= esc($order['order_number']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (in_array('customer_name', $selectedFields, true)): ?>
                        <div class="lbl-line">
                            <?= esc($order['customer_name'] ?? ($ship['name'] ?? 'Customer')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (in_array('address', $selectedFields, true) && $ship): ?>
                        <div class="lbl-line">
                            <?= esc($ship['address_line1'] ?? '') ?>
                            <?php if (!empty($ship['address_line2'])): ?>
                                , <?= esc($ship['address_line2']) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="lbl-line">
                        <?php if (in_array('city', $selectedFields, true) && $ship): ?>
                            <?= esc($ship['city']) ?>,
                        <?php endif; ?>
                        <?php if (in_array('state', $selectedFields, true) && $ship): ?>
                            <?= esc($ship['state']) ?>
                        <?php endif; ?>
                        <?php if (in_array('pincode', $selectedFields, true) && $ship): ?>
                            - <?= esc($ship['pincode']) ?>
                        <?php endif; ?>
                    </div>

                    <?php if (in_array('phone', $selectedFields, true)): ?>
                        <div class="lbl-line">
                            📞 <?= esc($ship['phone'] ?? $order['customer_contact'] ?? '') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="lbl-meta">
                    <div>
                        <div><?= date('d M, h:i A', strtotime($order['created_at'])) ?></div>
                        <div style="font-size:9px;color:#6b7280;">HealthySafar</div>
                    </div>
                    <?php if (in_array('grand_total', $selectedFields, true)): ?>
                        <div class="lbl-amount">
                            ₹<?= number_format($order['grand_total'], 2) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>