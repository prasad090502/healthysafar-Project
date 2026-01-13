<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Razorpay extends BaseConfig
{
    // Later these will be loaded from DB/settings
    public string $keyId     = 'rzp_live_RmnF7vedFkRwrF';   
    public string $keySecret = 'F1qkFkzFvsaOgoQRxTS3yA2b';  

    // Currency for your orders
    public string $currency  = 'INR';
}