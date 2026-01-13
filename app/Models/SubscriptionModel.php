<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table            = 'subscriptions';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'user_id',
        'customer_id',
        'subscription_plan_id',
        'duration_days',
        'start_date',
        'end_date',
        'total_deliveries_planned',
        'postponement_limit',
        'postponement_used',
        'base_address_id',
        'default_slot_key',
        'total_price',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActiveSubscriptions()
    {
        return $this->where('status', 'active')->findAll();
    }
}