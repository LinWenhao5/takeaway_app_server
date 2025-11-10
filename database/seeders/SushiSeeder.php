<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Features\Product\Models\Product;
use App\Features\ProductCategory\Models\ProductCategory;

class SushiSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Nigiri' => [
                [
                    'name' => 'Salmon Sushi',
                    'description' => 'Fresh salmon on vinegared rice, classic and delicious.',
                    'price' => 18.00,
                ],
                [
                    'name' => 'Tuna Sushi',
                    'description' => 'Premium tuna with a melt-in-your-mouth texture.',
                    'price' => 20.00,
                ],
                [
                    'name' => 'Eel Sushi',
                    'description' => 'Grilled eel with special sauce, rich and flavorful.',
                    'price' => 22.00,
                ],
            ],
            'Roll' => [
                [
                    'name' => 'Cucumber Roll',
                    'description' => 'Refreshing cucumber roll, perfect for vegetarians.',
                    'price' => 10.00,
                ],
            ],
            'Tamago' => [
                [
                    'name' => 'Tamago Sushi',
                    'description' => 'Sweet Japanese omelette on sushi rice.',
                    'price' => 12.00,
                ],
            ],
        ];

        foreach ($categories as $categoryName => $products) {
            $category = ProductCategory::firstOrCreate(
                ['name' => $categoryName]
            );

            foreach ($products as $sushi) {
                Product::firstOrCreate(
                    [
                        'name' => $sushi['name'],
                        'product_category_id' => $category->id,
                    ],
                    [
                        'description' => $sushi['description'],
                        'price' => $sushi['price'],
                    ]
                );
            }
        }
    }
}