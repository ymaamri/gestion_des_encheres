<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get product image URL
     */
    public static function getProductImage($produit, $index = 0)
    {
        if (!$produit) {
            return 'https://via.placeholder.com/400x300?text=No+Image';
        }

        $photos = $produit->photos;

        if ($photos && is_array($photos) && isset($photos[$index]) && $photos[$index]) {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($photos[$index])) {
                return Storage::url($photos[$index]);
            }
        }

        // Return placeholder image
        return 'https://via.placeholder.com/400x300?text=' . urlencode($produit->nom ?? 'Product');
    }

    /**
     * Get multiple product images
     */
    public static function getProductImages($produit)
    {
        $images = [];

        if ($produit && $produit->photos && is_array($produit->photos)) {
            foreach ($produit->photos as $photo) {
                if ($photo && Storage::disk('public')->exists($photo)) {
                    $images[] = Storage::url($photo);
                }
            }
        }

        if (empty($images)) {
            $images[] = 'https://via.placeholder.com/400x300?text=No+Image';
        }

        return $images;
    }
}