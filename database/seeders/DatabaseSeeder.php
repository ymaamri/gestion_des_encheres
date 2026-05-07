<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Order matters because of foreign keys
        $this->call([
            CategorySeeder::class,     // create categories
            SubCategorySeeder::class,  // create subcategories (depends on categories)
            UserSeeder::class,         // create users, clients, vendeurs
            AuctionSeeder::class,      // create products, auctions, bids, notifications
        ]);
    }
}