<?php

namespace Database\Seeders;

use App\Models\SousCategorie;
use App\Models\Categorie;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            'Electronics' => [
                ['nom' => 'Smartphones', 'description' => 'Mobile phones and accessories'],
                ['nom' => 'Laptops', 'description' => 'Notebooks and computers'],
                ['nom' => 'Tablets', 'description' => 'iPad and Android tablets'],
                ['nom' => 'Audio', 'description' => 'Headphones, speakers, and audio equipment'],
                ['nom' => 'Cameras', 'description' => 'Digital cameras and photography equipment'],
            ],
            'Fashion' => [
                ['nom' => "Men's Clothing", 'description' => 'Shirts, pants, jackets for men'],
                ['nom' => "Women's Clothing", 'description' => 'Dresses, blouses, skirts for women'],
                ['nom' => 'Shoes', 'description' => 'Sneakers, boots, and formal shoes'],
                ['nom' => 'Accessories', 'description' => 'Bags, watches, belts, and jewelry'],
            ],
            'Home & Garden' => [
                ['nom' => 'Furniture', 'description' => 'Sofas, tables, chairs, and beds'],
                ['nom' => 'Decor', 'description' => 'Home decoration and art'],
                ['nom' => 'Kitchen', 'description' => 'Appliances and utensils'],
                ['nom' => 'Gardening', 'description' => 'Tools and plants for garden'],
            ],
            'Sports' => [
                ['nom' => 'Fitness', 'description' => 'Gym equipment and accessories'],
                ['nom' => 'Outdoor', 'description' => 'Camping and hiking gear'],
                ['nom' => 'Team Sports', 'description' => 'Football, basketball, soccer equipment'],
                ['nom' => 'Cycling', 'description' => 'Bikes and cycling accessories'],
            ],
        ];

        foreach ($subcategories as $categoryName => $subs) {
            $category = Categorie::where('nom', $categoryName)->first();
            if ($category) {
                foreach ($subs as $sub) {
                    SousCategorie::create([
                        'categorie_id' => $category->id,
                        'nom' => $sub['nom'],
                        'description' => $sub['description'],
                    ]);
                }
            }
        }

        $this->command->info('Sub-categories seeded successfully!');
    }
}