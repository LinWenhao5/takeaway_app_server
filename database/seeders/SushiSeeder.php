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

        $categories = [
            'Sashimi' => [
                ['name' => 'Zalm Sashimi', 'description' => '6 stuks verse zalm', 'price' => 9.00],
                ['name' => 'Tuna Sashimi', 'description' => '6 stuks verse tonijn', 'price' => 12.00],
                ['name' => 'Zalm & Tuna Duo', 'description' => '3 Zalm Sashimi, 3 Tuna Sashimi', 'price' => 10.00],
                ['name' => 'Tataki Zalm Sashimi', 'description' => 'Licht geschroeide verse zalm', 'price' => 10.00],
                ['name' => 'Tataki Tuna Sashimi', 'description' => 'Licht geschroeide verse tonijn', 'price' => 13.00],
                ['name' => 'Tataki hotategai Sashimi', 'description' => 'Licht geschroeide verse coquilles', 'price' => 13.00],
            ],
            'Sushi Boxes' => [
                ['name' => 'Maki Mix (24st)', 'description' => '8 Zalm Maki, 8 Tuna Maki, 8 Komkommer Maki', 'price' => 10.50],
                ['name' => 'Veggie Box (16st)', 'description' => '4 Veggie Roll, 8 Komkommer Maki, 2 Avocado Nigiri, 2 Omelet Nigiri', 'price' => 13.50],
                ['name' => 'Crispy & Flamed Mix (12st)', 'description' => 'Spicy Chicken Roll, 4 Tempura Ebi Roll, 4 Flaming Zalm Nigiri', 'price' => 16.95],
                ['name' => 'Zalm & Tuna Lover Mix (12st)', 'description' => '3 Zalm Nigiri, 3 Tuna Nigiri, 3 Zalm Sashimi, 3 Tuna Sashimi', 'price' => 18.95],
                ['name' => 'Zalm Box (24st)', 'description' => '8 Crunch Zalm Roll, 8 Zalm Maki, 4 Zalm Nigiri, 4 Zalm Sashimi', 'price' => 27.50],
                ['name' => 'Uramaki Box (32st)', 'description' => '8 California Roll, 8 Zalm Avocado Roll, 8 Tempura Ebi Roll, 8 Spicy Chicken Roll', 'price' => 38.00],
                ['name' => 'Flaming Sushi Box (24st)', 'description' => '8 Softshell Zalm Roll, 8 Beef Roll, 8 Flaming Hotategai Roll', 'price' => 38.95],
                ['name' => 'Party Sushi Box (40st)', 'description' => '8 Avocado Dragon Roll, 8 Flaming Truffle Beef, 8 Crunch Zalm Roll, 8 Spicy Tuna Roll, 8 California Roll', 'price' => 58.50],
                ['name' => 'Family Box (64st)', 'description' => '4 Zalm, 4 Tuna, 4 Unagi, 4 Ebi Nigiri, mix van Maki, California, Zalm Avocado, Tempura Ebi en Spicy Chicken Roll', 'price' => 68.50],
                ['name' => 'Family Box Deluxe (80st)', 'description' => '8 Soft Shell Zalm, 8 Crunch Zalm, 8 Flaming Truffle Beef, 8 Avocado Dragon, 6 Zalm & 6 Tuna Sashimi, 4 Spicy Tuna Taart, 16 Zalm & 16 Komkommer Maki', 'price' => 88.95],
            ],
            'Uramaki Roll' => [
                ['name' => 'California Roll', 'description' => 'Krab, Avocado, Komkommer, Tobiko, Mayo', 'price' => 10.50],
                ['name' => 'Crunch California Roll', 'description' => 'Krab, Avocado, Komkommer, Unagisaus, Crunch, Mayo', 'price' => 10.75],
                ['name' => 'Zalm Avocado Roll', 'description' => 'Avocado, Zalm, Sesam, Mayo', 'price' => 12.00],
                ['name' => 'Crunch Zalm Roll', 'description' => 'Avocado, Zalm, Mayo, Unagisaus, Crunch', 'price' => 12.25],
                ['name' => 'Tuna Roll', 'description' => 'Avocado, Tuna, Sesam, Mayo', 'price' => 12.00],
                ['name' => 'Spicy Tuna Roll', 'description' => 'Avocado, Spicy Tuna', 'price' => 12.75],
                ['name' => 'Spicy Chicken Roll', 'description' => 'Komkommer, Fried Chicken, Spicy Mayo', 'price' => 11.50],
                ['name' => 'Tempura Ebi Roll', 'description' => 'Garnaal, Komkommer, Mayo, Sesam', 'price' => 11.50],
                ['name' => 'Creamy Zalm Roll', 'description' => 'Avocado, Roomkaas, Zalm, Sesam', 'price' => 13.00],
                ['name' => 'Zalm Salad Roll', 'description' => 'Gegaarde Zalm, Mayonaise', 'price' => 10.50],
                ['name' => 'Veggie Roll', 'description' => 'Avocado, Komkommer, Omelet, Wakame, Mayo, Sesam', 'price' => 10.50],
            ],
            'Special Roll (8st)' => [
                ['name' => 'Flaming Truffle Beef', 'description' => 'Garnalen Roll Met Beef, Truffel, Yakitori Saus', 'price' => 15.75],
                ['name' => 'Beef Roll', 'description' => 'Veggie Roll Met Beef, Yakitori Saus', 'price' => 14.00],
                ['name' => 'Avocado Dragon Roll', 'description' => 'Garnalen Roll, Toppet Avocado, Mayo, Zalmforeleitjes', 'price' => 15.50],
                ['name' => 'Flaming Hotategai Roll', 'description' => 'Garnalen Roll Met Geflambeerde Coquilles, Tobiko', 'price' => 15.75],
                ['name' => 'Unagi Dragon Roll', 'description' => 'Garnalen Roll, Paling, Unagi Saus, Sesam', 'price' => 15.75],
                ['name' => 'Softshell Zalm Roll', 'description' => 'Garnalen Roll, Flaming Zalm, Unagisaus', 'price' => 15.75],
                ['name' => 'Rainbow Roll', 'description' => 'California Roll, Toppet Zalm, Tuna, Avocado', 'price' => 16.50],
                ['name' => 'Zalm & Tuna Roll', 'description' => 'Zalm Avocado Roll, Toppet Tuna, Mayo, Unagisaus', 'price' => 16.50],
            ],
            'Nigiri (2st)' => [
                ['name' => 'Zalm Nigiri', 'description' => 'Verse zalm op rijst', 'price' => 4.20],
                ['name' => 'Flaming Zalm Nigiri', 'description' => 'Geflambeerde zalm op rijst', 'price' => 4.50],
                ['name' => 'Tuna Nigiri', 'description' => 'Verse tonijn op rijst', 'price' => 4.20],
                ['name' => 'Flaming Tuna Nigiri', 'description' => 'Geflambeerde tonijn op rijst', 'price' => 4.50],
                ['name' => 'Flaming Hotategai Nigiri', 'description' => 'Geflambeerde coquille op rijst', 'price' => 4.95],
                ['name' => 'Kani Nigiri', 'description' => 'Krabstick op rijst', 'price' => 4.00],
                ['name' => 'Ebi Nigiri', 'description' => 'Garnaal op rijst', 'price' => 4.00],
                ['name' => 'Omelet Nigiri', 'description' => 'Japanse omelet op rijst', 'price' => 4.00],
                ['name' => 'Inari Nigiri', 'description' => 'Tofu-zakje met rijst', 'price' => 4.00],
                ['name' => 'Avocado Nigiri', 'description' => 'Avocado op rijst', 'price' => 3.75],
                ['name' => 'Unagi Nigiri', 'description' => 'Gegrilde paling op rijst', 'price' => 4.50],
            ],
            'Gunkan (2st)' => [
                ['name' => 'Ikura', 'description' => 'Zalmforeleitjes', 'price' => 5.80],
                ['name' => 'Spicy Tuna Inari', 'description' => 'Tofu-zakje met pittige tonijn', 'price' => 5.80],
                ['name' => 'Tobiko', 'description' => 'Vliegende vis-eitjes', 'price' => 4.00],
                ['name' => 'Wakame', 'description' => 'Zeewiersalade', 'price' => 4.00],
                ['name' => 'Spicy Tuna Tartaar', 'description' => '4 stuks tartaar van pittige tonijn', 'price' => 11.50],
            ],
            'Maki (8st)' => [
                ['name' => 'Komkommer Maki', 'description' => 'Maki met komkommer', 'price' => 4.00],
                ['name' => 'Avocado Maki', 'description' => 'Maki met avocado', 'price' => 4.00],
                ['name' => 'Omelet Maki', 'description' => 'Maki met Japanse omelet', 'price' => 4.00],
                ['name' => 'Kani Maki', 'description' => 'Maki met krab', 'price' => 4.00],
                ['name' => 'Zalm Maki', 'description' => 'Maki met zalm', 'price' => 5.00],
                ['name' => 'Tuna Maki', 'description' => 'Maki met tonijn', 'price' => 5.00],
                ['name' => 'Zalm Avocado Maki', 'description' => 'Maki met zalm en avocado', 'price' => 5.00],
            ],
            'Temaki (1st)' => [
                ['name' => 'Zalm Temaki', 'description' => 'Handroll met zalm', 'price' => 4.50],
                ['name' => 'Tuna Temaki', 'description' => 'Handroll met tonijn', 'price' => 4.70],
                ['name' => 'California Temaki', 'description' => 'Handroll met krab en avocado', 'price' => 4.00],
                ['name' => 'Veggie Temaki', 'description' => 'Handroll met groenten', 'price' => 3.95],
                ['name' => 'Unagi Temaki', 'description' => 'Handroll met paling', 'price' => 4.50],
                ['name' => 'Tempura Temaki', 'description' => 'Handroll met gefrituurde garnaal', 'price' => 4.50],
                ['name' => 'Chicken Temaki', 'description' => 'Handroll met kip', 'price' => 4.50],
                ['name' => 'Spicy Tuna Temaki', 'description' => 'Handroll met pittige tonijn', 'price' => 4.90],
            ],
            'Futomaki (5st)' => [
                ['name' => 'Veggie Futomaki', 'description' => 'Avocado, Komkommer, Wakame, Tofu, Tamago, Sesam', 'price' => 6.00],
                ['name' => 'California Futomaki', 'description' => 'Krab, Avocado, Komkommer, Vistjes, Sesam', 'price' => 7.00],
                ['name' => 'Zalm Futomaki', 'description' => 'Zalm, Avocado, Komkommer, Roomkaas, Sesam', 'price' => 7.00],
                ['name' => 'Chicken Futomaki', 'description' => 'Chicken, Avocado, Komkommer, Sesam', 'price' => 7.00],
            ],
            'Poke Bowl' => [
                ['name' => 'Zalm Poke Bowl', 'description' => 'Verse zalm, rijst, diverse groenten en saus', 'price' => 15.00],
                ['name' => 'Tuna Poke Bowl', 'description' => 'Verse tonijn, rijst, diverse groenten en saus', 'price' => 15.00],
                ['name' => 'Garnalen Poke Bowl', 'description' => 'Garnalen, rijst, diverse groenten en saus', 'price' => 14.00],
                ['name' => 'Karaage Poke Bowl (Kip)', 'description' => 'Gefrituurde kip, rijst, diverse groenten en saus', 'price' => 14.00],
                ['name' => 'Paling Poke Bowl', 'description' => 'Gegrilde paling, rijst, diverse groenten en saus', 'price' => 15.00],
                ['name' => 'Vega Poke Bowl', 'description' => 'Vegetarische ingrediënten, rijst en saus', 'price' => 13.00],
            ],
            'Warm Gerecht' => [
                ['name' => 'Mini Spring Roll', 'description' => '5 stuks mini loempia', 'price' => 3.25],
                ['name' => 'Yakitori Skewer', 'description' => '4 stuks gegrilde kipsaté', 'price' => 6.00],
                ['name' => 'Tori Karaage', 'description' => '5 stuks Japanse gefrituurde kip', 'price' => 5.25],
                ['name' => 'Edamame', 'description' => 'Gekookte sojabonen', 'price' => 4.00],
                ['name' => 'Chuka Wakame', 'description' => 'Zeewiersalade', 'price' => 3.50],
                ['name' => 'Gyoza', 'description' => '5 stuks Japanse dumplings', 'price' => 5.25],
                ['name' => 'Garnalen Tempura', 'description' => '4 stuks gefrituurde garnalen', 'price' => 6.00],
                ['name' => 'Sesam Bal', 'description' => '5 stuks gefrituurde sesamballen', 'price' => 5.00],
            ]
        ];

        foreach ($categories as $categoryName => $products) {
            $category = ProductCategory::firstOrCreate(
                ['name' => $categoryName]
            );


            foreach ($products as $sushi) {
                Product::updateOrCreate(
                    [
                        'name' => $sushi['name'],
                        'product_category_id' => $category->id,
                    ],
                    [
                        'description' => $sushi['description'],
                        'price' => $sushi['price'],
                        'vat_rate_id' => $foodVatRate->id,
                    ]
                );
            }
        }
    }
}