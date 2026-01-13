<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        $menuItems = [
            // Monday Menu Items
            [
                'menu_id' => 1,
                'item_name' => 'Grilled Chicken Salad',
                'description' => 'Fresh grilled chicken breast with mixed greens, cherry tomatoes, cucumber, and balsamic vinaigrette',
                'price' => 12.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 1,
                'item_name' => 'Quinoa Bowl',
                'description' => 'Nutritious quinoa with roasted vegetables, chickpeas, and tahini dressing',
                'price' => 10.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 1,
                'item_name' => 'Turkey Wrap',
                'description' => 'Whole wheat wrap with turkey, lettuce, tomato, avocado, and mustard',
                'price' => 9.99,
                'is_available' => 1,
            ],

            // Tuesday Menu Items
            [
                'menu_id' => 2,
                'item_name' => 'Salmon Poke Bowl',
                'description' => 'Fresh salmon with sushi rice, avocado, cucumber, and sesame seeds',
                'price' => 15.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 2,
                'item_name' => 'Vegetable Stir Fry',
                'description' => 'Mixed vegetables stir-fried with tofu and brown rice',
                'price' => 11.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 2,
                'item_name' => 'Chicken Caesar Wrap',
                'description' => 'Grilled chicken with romaine lettuce, parmesan, and caesar dressing in a wrap',
                'price' => 10.99,
                'is_available' => 1,
            ],

            // Wednesday Menu Items
            [
                'menu_id' => 3,
                'item_name' => 'Beef Stir Fry',
                'description' => 'Lean beef strips with broccoli, bell peppers, and jasmine rice',
                'price' => 14.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 3,
                'item_name' => 'Mediterranean Salad',
                'description' => 'Feta cheese, olives, cucumber, tomatoes, and olive oil dressing',
                'price' => 12.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 3,
                'item_name' => 'Veggie Burger',
                'description' => 'Plant-based burger with lettuce, tomato, and sweet potato fries',
                'price' => 11.99,
                'is_available' => 1,
            ],

            // Thursday Menu Items
            [
                'menu_id' => 4,
                'item_name' => 'Shrimp Scampi',
                'description' => 'Garlic shrimp with whole wheat pasta and steamed vegetables',
                'price' => 16.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 4,
                'item_name' => 'Falafel Bowl',
                'description' => 'Crispy falafel with hummus, tabbouleh, and pita bread',
                'price' => 12.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 4,
                'item_name' => 'Tuna Salad Sandwich',
                'description' => 'Albacore tuna salad on whole grain bread with lettuce and tomato',
                'price' => 9.99,
                'is_available' => 1,
            ],

            // Friday Menu Items
            [
                'menu_id' => 5,
                'item_name' => 'Grilled Salmon',
                'description' => 'Atlantic salmon fillet with quinoa and roasted asparagus',
                'price' => 17.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 5,
                'item_name' => 'Caprese Salad',
                'description' => 'Fresh mozzarella, tomatoes, basil, and balsamic glaze',
                'price' => 13.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 5,
                'item_name' => 'Chicken Shawarma',
                'description' => 'Marinated chicken with rice, salad, and garlic sauce',
                'price' => 13.99,
                'is_available' => 1,
            ],

            // Saturday Menu Items
            [
                'menu_id' => 6,
                'item_name' => 'Steak Salad',
                'description' => 'Grilled sirloin steak over mixed greens with blue cheese dressing',
                'price' => 18.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 6,
                'item_name' => 'Sushi Rolls',
                'description' => 'Assortment of vegetable and salmon rolls with soy sauce',
                'price' => 15.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 6,
                'item_name' => 'Pasta Primavera',
                'description' => 'Whole wheat pasta with seasonal vegetables and light sauce',
                'price' => 12.99,
                'is_available' => 1,
            ],

            // Sunday Menu Items
            [
                'menu_id' => 7,
                'item_name' => 'Roast Chicken',
                'description' => 'Herb-roasted chicken breast with mashed sweet potatoes and green beans',
                'price' => 15.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 7,
                'item_name' => 'Greek Salad',
                'description' => 'Cucumber, tomatoes, red onion, olives, and feta with olive oil',
                'price' => 11.99,
                'is_available' => 1,
            ],
            [
                'menu_id' => 7,
                'item_name' => 'Lentil Soup Bowl',
                'description' => 'Hearty lentil soup with whole grain bread and side salad',
                'price' => 10.99,
                'is_available' => 1,
            ],
        ];

        $this->db->table('menu_items')->insertBatch($menuItems);
    }
}
