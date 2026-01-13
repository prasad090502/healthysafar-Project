<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionPlanConfigModel;

class Home extends BaseController
{
    public function index()
    {
        // -----------------------------
        // 1) PRODUCTS (your existing logic)
        // -----------------------------
        $productModel = new ProductModel();

        $rawProducts = $productModel->getHomeProducts(12);

        $products = array_map(function (array $p) {
            $price     = (float)($p['price'] ?? 0);
            $salePrice = (float)($p['sale_price'] ?? 0);

            if ($salePrice > 0 && $salePrice < $price) {
                $p['final_price'] = $salePrice;
            } else {
                $p['final_price'] = $price;
                $p['sale_price']  = null;
            }

            $category = strtolower(trim((string)($p['category'] ?? '')));
            $tags     = strtolower((string)($p['tags'] ?? ''));

            $catClass = 'cat1'; // default Fruits

            if (str_contains($category, 'vegetable') || str_contains($category, 'veggies')) {
                $catClass = 'cat2';
            } elseif (str_contains($category, 'millet') || str_contains($category, 'grain')) {
                $catClass = 'cat3';
            } elseif (str_contains($category, 'juice') || str_contains($tags, 'juice')) {
                $catClass = 'cat4';
            } elseif (str_contains($category, 'salad')) {
                $catClass = 'cat5';
            } elseif (str_contains($category, 'fruit')) {
                $catClass = 'cat1';
            }

            $p['cat_class'] = $catClass;

            $img = trim((string)($p['main_image'] ?? ''));
            if ($img === '' || strtolower($img) === 'null') {
                $p['main_image'] = 'assets/img/product/product_1_1.jpg';
            }

            $p['average_rating'] = $p['average_rating'] ?? 5.0;
            $p['rating_count']   = $p['rating_count']   ?? 1;
            $p['review_count']   = $p['review_count']   ?? $p['rating_count'];

            return $p;
        }, $rawProducts);

        // -----------------------------
        // 2) CART COUNTS (your existing logic)
        // -----------------------------
        $session = session();
        $cart    = (array) $session->get('cart');
        $cartCounts = [];
        $cartCount  = 0;

        foreach ($cart as $productId => $line) {
            $qty = (int)($line['qty'] ?? 0);
            if ($qty < 0) $qty = 0;

            // Note: subscription rows in your cart use rowId like "sub_xxx"
            // so they won't be counted here (which is OK for product cards).
            if (is_numeric($productId)) {
                $cartCounts[(int)$productId] = $qty;
            }

            $cartCount += $qty;
        }

        // -----------------------------
        // 3) SUBSCRIPTION PLANS (NEW)
        // -----------------------------
        $planModel   = new SubscriptionPlanModel();
        $configModel = new SubscriptionPlanConfigModel();

        // Make sure this method returns only active plans
        $plans = $planModel->getActivePlans() ?? [];

        // Compute "starting from" price for each plan
        foreach ($plans as &$plan) {
            $planId = (int)($plan['id'] ?? 0);

            $basePrice = (float)($plan['base_price'] ?? 0);
            $startingPrice = $basePrice;

            $configRow = $planId ? $configModel->getByPlanId($planId) : null;

            $durations        = [];
            $durationPriceMap = [];

            if (!empty($configRow['duration_options_json'])) {
                $durations = json_decode($configRow['duration_options_json'], true) ?: [];
            }

            if (!empty($configRow['duration_pricing_json'])) {
                $durationPriceMap = json_decode($configRow['duration_pricing_json'], true) ?: [];
            }

            // If duration options exist, compute minimum possible price
            if (!empty($durations)) {
                $prices = [];

                foreach ($durations as $d) {
                    $dInt = (int) $d;
                    if ($dInt <= 0) continue;

                    if (isset($durationPriceMap[$dInt])) {
                        $prices[] = (float) $durationPriceMap[$dInt];
                    } elseif (($plan['pricing_type'] ?? '') === 'per_day') {
                        $prices[] = $dInt * $basePrice;
                    } else {
                        $prices[] = $basePrice;
                    }
                }

                if (!empty($prices)) {
                    $startingPrice = min($prices);
                }
            }

            $plan['starting_from_price'] = $startingPrice;

            // Optional safety defaults used by your UI
            $plan['thumbnail_url'] = $plan['thumbnail_url'] ?? '';
            $plan['short_description'] = $plan['short_description'] ?? '';
            $plan['tagline'] = $plan['tagline'] ?? '';
            $plan['slug'] = $plan['slug'] ?? '';
        }
        unset($plan);

        // -----------------------------
        // 4) PASS EVERYTHING TO HOME VIEW
        // -----------------------------
        return view('home/index', [
            'products'   => $products,
            'cartCounts' => $cartCounts,
            'cartCount'  => $cartCount,

            // ✅ IMPORTANT: this is why subscriptions were showing 0 earlier
            'plans'      => $plans,
        ]);
    }
}