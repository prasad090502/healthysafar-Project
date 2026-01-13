<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionDeliveryModel extends Model
{
    protected $table = 'subscription_deliveries';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'subscription_id',
        'delivery_date',
        'weekday',
        'menu_id',
        'status'
    ];
}
