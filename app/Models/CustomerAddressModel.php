<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerAddressModel extends Model
{
    protected $table         = 'customer_addresses';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'customer_id', 'name', 'phone', 'alternate_phone',
        'address_line1', 'address_line2', 'landmark',
        'city', 'state', 'pincode', 'country',
        'address_type', 'is_default', 'latitude', 'longitude',
    ];
}