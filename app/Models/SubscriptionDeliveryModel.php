<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionDeliveryModel extends Model
{
    protected $table            = 'subscription_deliveries';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'subscription_id',
        'subscription_plan_id',
        'user_id',
        'customer_id',
        'delivery_date',
        'base_address_id',
        'override_address_id',
        'base_slot_key',
        'override_slot_key',
        'menu_id',
        'status',
        'notes',
        'is_generated_extension',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Create the initial per-day schedule for a subscription.
     * - $subscription: row from subscriptions table.
     * - $config: row from subscription_plan_config for that plan.
     */
    public function createScheduleForSubscription(array $subscription, array $config): int
    {
        $subscriptionId  = (int) $subscription['id'];
        $planId          = (int) $subscription['subscription_plan_id'];
        $durationDays    = (int) $subscription['duration_days'];
        $defaultSlotKey  = (string) $subscription['default_slot_key'];
        $startDate       = new \DateTime($subscription['start_date']);
        $offDays         = [];

        if (!empty($config['off_days_json'])) {
            $offDays = json_decode($config['off_days_json'], true) ?: [];
        }
        $offDays = array_map('strtoupper', $offDays);

        // We'll generate "durationDays" active delivery dates, skipping off days.
        $deliveries = [];
        $currentDate = clone $startDate;
        $count = 0;

        while ($count < $durationDays) {
            $dayCode = strtoupper($currentDate->format('D')); // MON, TUE...
            if (!in_array($dayCode, $offDays, true)) {
                $deliveries[] = [
                    'subscription_id'       => $subscriptionId,
                    'subscription_plan_id'  => $planId,
                    'user_id'               => $subscription['user_id'] ?? null,
                    'customer_id'           => $subscription['customer_id'] ?? null,
                    'delivery_date'         => $currentDate->format('Y-m-d'),
                    'base_address_id'       => $subscription['base_address_id'] ?? null,
                    'override_address_id'   => null,
                    'base_slot_key'         => $defaultSlotKey,
                    'override_slot_key'     => null,
                    'status'                => 'pending',
                    'notes'                 => null,
                    'is_generated_extension'=> 0,
                ];
                $count++;
            }

            if ($count >= $durationDays) {
                break;
            }

            // Move to next day
            $currentDate->modify('+1 day');
        }

        if (!empty($deliveries)) {
            $this->insertBatch($deliveries);
        }

        // Return last delivery date so caller can update subscriptions.end_date & total_deliveries_planned
        $last = end($deliveries);
        return $last ? $last['delivery_date'] : $subscription['start_date'];
    }

    /**
     * Get deliveries for specific date with optional filters.
     */
    public function getDeliveriesForDate(string $date, ?string $slotKey = null, ?int $planId = null, ?string $status = null)
    {
        $builder = $this->select('subscription_deliveries.*, subscriptions.default_slot_key, subscriptions.total_price, subscriptions.id as subscription_id, subscription_plans.title as plan_title, menus.menu_name, menus.weekday as menu_weekday')
            ->join('subscriptions', 'subscriptions.id = subscription_deliveries.subscription_id', 'left')
            ->join('subscription_plans', 'subscription_plans.id = subscription_deliveries.subscription_plan_id', 'left')
            ->join('menus', 'menus.id = subscription_deliveries.menu_id', 'left')
            ->where('subscription_deliveries.delivery_date', $date);

        if (!empty($slotKey)) {
            $builder->groupStart()
                ->where('subscription_deliveries.base_slot_key', $slotKey)
                ->orWhere('subscription_deliveries.override_slot_key', $slotKey)
                ->groupEnd();
        }

        if (!empty($planId)) {
            $builder->where('subscription_deliveries.subscription_plan_id', $planId);
        }

        if (!empty($status)) {
            $builder->where('subscription_deliveries.status', $status);
        }

        return $builder->orderBy('subscription_deliveries.id', 'ASC')->findAll();
    }
}