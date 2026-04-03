<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'marque',
        'modele',
        'etat',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array', // 🔥 auto convert JSON ↔ array
    ];

    /**
     * Relations
     */

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    /**
     * Helpers
     */

    public function ajouterPhoto($url)
    {
        $photos = $this->photos ?? [];
        $photos[] = $url;

        $this->photos = $photos;
        $this->save();
    }

    public function supprimerPhoto($url)
    {
        $photos = collect($this->photos)->filter(fn($p) => $p !== $url)->values();

        $this->photos = $photos;
        $this->save();
    }

    public function getDetails()
    {
        return "{$this->nom} - {$this->marque} {$this->modele}";
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}