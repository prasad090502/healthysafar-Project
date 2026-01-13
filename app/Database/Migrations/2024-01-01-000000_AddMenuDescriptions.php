<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMenuDescriptions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('menus', [
            'short_description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'menu_name'
            ],
            'long_description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'short_description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('menus', 'short_description');
        $this->forge->dropColumn('menus', 'long_description');
    }
}
