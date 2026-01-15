<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Models\MenuModel;

class MenuController extends AdminBaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        $data['menus'] = $this->menuModel->findAll();
        return view('admin/menus/index', $data);
    }

    public function edit($id)
    {
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->back()->with('error', 'Menu not found');
        }

        $data['menu'] = $menu;
        return view('admin/menus/edit', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'menu_name' => 'required',
            'weekday' => 'required|in_list[Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }

        $this->menuModel->insert([
            'menu_name'         => $this->request->getPost('menu_name'),
            'weekday'           => $this->request->getPost('weekday'),
            'short_description' => $this->request->getPost('short_description'),
            'long_description'  => $this->request->getPost('long_description'),
            'is_active'         => 1
        ]);

        return redirect()->back()->with('success', 'Menu added');
    }

    public function update($id)
    {
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->back()->with('error', 'Menu not found');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'menu_name' => 'required',
            'weekday' => 'required|in_list[Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->getErrors());
        }

        $this->menuModel->update($id, [
            'menu_name'         => $this->request->getPost('menu_name'),
            'weekday'           => $this->request->getPost('weekday'),
            'short_description' => $this->request->getPost('short_description'),
            'long_description'  => $this->request->getPost('long_description')
        ]);

        return redirect()->back()->with('success', 'Menu updated');
    }

    public function toggleStatus($id)
    {
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->back()->with('error', 'Menu not found');
        }

        $this->menuModel->update($id, [
            'is_active' => $menu['is_active'] ? 0 : 1
        ]);

        return redirect()->back()->with('success', 'Menu status updated');
    }

    public function getMenusByWeekday()
    {
        $weekday = $this->request->getGet('weekday');
        $active = $this->request->getGet('active');

        $query = $this->menuModel;

        if ($weekday) {
            $query = $query->where('weekday', $weekday);
        }

        if ($active !== null) {
            $query = $query->where('is_active', (int)$active);
        }

        $menus = $query->findAll();

        return $this->response->setJSON(['menus' => $menus]);
    }
}
