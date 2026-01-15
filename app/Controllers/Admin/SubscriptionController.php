<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionDeliveryModel;
use App\Models\SubscriptionModel;
use App\Models\MenuModel;

class SubscriptionController extends AdminBaseController
{
    public function changeDeliveryMenu()
    {
        // Server-side validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'delivery_id' => 'required|integer|greater_than[0]',
            'menu_id'     => 'required|integer|greater_than[0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }

        $deliveryId = (int) $this->request->getPost('delivery_id');
        $menuId     = (int) $this->request->getPost('menu_id');

        $deliveryModel     = new SubscriptionDeliveryModel();
        $subscriptionModel = new SubscriptionModel();
        $menuModel         = new MenuModel();

        // 1. Validate delivery
        $delivery = $deliveryModel->find($deliveryId);
        if (!$delivery) {
            return redirect()->back()->with('error', 'Invalid delivery');
        }

        // 2. Validate subscription (ACTIVE)
        $subscription = $subscriptionModel->find($delivery['subscription_id']);
        if (!$subscription || $subscription['status'] !== 'active') {
            return redirect()->back()
                ->with('error', 'Menu change allowed only for active subscriptions');
        }

        // 3. Validate menu
        $menu = $menuModel->find($menuId);
        if (!$menu || (int)$menu['is_active'] !== 1) {
            return redirect()->back()
                ->with('error', 'Selected menu is inactive or invalid');
        }

        // 4. CORE RULE — SAME WEEKDAY ONLY
        if ($menu['weekday'] !== $delivery['weekday']) {
            return redirect()->back()
                ->with('error', 'Menu can be changed only for the same weekday');
        }

        // 5. Update delivery menu
        $deliveryModel->update($deliveryId, [
            'menu_id' => $menuId
        ]);

        return redirect()->back()
            ->with('success', 'Menu updated successfully');
    }
}
