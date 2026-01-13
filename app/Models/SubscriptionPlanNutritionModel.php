<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionPlanNutritionModel extends Model
{
    protected $table            = 'subscription_plan_nutrition';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'subscription_plan_id',
        'calories_kcal',
        'protein_g',
        'carbs_g',
        'fats_g',
        'fibre_g',
        'sugar_g',
        'sodium_mg',
        'notes',
    ];

    public function getByPlanId(int $planId): ?array
    {
        return $this->where('subscription_plan_id', $planId)->first();
    }
}