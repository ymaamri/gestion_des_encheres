<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Electronics', 'description' => 'Smartphones, laptops, tablets, and other electronic devices', 'icone' => 'devices'],
            ['nom' => 'Fashion', 'description' => 'Clothing, shoes, accessories, and jewelry', 'icone' => 'checkroom'],
            ['nom' => 'Home & Garden', 'description' => 'Furniture, decor, gardening tools, and appliances', 'icone' => 'home'],
            ['nom' => 'Sports', 'description' => 'Sports equipment, fitness gear, and outdoor activities', 'icone' => 'sports_basketball'],
            ['nom' => 'Automotive', 'description' => 'Cars, motorcycles, parts, and accessories', 'icone' => 'directions_car'],
            ['nom' => 'Collectibles', 'description' => 'Antiques, art, stamps, coins, and other collectibles', 'icone' => 'collections'],
            ['nom' => 'Books', 'description' => 'Books, magazines, comics, and educational materials', 'icone' => 'menu_book'],
            ['nom' => 'Toys & Hobbies', 'description' => 'Toys, games, and hobby supplies', 'icone' => 'toys'],
        ];

        foreach ($categories as $category) {
            Categorie::create($category);
        }

        $this->command->info('Categories seeded successfully!');
    }
}