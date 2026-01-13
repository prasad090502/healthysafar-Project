<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionPlanChoiceModel extends Model
{
    protected $table = 'subscription_plan_choices';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'subscription_plan_id',
        'ref_type',
        'ref_id',
        'title',
        'description',
        'image_url',
        'calories_kcal',
        'is_active',
        'sort_order',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}