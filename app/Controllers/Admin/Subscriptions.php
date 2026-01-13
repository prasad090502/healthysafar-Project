<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionDeliveryModel;

class Subscriptions extends BaseController
{
    protected SubscriptionModel $subscriptions;
    protected SubscriptionPlanModel $plans;
    protected SubscriptionDeliveryModel $deliveries;

    protected int $perPage = 10;

    public function __construct()
    {
        $this->subscriptions = new SubscriptionModel();
        $this->plans         = new SubscriptionPlanModel();
        $this->deliveries    = new SubscriptionDeliveryModel();
    }

    /**
     * List all subscriptions with basic filters.
     * URL: /admin/subscriptions
     */
    public function index()
    {
        $request = $this->request;

        $q        = trim((string)$request->getGet('q'));
        $status   = $request->getGet('status');
        $planId   = $request->getGet('plan_id');
        $dateFrom = $request->getGet('date_from');
        $dateTo   = $request->getGet('date_to');

        $builder = $this->subscriptions
            ->select('subscriptions.*, subscription_plans.title AS plan_title')
            ->join('subscription_plans', 'subscription_plans.id = subscriptions.subscription_plan_id', 'left')
            ->orderBy('subscriptions.created_at', 'DESC');

        if ($q !== '') {
            // Search in numeric IDs (subscription id / user_id / customer_id)
            $builder->groupStart()
                ->like('subscriptions.id', $q)
                ->orLike('subscriptions.user_id', $q)
                ->orLike('subscriptions.customer_id', $q)
                ->groupEnd();
        }

        if (!empty($status)) {
            $builder->where('subscriptions.status', $status);
        }

        if (!empty($planId)) {
            $builder->where('subscriptions.subscription_plan_id', (int)$planId);
        }

        if (!empty($dateFrom)) {
            $builder->where('subscriptions.start_date >=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $builder->where('subscriptions.start_date <=', $dateTo);
        }

        $subs = $builder->paginate($this->perPage);

        // Fetch all plans for filter dropdown
        $plans = $this->plans->where('is_active', 1)->orderBy('title', 'ASC')->findAll();

        // Delivered count per subscription
        $deliveredCounts = [];
        if (!empty($subs)) {
            $ids = array_column($subs, 'id');
            if (!empty($ids)) {
                $rows = $this->deliveries
                    ->select('subscription_id, COUNT(*) AS delivered_count')
                    ->whereIn('subscription_id', $ids)
                    ->where('status', 'delivered')
                    ->groupBy('subscription_id')
                    ->findAll();

                foreach ($rows as $r) {
                    $deliveredCounts[(int)$r['subscription_id']] = (int)$r['delivered_count'];
                }
            }
        }

        return view('admin/subscriptions/index', [
            'subscriptions'   => $subs,
            'pager'           => $this->subscriptions->pager,
            'plans'           => $plans,
            'searchTerm'      => $q,
            'selectedStatus'  => $status,
            'selectedPlanId'  => $planId,
            'dateFrom'        => $dateFrom,
            'dateTo'          => $dateTo,
            'deliveredCounts' => $deliveredCounts,
        ]);
    }
}