<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionDeliveryModel;
use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionPlanConfigModel;
use App\Models\SubscriptionModel;
use App\Models\MenuModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class SubscriptionDeliveries extends AdminBaseController
{
    protected SubscriptionDeliveryModel $deliveries;
    protected SubscriptionPlanModel $plans;
    protected SubscriptionPlanConfigModel $configs;
    protected SubscriptionModel $subscriptions;
    protected MenuModel $menus;

    public function __construct()
    {
        $this->deliveries    = new SubscriptionDeliveryModel();
        $this->plans         = new SubscriptionPlanModel();
        $this->configs       = new SubscriptionPlanConfigModel();
        $this->subscriptions = new SubscriptionModel();
        $this->menus         = new MenuModel();
    }

    /**
     * Deliveries dashboard
     * URL: /admin/subscription-deliveries
     */
    public function index()
    {
        $request = $this->request;

        $date   = $request->getGet('date') ?: date('Y-m-d');
        $slot   = trim((string)$request->getGet('slot'));
        $planId = $request->getGet('plan_id');
        $status = trim((string)$request->getGet('status'));

        $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$dateObj) {
            $date = date('Y-m-d');
        }

        // Active plans for filter
        $plans = $this->plans->where('is_active', 1)->orderBy('title', 'ASC')->findAll();

        /**
         * IMPORTANT:
         * Your model method should return deliveries with:
         * - plan_title (from subscription_plans)
         * - selected_menu_item_title (from subscription_menu_items) [optional join]
         * - selected_plan_title (from subscription_plans as selected) [optional join]
         *
         * For now we call your existing method.
         */
        $deliveries = $this->deliveries->getDeliveriesForDate(
            $date,
            $slot !== '' ? $slot : null,
            $planId ? (int)$planId : null,
            $status !== '' ? $status : null
        );

        // Slot label/window map by plan_id
        $slotMap = $this->buildSlotMapForDeliveries($deliveries);

        return view('admin/subscription_deliveries/index', [
            'selectedDate'    => $date,
            'selectedSlot'    => $slot,
            'selectedPlanId'  => $planId,
            'selectedStatus'  => $status,
            'plans'           => $plans,
            'deliveries'      => $deliveries,
            'slotMap'         => $slotMap,
        ]);
    }

    /**
     * POST /admin/subscription-deliveries/{id}/status
     */
    public function updateStatus(int $id)
    {
        $delivery = $this->deliveries->find($id);
        if (!$delivery) {
            throw PageNotFoundException::forPageNotFound();
        }

        $status  = (string)$this->request->getPost('status');
        $allowed = ['pending', 'out_for_delivery', 'delivered', 'skipped', 'cancelled'];

        if (!in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Invalid status selected.');
        }

        $this->deliveries->update($id, ['status' => $status]);

        return redirect()->back()->with('success', 'Delivery status updated.');
    }

    /**
     * [plan_id][slot_key] => ['label'=>..., 'window'=>...]
     */
    protected function buildSlotMapForDeliveries(array $deliveries): array
    {
        $planIds = [];
        foreach ($deliveries as $d) {
            if (!empty($d['subscription_plan_id'])) {
                $planIds[] = (int)$d['subscription_plan_id'];
            }
        }
        $planIds = array_values(array_unique(array_filter($planIds)));

        if (empty($planIds)) {
            return [];
        }

        $configs = $this->configs->whereIn('subscription_plan_id', $planIds)->findAll();

        $map = [];
        foreach ($configs as $c) {
            $pid = (int)$c['subscription_plan_id'];

            $slotsJson = $c['delivery_slots_json'] ?? '[]';
            $slots = json_decode($slotsJson, true) ?: [];

            foreach ($slots as $slot) {
                if (!empty($slot['key'])) {
                    $key = (string)$slot['key'];
                    $map[$pid][$key] = [
                        'label'  => $slot['label'] ?? $key,
                        'window' => $slot['window'] ?? '',
                    ];
                }
            }
        }

        return $map;
    }
}