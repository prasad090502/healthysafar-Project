<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMenuFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('menus', [
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'menu_name'
            ],
            'base_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
                'after' => 'slug'
            ],
            'pricing_type' => [
                'type' => 'ENUM',
                'constraint' => ['per_package', 'per_day', 'per_week', 'per_month'],
                'default' => 'per_package',
                'after' => 'base_price'
            ],
            'menu_mode' => [
                'type' => 'ENUM',
                'constraint' => ['fixed', 'choice'],
                'default' => 'fixed',
                'after' => 'pricing_type'
            ],
            'choice_per_day_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 1,
                'after' => 'menu_mode'
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'choice_per_day_limit'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('menus', [
            'slug',
            'base_price',
            'pricing_type',
            'menu_mode',
            'choice_per_day_limit',
            'sort_order'
        ]);
    }
}
