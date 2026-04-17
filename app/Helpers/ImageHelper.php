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
            return 'https://picsum.photos/id/1/400/300';
        }

        $photos = $produit->photos;

        // Check if photos exist
        if ($photos && is_array($photos) && isset($photos[$index]) && $photos[$index]) {
            $photo = $photos[$index];

            // If it's a URL (starts with http)
            if (filter_var($photo, FILTER_VALIDATE_URL)) {
                return $photo;
            }

            // If it's a local storage path
            if (Storage::disk('public')->exists($photo)) {
                return Storage::url($photo);
            }
        }

        // Return a nice placeholder image from picsum with product name in alt text
        return 'https://picsum.photos/id/' . (($produit->id % 100) + 1) . '/400/300';
    }

    /**
     * Get multiple product images
     */
    public static function getProductImages($produit)
    {
        $images = [];

        if ($produit && $produit->photos && is_array($produit->photos)) {
            foreach ($produit->photos as $photo) {
                if ($photo) {
                    if (filter_var($photo, FILTER_VALIDATE_URL)) {
                        $images[] = $photo;
                    } elseif (Storage::disk('public')->exists($photo)) {
                        $images[] = Storage::url($photo);
                    }
                }
            }
        }

        if (empty($images)) {
            // Use picsum with different IDs for variety
            $images[] = 'https://picsum.photos/id/' . (($produit->id % 100) + 1) . '/400/300';
        }

        return $images;
    }
}
