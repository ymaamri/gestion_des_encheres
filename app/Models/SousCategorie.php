<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_id',
        'nom',
        'description',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function annonces()
    {
        return $this->hasManyThrough(Annonce::class, Produit::class);
    }
}