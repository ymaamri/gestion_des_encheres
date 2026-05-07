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
                ['nom' => 'Audio', 'description' => 'Headphones, speakers, audio equipment'],
                ['nom' => 'Cameras', 'description' => 'Digital cameras and photography'],
            ],
            'Fashion' => [
                ['nom' => "Hommes", 'description' => 'Men clothing, shirts, pants, jackets'],
                ['nom' => "Femmes", 'description' => 'Women dresses, blouses, skirts'],
                ['nom' => 'Chaussures', 'description' => 'Sneakers, boots, formal shoes'],
                ['nom' => 'Accessoires', 'description' => 'Bags, watches, belts, jewelry'],
            ],
            'Home & Garden' => [
                ['nom' => 'Meubles', 'description' => 'Sofas, tables, chairs, beds'],
                ['nom' => 'Décoration', 'description' => 'Home decor and art pieces'],
                ['nom' => 'Cuisine', 'description' => 'Appliances and utensils'],
                ['nom' => 'Jardin', 'description' => 'Tools, plants, outdoor furniture'],
            ],
            'Sports' => [
                ['nom' => 'Fitness', 'description' => 'Gym equipment and accessories'],
                ['nom' => 'Plein air', 'description' => 'Camping, hiking gear'],
                ['nom' => 'Sports collectifs', 'description' => 'Football, basketball, soccer'],
                ['nom' => 'Cyclisme', 'description' => 'Bikes and cycling accessories'],
            ],
            'Automotive' => [
                ['nom' => 'Voitures', 'description' => 'Cars and vehicles'],
                ['nom' => 'Motos', 'description' => 'Motorcycles and scooters'],
                ['nom' => 'Pièces auto', 'description' => 'Car parts and accessories'],
            ],
            'Collectibles' => [
                ['nom' => 'Art', 'description' => 'Paintings, sculptures'],
                ['nom' => 'Antiquités', 'description' => 'Vintage items'],
                ['nom' => 'Monnaies & timbres', 'description' => 'Coins and stamps'],
            ],
            'Books' => [
                ['nom' => 'Livres neufs', 'description' => 'New releases'],
                ['nom' => 'Livres anciens', 'description' => 'Rare editions'],
                ['nom' => 'BD & Mangas', 'description' => 'Comics and manga'],
            ],
            'Toys & Hobbies' => [
                ['nom' => 'Jeux vidéo', 'description' => 'Video games and consoles'],
                ['nom' => 'Jouets', 'description' => 'Classic toys'],
                ['nom' => 'Modélisme', 'description' => 'Models and hobby kits'],
            ],
        ];

        foreach ($subcategories as $categoryName => $subs) {
            $category = Categorie::where('nom', $categoryName)->first();
            if ($category) {
                foreach ($subs as $sub) {
                    SousCategorie::firstOrCreate(
                        ['categorie_id' => $category->id, 'nom' => $sub['nom']],
                        ['description' => $sub['description']]
                    );
                }
            }
        }

        $this->command->info('✅ Sub-categories seeded successfully!');
    }
}