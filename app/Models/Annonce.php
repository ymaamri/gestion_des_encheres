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
        'prix_final',
        'statut',
    ];

    protected $casts = [
        'prix_depart' => 'decimal:2',
        'prix_final' => 'decimal:2',
    ];

    // Relations
    public function vendeur()
    {
        return $this->belongsTo(Vendeur::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function encheres()
    {
        return $this->hasMany(Enchere::class);
    }

    // Récupérer la mise la plus haute pour cette annonce
    public function miseLaPlusHaute()
    {
        return $this->hasOne(Enchere::class)->orderBy('montant', 'desc');
    }

    // Récupérer toutes les mises d'un client spécifique
    public function misesParClient(Client $client)
    {
        return $this->encheres()->where('client_id', $client->id);
    }

    // Récupérer le prix actuel (dernière mise ou prix de départ)
    public function getPrixActuelAttribute()
    {
        $highestBid = $this->encheres()->max('montant');
        return $highestBid ?? $this->prix_depart;
    }

    // Vérifier si l'enchère est active
    public function estActive()
    {
        $now = now();
        $activeEnchere = $this->encheres()
            ->where('date_debut', '<=', $now)
            ->where('date_fin', '>', $now)
            ->exists();
            
        return $this->statut === 'ACTIVE' && $activeEnchere;
    }

    // Vérifier si l'enchère est terminée
    public function estTerminee()
    {
        return $this->statut === 'CLOTUREE';
    }

    // Publier l'annonce (soumission pour validation)
    public function publier()
    {
        $this->statut = 'EN_ATTENTE';
        $this->save();
    }

    // Valider l'annonce par l'admin
    public function valider($dateDebut, $dateFin)
    {
        // Créer la première enchère (session)
        Enchere::create([
            'annonce_id' => $this->id,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'montant' => 0, // Pas de mise initiale
        ]);
        
        $this->statut = 'ACTIVE';
        $this->save();
    }

    // Clôturer l'enchère
    public function cloturer()
    {
        // Trouver la mise gagnante
        $winningBid = $this->encheres()
            ->orderBy('montant', 'desc')
            ->first();
            
        if ($winningBid) {
            $this->prix_final = $winningBid->montant;
            
            // Notifier le gagnant
            // Notification logic here...
        }
        
        $this->statut = 'CLOTUREE';
        $this->save();
    }
}