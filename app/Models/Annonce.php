<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendeur_id',
        'produit_id',
        'titre',
        'description',
        'prix_depart',
        'prix_actuel',
        'montant_mise',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function vendeur()
    {
        return $this->belongsTo(Vendeur::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Change from mises() to encheres()
    public function encheres()
    {
        return $this->hasMany(Enchere::class);
    }

    public function estActive()
    {
        return $this->statut === 'ACTIVE';
    }

    public function publier()
    {
        $this->statut = 'ACTIVE';
        $this->date_debut = now();
        $this->save();
    }

    public function cloturer()
    {
        $this->statut = 'CLOTUREE';
        $this->save();
    }

    public function getMontantActuel()
    {
        return $this->prix_actuel ?? $this->prix_depart;
    }

    // Get the highest bid for this auction
    public function getHighestBid()
    {
        return $this->encheres()->orderBy('montant', 'desc')->first();
    }

    // Get all bids for this auction
    public function getAllBids()
    {
        return $this->encheres()->orderBy('montant', 'desc')->get();
    }
}