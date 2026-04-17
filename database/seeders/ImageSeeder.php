<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        // Sample image URLs
        $sampleImages = [
            'https://picsum.photos/id/20/400/300', // iPhone
            'https://picsum.photos/id/26/400/300', // Laptop
            'https://picsum.photos/id/30/400/300', // Coffee
            'https://picsum.photos/id/42/400/300', // Piano
            'https://picsum.photos/id/50/400/300', // Camera
            'https://picsum.photos/id/60/400/300', // Watch
        ];

        $products = Produit::all();

        foreach ($products as $index => $product) {
            $imageIndex = $index % count($sampleImages);
            $imageUrl = $sampleImages[$imageIndex];

            // Download and save image
            $imageContent = file_get_contents($imageUrl);
            $filename = 'products/product_' . $product->id . '_' . time() . '.jpg';
            Storage::disk('public')->put($filename, $imageContent);

            // Update product with image
            $product->photos = [$filename];
            $product->save();

            $this->command->info("Added image to product: {$product->nom}");
        }
    }
}