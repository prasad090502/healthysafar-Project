<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionPlanNutritionModel;
use App\Models\SubscriptionPlanConfigModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Subscriptions extends BaseController
{
    protected SubscriptionPlanModel $plans;
    protected SubscriptionPlanNutritionModel $nutrition;
    protected SubscriptionPlanConfigModel $config;

    public function __construct()
    {
        $this->plans     = new SubscriptionPlanModel();
        $this->nutrition = new SubscriptionPlanNutritionModel();
        $this->config    = new SubscriptionPlanConfigModel();
    }

    /**
     * List all active subscription plans.
     * URL: /subscriptions
     */
    public function index()
    {
        // If your model has getActivePlans() use it; otherwise fallback to simple query.
        if (method_exists($this->plans, 'getActivePlans')) {
            $plans = $this->plans->getActivePlans();
        } else {
            $plans = $this->plans
                ->where('is_active', 1)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'DESC')
                ->findAll();
        }

        // Bulk hydrate nutrition + config (avoid N+1)
        $ids = array_values(array_filter(array_map(fn($p) => (int)($p['id'] ?? 0), $plans)));
        $nutritionMap = [];
        $configMap    = [];

        if (!empty($ids)) {
            $nuts = $this->nutrition->whereIn('subscription_plan_id', $ids)->findAll();
            foreach ($nuts as $n) {
                $nutritionMap[(int)$n['subscription_plan_id']] = $n;
            }

            $cfgs = $this->config->whereIn('subscription_plan_id', $ids)->findAll();
            foreach ($cfgs as $c) {
                $configMap[(int)$c['subscription_plan_id']] = $c;
            }
        }

        // Compute starting price per plan (for beautiful cards)
        foreach ($plans as &$p) {
            $pid = (int)($p['id'] ?? 0);

            $configRow = $configMap[$pid] ?? [];
            $durations = $this->safeJsonArray($configRow['duration_options_json'] ?? null);
            $priceMap  = $this->safeJsonAssoc($configRow['duration_pricing_json'] ?? null);

            $p['nutrition'] = $nutritionMap[$pid] ?? [];
            $p['starting_price'] = $this->computeStartingPrice(
                (float)($p['base_price'] ?? 0),
                (string)($p['pricing_type'] ?? 'per_package'),
                $durations,
                $priceMap
            );

            // For easy UI use:
            $p['calories_kcal'] = $p['nutrition']['calories_kcal'] ?? null;
        }
        unset($p);

        return view('subscriptions/index', [
            'plans' => $plans,
        ]);
    }

    /**
     * Show single plan details.
     * URL: /subscriptions/{slug}
     */
    public function show(string $slug)
    {
        $slug = trim((string)$slug);

        if (method_exists($this->plans, 'getBySlug')) {
            $plan = $this->plans->getBySlug($slug);
        } else {
            $plan = $this->plans->where('slug', $slug)->first();
        }

        // optionally only allow active plans publicly
        if (!$plan || empty($plan['is_active'])) {
            throw PageNotFoundException::forPageNotFound();
        }

        $planId = (int)$plan['id'];

        // Nutrition + config
        $nutrition = $this->nutrition->getByPlanId($planId);
        $configRow = $this->config->getByPlanId($planId);

        // Decode config JSON safely
        $durations        = $this->safeJsonArray($configRow['duration_options_json'] ?? null);
        $slots            = $this->safeJsonArray($configRow['delivery_slots_json'] ?? null);
        $offDays          = $this->safeJsonArray($configRow['off_days_json'] ?? null);
        $durationPriceMap = $this->safeJsonAssoc($configRow['duration_pricing_json'] ?? null);

        // Compute "starting from" price
        $startingPrice = $this->computeStartingPrice(
            (float)($plan['base_price'] ?? 0),
            (string)($plan['pricing_type'] ?? 'per_package'),
            $durations,
            $durationPriceMap
        );

        // Logistics
        $minOffset = (int)($configRow['min_start_offset_days'] ?? 1);
        $minOffset = max(0, $minOffset);

        return view('subscriptions/show', [
            'plan'             => $plan,
            'nutrition'        => $nutrition ?: [],
            'configRow'        => $configRow ?: [],
            'durations'        => $durations,
            'slots'            => $slots,
            'offDays'          => $offDays,
            'durationPriceMap' => $durationPriceMap,
            'startingPrice'    => $startingPrice,
            'minStartOffset'   => $minOffset,
        ]);
    }

    /**
     * Add subscription to main cart session.
     * URL: POST /subscriptions/add-to-cart
     */
    public function addToCart()
    {
        $request = $this->request;

        $planId   = (int)$request->getPost('plan_id');
        $duration = (int)$request->getPost('duration_days');
        $start    = trim((string)$request->getPost('start_date'));
        $slotKey  = trim((string)$request->getPost('slot_key'));

        $plan = $this->plans->find($planId);
        if (!$plan || empty($plan['is_active'])) {
            return redirect()->back()->with('error', 'Invalid subscription plan.');
        }

        // Config needed for validation + price + rules
        $configRow = $this->config->getByPlanId($planId) ?: [];

        $durationsAllowed = $this->safeJsonArray($configRow['duration_options_json'] ?? null);
        $slots            = $this->safeJsonArray($configRow['delivery_slots_json'] ?? null);
        $offDays          = $this->safeJsonArray($configRow['off_days_json'] ?? null);
        $durationPriceMap = $this->safeJsonAssoc($configRow['duration_pricing_json'] ?? null);

        // 1) Duration validation
        if ($duration <= 0) {
            return redirect()->back()->with('error', 'Please select a valid duration.');
        }
        if (!empty($durationsAllowed) && !in_array($duration, array_map('intval', $durationsAllowed), true)) {
            return redirect()->back()->with('error', 'Please select a valid duration option.');
        }

        // 2) Start date validation + min offset
        if (!$this->isValidYmd($start)) {
            return redirect()->back()->with('error', 'Please select a valid start date.');
        }
        $minOffset = max(0, (int)($configRow['min_start_offset_days'] ?? 1));
        $minDate   = (new \DateTime('today'))->modify('+' . $minOffset . ' day')->format('Y-m-d');
        if ($start < $minDate) {
            return redirect()->back()->with('error', "Start date must be on or after {$minDate}.");
        }

        // 3) Slot validation (if slots configured)
        if (empty($slotKey)) {
            return redirect()->back()->with('error', 'Please select a delivery time slot.');
        }
        if (!empty($slots)) {
            $validSlotKeys = array_values(array_filter(array_map(fn($s) => (string)($s['key'] ?? ''), $slots)));
            if (!in_array($slotKey, $validSlotKeys, true)) {
                return redirect()->back()->with('error', 'Please select a valid delivery slot.');
            }
        }

        // Compute total price
        $totalPrice = $this->computeTotalPrice(
            (float)($plan['base_price'] ?? 0),
            (string)($plan['pricing_type'] ?? 'per_package'),
            $duration,
            $durationPriceMap
        );

        // Compute end date (skipping off days)
        $endDate = $this->computeEndDate($start, $duration, $offDays);

        // Resolve slot label/window
        $slotLabel  = $slotKey;
        $slotWindow = '';
        foreach ($slots as $slot) {
            if (!empty($slot['key']) && (string)$slot['key'] === $slotKey) {
                $slotLabel  = $slot['label']  ?? $slotKey;
                $slotWindow = $slot['window'] ?? '';
                break;
            }
        }

        // Choice mode support (future-friendly – won’t break today)
        $menuMode          = (string)($plan['menu_mode'] ?? 'fixed');
        $choicePerDayLimit = (int)($plan['choice_per_day_limit'] ?? 1);
        $choicePerDayLimit = max(1, $choicePerDayLimit);

        // Cart push
        $session  = session();
        $mainCart = (array)$session->get('cart');

        $rowId = 'sub_' . uniqid('', true);

        $img = $plan['thumbnail_url'] ?? '';
        if (!$img) $img = 'assets/img/placeholder-product.png';

        $meta = [
            'subscription_plan_id'   => $planId,
            'duration_days'          => $duration,
            'start_date'             => $start,
            'end_date'               => $endDate,
            'slot_key'               => $slotKey,
            'slot_label'             => $slotLabel,
            'slot_window'            => $slotWindow,

            // future / choice-based fields:
            'menu_mode'              => $menuMode,
            'choice_per_day_limit'   => $choicePerDayLimit,
        ];

        $mainCart[$rowId] = [
            'product_id'           => null,
            'id'                   => $rowId,
            'row_id'               => $rowId,

            'slug'                 => $plan['slug'] ?? null,
            'name'                 => $plan['title'] ?? 'Subscription Plan',
            'category'             => 'subscription',

            'main_image'           => $img,
            'image'                => $img,

            'price'                => $totalPrice,
            'sale_price'           => null,
            'final_price'          => $totalPrice,

            'qty'                  => 1,
            'row_total'            => $totalPrice,

            'is_subscription'      => 1,
            'subscription_meta'    => $meta,
            'subscription_plan_id' => $planId,
        ];

        $session->set('cart', $mainCart);

        return redirect()->to('/cart')
            ->with('cart_notice', 'Subscription plan added to your cart. Please review and proceed to checkout.');
    }

    // -----------------------------
    // Helpers
    // -----------------------------

    protected function safeJsonArray(?string $json): array
    {
        if (!$json) return [];
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    protected function safeJsonAssoc(?string $json): array
    {
        if (!$json) return [];
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    protected function isValidYmd(string $date): bool
    {
        $dt = \DateTime::createFromFormat('Y-m-d', $date);
        return $dt && $dt->format('Y-m-d') === $date;
    }

    protected function computeStartingPrice(float $basePrice, string $pricingType, array $durations, array $durationPriceMap): float
    {
        $basePrice = max(0, $basePrice);
        $pricingType = $pricingType ?: 'per_package';

        // If no durations configured, "starting" is base price
        if (empty($durations)) {
            return $basePrice;
        }

        $prices = [];
        foreach ($durations as $d) {
            $dInt = (int)$d;
            if ($dInt <= 0) continue;

            if (isset($durationPriceMap[$dInt])) {
                $prices[] = (float)$durationPriceMap[$dInt];
            } else {
                // fallback price logic
                if ($pricingType === 'per_day') {
                    $prices[] = $dInt * $basePrice;
                } else {
                    $prices[] = $basePrice;
                }
            }
        }

        if (empty($prices)) return $basePrice;
        $min = min($prices);
        return max(0, (float)$min);
    }

    protected function computeTotalPrice(float $basePrice, string $pricingType, int $duration, array $durationPriceMap): float
    {
        $basePrice = max(0, $basePrice);
        $pricingType = $pricingType ?: 'per_package';

        if (isset($durationPriceMap[$duration])) {
            return max(0, (float)$durationPriceMap[$duration]);
        }

        if ($pricingType === 'per_day') {
            return max(0, $duration * $basePrice);
        }

        return $basePrice;
    }

    /**
     * Compute end date by skipping off days like ["SUN", "SAT"].
     */
    protected function computeEndDate(string $startDate, int $durationDays, array $offDays): string
    {
        $date = new \DateTime($startDate);
        $daysAdded = 0;

        $offDays = array_map(fn($d) => strtoupper((string)$d), $offDays);

        // duration=1 => end = start
        while ($daysAdded < ($durationDays - 1)) {
            $date->modify('+1 day');
            $dayCode = strtoupper($date->format('D')); // MON, TUE, WED...
            if (!in_array($dayCode, $offDays, true)) {
                $daysAdded++;
            }
        }

        return $date->format('Y-m-d');
    }
}