<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuItemModel;
use App\Models\MenuModel;

class MenuItemController extends BaseController
{
    protected $menuItemModel;
    protected $menuModel;

    public function __construct()
    {
        $this->menuItemModel = new MenuItemModel();
        $this->menuModel = new MenuModel();
    }

    /**
     * Display menu items for a specific menu
     */
    public function index($menuId = null)
    {
        if ($menuId === null) {
            return redirect()->to(site_url('admin/menus'))->with('error', 'Menu ID is required');
        }

        $menu = $this->menuModel->find($menuId);
        if (!$menu) {
            return redirect()->to(site_url('admin/menus'))->with('error', 'Menu not found');
        }

        $data = [
            'title' => 'Menu Items - ' . $menu['menu_name'],
            'menu' => $menu,
            'menuItems' => $this->menuItemModel->getItemsByMenu($menuId),
        ];

        return view('admin/menu_items/index', $data);
    }

    /**
     * Show create form
     */
    public function create($menuId)
    {
        $menu = $this->menuModel->find($menuId);
        if (!$menu) {
            return redirect()->to(site_url('admin/menus'))->with('error', 'Menu not found');
        }

        $data = [
            'title' => 'Add Menu Item',
            'menu' => $menu,
        ];

        return view('admin/menu_items/create', $data);
    }

    /**
     * Store new menu item
     */
    public function store()
    {
        $rules = [
            'menu_id' => 'required|integer',
            'item_name' => 'required|string|max_length[255]',
            'description' => 'permit_empty|string',
            'price' => 'required|decimal',
            'is_available' => 'required|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'menu_id' => $this->request->getPost('menu_id'),
            'item_name' => $this->request->getPost('item_name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'is_available' => $this->request->getPost('is_available'),
        ];

        if ($this->menuItemModel->insert($data)) {
            return redirect()->to(site_url('admin/menu-items/' . $data['menu_id']))->with('success', 'Menu item added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add menu item');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $menuItem = $this->menuItemModel->find($id);
        if (!$menuItem) {
            return redirect()->to(site_url('admin/menus'))->with('error', 'Menu item not found');
        }

        $menu = $this->menuModel->find($menuItem['menu_id']);

        $data = [
            'title' => 'Edit Menu Item',
            'menuItem' => $menuItem,
            'menu' => $menu,
        ];

        return view('admin/menu_items/edit', $data);
    }

    /**
     * Update menu item
     */
    public function update($id)
    {
        $rules = [
            'item_name' => 'required|string|max_length[255]',
            'description' => 'permit_empty|string',
            'price' => 'required|decimal',
            'is_available' => 'required|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'item_name' => $this->request->getPost('item_name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'is_available' => $this->request->getPost('is_available'),
        ];

        if ($this->menuItemModel->update($id, $data)) {
            $menuItem = $this->menuItemModel->find($id);
            return redirect()->to(site_url('admin/menu-items/' . $menuItem['menu_id']))->with('success', 'Menu item updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update menu item');
    }

    /**
     * Delete menu item
     */
    public function delete($id)
    {
        $menuItem = $this->menuItemModel->find($id);
        if (!$menuItem) {
            return redirect()->to(site_url('admin/menus'))->with('error', 'Menu item not found');
        }

        if ($this->menuItemModel->delete($id)) {
            return redirect()->to(site_url('admin/menu-items/' . $menuItem['menu_id']))->with('success', 'Menu item deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete menu item');
    }

    /**
     * Toggle availability
     */
    public function toggleAvailability($id)
    {
        $menuItem = $this->menuItemModel->find($id);
        if (!$menuItem) {
            return $this->response->setJSON(['success' => false, 'message' => 'Menu item not found']);
        }

        $newStatus = $menuItem['is_available'] ? 0 : 1;
        if ($this->menuItemModel->update($id, ['is_available' => $newStatus])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update availability']);
    }
}
