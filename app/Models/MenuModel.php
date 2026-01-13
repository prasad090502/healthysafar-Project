<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'menu_name',
        'weekday',
        'short_description',
        'long_description',
        'is_active'
    ];

    protected $useTimestamps = true;

    /**
     * Get active menus for a specific weekday
     */
    public function getActiveMenusByWeekday(string $weekday): array
    {
        return $this->where('weekday', $weekday)
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * Get all active menus
     */
    public function getActiveMenus(): array
    {
        return $this->where('is_active', 1)->findAll();
    }
}
