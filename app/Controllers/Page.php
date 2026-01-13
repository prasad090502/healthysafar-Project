<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Page extends BaseController
{
    public function about()
    {
        return view('pages/about');
    }

    public function contacts()
    {
        return view('pages/contacts');
    }

    // --- Legal pages ---

    public function privacy()
    {
        // app/Views/legal/privacy.php
        return view('legal/privacy');
    }

    public function refund()
    {
        // app/Views/legal/refund.php
        return view('legal/refund');
    }

    public function shipping()
    {
        // app/Views/legal/shipping.php
        return view('legal/shipping');
    }

    public function terms()
    {
        // app/Views/legal/terms.php
        return view('legal/terms');
    }
}