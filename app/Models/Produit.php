<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'description',
        'marque',
        'modele',
        'etat',
        'sous_categorie_id',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    // Relationship with SousCategorie
    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class);
    }

    // Relationship with Categorie (through sousCategorie)
    public function categorie()
    {
        return $this->hasOneThrough(
            Categorie::class,
            SousCategorie::class,
            'id',           // Foreign key on sous_categories
            'id',           // Foreign key on categories
            'sous_categorie_id', // Local key on produits
            'categorie_id'      // Local key on sous_categories
        );
    }

    // ✅ FIXED: Relationship with Annonce
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'produit_id', 'id');
    }

    // Helper to get first image URL
    public function getFirstPhotoUrl()
    {
        $photos = $this->photos;
        if (!empty($photos) && is_array($photos) && !empty($photos[0])) {
            if (filter_var($photos[0], FILTER_VALIDATE_URL)) {
                return $photos[0];
            }
            if (\Storage::disk('public')->exists($photos[0])) {
                return \Storage::url($photos[0]);
            }
        }
        return 'https://picsum.photos/id/' . (($this->id % 100) + 1) . '/400/300';
    }
}