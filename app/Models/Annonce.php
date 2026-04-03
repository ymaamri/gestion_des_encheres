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

    /**
     * 🔗 Relations
     */

    public function vendeur()
    {
        return $this->belongsTo(Vendeur::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Clients participating (we'll use pivot table later)
     */
    public function mises()
    {
        return $this->hasMany(Mise::class);
    }

    /**
     * 🔥 Helper methods (very useful)
     */

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
}