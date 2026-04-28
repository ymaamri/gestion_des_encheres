<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ImageHelper;

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
        'vendeur_id',
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

    // Relationship with Annonce
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'produit_id', 'id');
    }

    // Helper to get first image URL - NOW USES IMAGEHELPER
    public function getFirstPhotoUrl()
    {
        return ImageHelper::getProductImage($this);
    }
}