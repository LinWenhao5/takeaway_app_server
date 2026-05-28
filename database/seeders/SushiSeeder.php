<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Features\Product\Models\Product;
use App\Features\ProductCategory\Models\ProductCategory;
use App\Features\Vat\Models\VatRate;

class SushiSeeder extends Seeder
{
    public function run()
    {
        $foodVatRate = VatRate::updateOrCreate(
            ['name' => 'Food VAT 9%'],
            ['rate' => 9.00]
        );

        $drinkVatRate = VatRate::updateOrCreate(
            ['name' => 'Drink VAT 21%'],
            ['rate' => 21.00]
        );

        $categories = [
            'Sushi Boxes' => [
                [
                    'name' => 'Maki Mix (24 st)',
                    'description' => 'Zalm maki, Tuna maki, Komkommer maki, Kani maki',
                    'price' => 10.50,
                ],
                [
                    'name' => 'Uramaki Mix (16 st)',
                    'description' => 'California masago roll, Salmon sesam roll, Tempura ebi roll, Spicy chicken roll',
                    'price' => 12.50,
                ],
                [
                    'name' => 'Salmon Box (18 st)',
                    'description' => 'Crunch salmon roll, Salmon maki, Salmon nigiri, Sashimi',
                    'price' => 23.95,
                ],
                [
                    'name' => 'Salmon Tuna Box (16 st)',
                    'description' => 'Salmon sesam roll, Tuna spicy roll, Salmon nigiri, Tuna nigiri',
                    'price' => 25.95,
                ],
                [
                    'name' => 'Uramaki Mix Box (32 st)',
                    'description' => 'California masago roll, Salmon sesam roll, Tempura ebi roll, Spicy chicken roll',
                    'price' => 38.00,
                ],
                [
                    'name' => 'Veggie Box (20 st)',
                    'description' => 'Veggie roll, Komkommer maki, Avocado maki, Omelet nigiri, Avocado nigiri',
                    'price' => 17.00,
                ],
                [
                    'name' => 'Fried Crispy Box (18 st)',
                    'description' => 'Kip futomaki, Zalm futomaki, Tuna futomaki',
                    'price' => 21.50,
                ],
                [
                    'name' => 'Salmon/Tuna Mix (16 st)',
                    'description' => 'Zalm nigiri, Tuna nigiri, Sashimi zalm, Sashimi tuna',
                    'price' => 18.95,
                ],
                [
                    'name' => 'Kinder Box (20 st)',
                    'description' => 'Tempura ebi roll, Crispy chicken roll, Komkommer maki, Avocado maki',
                    'price' => 15.00,
                ],
                [
                    'name' => 'Studenten Box (32 st)',
                    'description' => 'Avocado dragon roll, Sake dragon roll',
                    'price' => 18.00,
                ],
            ],
            'Sashimi' => [
                [
                    'name' => 'Zalm Sashimi (6 st)',
                    'description' => 'Salmon sashimi',
                    'price' => 9.00,
                ],
                [
                    'name' => 'Tuna Sashimi (6 st)',
                    'description' => 'Tuna sashimi',
                    'price' => 12.00,
                ],
                [
                    'name' => 'Tataki Zalm Sashimi',
                    'description' => 'Zalm licht geschaafd met saus',
                    'price' => 12.50,
                ],
                [
                    'name' => 'Tataki Tuna Sashimi',
                    'description' => 'Tuna licht geschaafd met saus',
                    'price' => 12.50,
                ],
                [
                    'name' => 'Hotategai Sashimi',
                    'description' => 'Coquilles sashimi',
                    'price' => 12.50,
                ],
            ],
            'Uramaki Roll (8 st)' => [
                [
                    'name' => 'California Roll',
                    'description' => 'Krabstick, avocado, komkommer, mayo, masago',
                    'price' => 10.50,
                ],
                [
                    'name' => 'Crunch California Roll',
                    'description' => 'Krabstick, avocado, komkommer, wagasauce, crunch',
                    'price' => 12.25,
                ],
                [
                    'name' => 'Salmon Sesam Roll',
                    'description' => 'Zalm, avocado, mayo, sesam',
                    'price' => 10.75,
                ],
                [
                    'name' => 'Crunch Salmon Roll',
                    'description' => 'Zalm, avocado, mayo, wagasauce, crunch',
                    'price' => 11.35,
                ],
                [
                    'name' => 'Spicy Tuna Roll',
                    'description' => 'Tuna, spicy tonijn, avocado',
                    'price' => 12.50,
                ],
                [
                    'name' => 'Veggie Roll',
                    'description' => 'Avocado, komkommer, tamago, mayo, sesam',
                    'price' => 8.70,
                ],
                [
                    'name' => 'Spicy Chicken Roll',
                    'description' => 'Fried chicken, komkommer, spicy mayo',
                    'price' => 10.80,
                ],
                [
                    'name' => 'Tempura Ebi Roll',
                    'description' => 'Garnaal, komkommer, mayo, sesam',
                    'price' => 10.80,
                ],
                [
                    'name' => 'Flaming Truffle Beef Roll',
                    'description' => 'Garnaal roll, beef, truffle mayo',
                    'price' => 11.75,
                ],
                [
                    'name' => 'Creamy Salmon Roll',
                    'description' => 'Zalm, avocado, creamy cheese',
                    'price' => 11.00,
                ],
                [
                    'name' => 'Avocado Dragon Roll',
                    'description' => 'Fried shrimp, avocado, mayo',
                    'price' => 13.30,
                ],
                [
                    'name' => 'Flambe Hotategai Roll',
                    'description' => 'Garnaal roll met geflambeerde coquilles, mayo, gebakken uien, masago',
                    'price' => 11.75,
                ],
            ],
            'Temaki (1 stuk)' => [
                [
                    'name' => 'California Temaki',
                    'description' => 'California hand roll',
                    'price' => 3.95,
                ],
                [
                    'name' => 'Zalm Temaki',
                    'description' => 'Salmon hand roll',
                    'price' => 4.10,
                ],
                [
                    'name' => 'Tuna Temaki',
                    'description' => 'Tuna hand roll',
                    'price' => 4.70,
                ],
                [
                    'name' => 'Spicy Tuna Temaki',
                    'description' => 'Spicy tuna hand roll',
                    'price' => 4.90,
                ],
                [
                    'name' => 'Tempura Temaki',
                    'description' => 'Tempura hand roll',
                    'price' => 4.50,
                ],
                [
                    'name' => 'Unagi Temaki',
                    'description' => 'Eel hand roll',
                    'price' => 4.70,
                ],
                [
                    'name' => 'Chicken Temaki',
                    'description' => 'Chicken hand roll',
                    'price' => 4.50,
                ],
                [
                    'name' => 'Veggi Temaki',
                    'description' => 'Vegetarian hand roll',
                    'price' => 3.75,
                ],
            ],
            'Salade' => [
                [
                    'name' => 'Mini Springroll (1 st)',
                    'description' => 'Small spring roll',
                    'price' => 3.25,
                ],
                [
                    'name' => 'Yakitori Skewer (4 st)',
                    'description' => 'Grilled chicken skewers',
                    'price' => 6.00,
                ],
                [
                    'name' => 'Tori Karaage (1 st)',
                    'description' => 'Japanese fried chicken',
                    'price' => 4.95,
                ],
                [
                    'name' => 'Gyoza (1 st)',
                    'description' => 'Japanese dumpling',
                    'price' => 4.95,
                ],
                [
                    'name' => 'Shrimp Tempura (4 st)',
                    'description' => 'Crispy shrimp tempura',
                    'price' => 6.00,
                ],
                [
                    'name' => 'Edamame',
                    'description' => 'Boiled green soybeans',
                    'price' => 4.00,
                ],
                [
                    'name' => 'Chuka Wakame',
                    'description' => 'Seaweed salad',
                    'price' => 3.50,
                ],
            ],
            'Poke Bowls' => [
                [
                    'name' => 'Salmon Bowl',
                    'description' => 'Salmon poke bowl',
                    'price' => 13.00,
                ],
                [
                    'name' => 'Tuna Bowl',
                    'description' => 'Tuna poke bowl',
                    'price' => 14.00,
                ],
                [
                    'name' => 'Spicy Salmon Bowl',
                    'description' => 'Spicy salmon poke bowl',
                    'price' => 13.75,
                ],
                [
                    'name' => 'Spicy Tuna Bowl',
                    'description' => 'Spicy tuna poke bowl',
                    'price' => 14.75,
                ],
                [
                    'name' => 'Farmen Bowl',
                    'description' => 'Farmen poke bowl',
                    'price' => 13.00,
                ],
                [
                    'name' => 'Korage Bowl',
                    'description' => 'Korage poke bowl',
                    'price' => 13.00,
                ],
                [
                    'name' => 'Krab Bowl',
                    'description' => 'Crab poke bowl',
                    'price' => 13.00,
                ],
                [
                    'name' => 'Paling Bowl',
                    'description' => 'Eel poke bowl',
                    'price' => 13.50,
                ],
                [
                    'name' => 'Vega Bowl',
                    'description' => 'Vegetarian poke bowl',
                    'price' => 12.00,
                ],
            ],
            'Drinks' => [
                [
                    'name' => 'Coca Cola',
                    'description' => 'Soft drink',
                    'price' => 2.20,
                ],
            ],
        ];

        foreach ($categories as $categoryName => $products) {
            $category = ProductCategory::firstOrCreate(
                ['name' => $categoryName]
            );

            $vatRate = $categoryName === 'Drinks' ? $drinkVatRate : $foodVatRate;

            foreach ($products as $sushi) {
                Product::updateOrCreate(
                    [
                        'name' => $sushi['name'],
                        'product_category_id' => $category->id,
                    ],
                    [
                        'description' => $sushi['description'],
                        'price' => $sushi['price'],
                        'vat_rate_id' => $vatRate->id,
                    ]
                );
            }
        }
    }
}