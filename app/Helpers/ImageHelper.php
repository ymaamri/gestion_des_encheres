<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get a single product image URL.
     *
     * @param \App\Models\Produit|null $produit
     * @param int $index
     * @return string
     */
    public static function getProductImage($produit, $index = 0)
    {
        // If no product, return a generic placeholder
        if (!$produit) {
            return 'https://picsum.photos/id/1/400/300';
        }

        $photos = $produit->photos;

        // If photos exist and the index is valid
        if (!empty($photos) && is_array($photos) && isset($photos[$index]) && !empty($photos[$index])) {
            $photo = $photos[$index];

            // If it's already a full URL (external image)
            if (filter_var($photo, FILTER_VALIDATE_URL)) {
                return $photo;
            }

            // If it's a local storage path (stored via Laravel)
            // Check if the file exists in the 'public' disk
            if (Storage::disk('public')->exists($photo)) {
                return Storage::url($photo);
            }
        }

        // Fallback: a nice placeholder image based on product ID
        $id = $produit->id ?? rand(1, 100);
        return 'https://picsum.photos/id/' . (($id % 100) + 1) . '/400/300';
    }

    /**
     * Get all product images as an array of URLs.
     *
     * @param \App\Models\Produit|null $produit
     * @return array
     */
    public static function getProductImages($produit)
    {
        if (!$produit) {
            return ['https://picsum.photos/id/1/400/300'];
        }

        $photos = $produit->photos;
        $images = [];

        if (!empty($photos) && is_array($photos)) {
            foreach ($photos as $photo) {
                if (filter_var($photo, FILTER_VALIDATE_URL)) {
                    $images[] = $photo;
                } elseif (Storage::disk('public')->exists($photo)) {
                    $images[] = Storage::url($photo);
                }
            }
        }

        // If no valid image found, add a placeholder
        if (empty($images)) {
            $id = $produit->id ?? rand(1, 100);
            $images[] = 'https://picsum.photos/id/' . (($id % 100) + 1) . '/400/300';
        }

        return $images;
    }
}