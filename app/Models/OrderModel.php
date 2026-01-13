<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'customer_id', 'order_number', 'status', 'payment_status',
        'payment_method', 'subtotal', 'tax_amount', 'shipping_amount',
        'discount_amount', 'grand_total', 'currency',
        'shipping_address_id', 'billing_address_id', 'notes',
    ];
}