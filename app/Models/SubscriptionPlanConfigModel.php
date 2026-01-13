<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionPlanConfigModel extends Model
{
    protected $table            = 'subscription_plan_config';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'subscription_plan_id',
        'duration_options_json',
        'delivery_slots_json',
        'off_days_json',
        'duration_pricing_json',
        'postponement_limit',
        'cut_off_hour',
        'min_start_offset_days',
    ];

    public function getByPlanId(int $planId): ?array
    {
        return $this->where('subscription_plan_id', $planId)->first();
    }
}