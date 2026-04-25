<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enchere extends Model
{
    use HasFactory;

    protected $table = 'enchere';

    protected $fillable = [
        'annonce_id',
        'client_id',
        'montant',
        'date_mise',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_mise' => 'datetime',
        'montant' => 'decimal:2',
    ];

    /**
     * Relations
     */
    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Helper method to check if this is the winning bid
     */
    public function isWinningBid()
    {
        return $this->annonce->getMontantActuel() == $this->montant;
    }

    // Vérifier si cette mise est la gagnante
    public function estGagnante()
    {
        $highestBid = $this->annonce->encheres()
            ->whereNotNull('client_id')
            ->orderBy('montant', 'desc')
            ->first();

        return $highestBid && $highestBid->id === $this->id;
    }

    // Vérifier si l'enchère est encore active
    public function estActive()
    {
        $now = now();
        return $this->date_debut <= $now && $this->date_fin > $now;
    }

    // Placer une nouvelle mise
    public static function placerMise(Annonce $annonce, Client $client, $montant)
    {
        // Vérifier si l'enchère est active
        $now = now();
        $lastEnchere = $annonce->encheres()->latest('date_fin')->first();

        if (!$lastEnchere || $lastEnchere->date_fin <= $now) {
            throw new \Exception('Cette enchère est terminée');
        }

        // Vérifier le montant minimum
        $prixActuel = $annonce->prix_actuel;
        $montantMin = $prixActuel + 1; // Pas d'enchère minimum de 1 MAD

        if ($montant < $montantMin) {
            throw new \Exception("Le montant minimum est de {$montantMin} MAD");
        }

        // Créer la nouvelle mise
        return self::create([
            'annonce_id' => $annonce->id,
            'client_id' => $client->id,
            'montant' => $montant,
            'date_mise' => $now,
            'date_debut' => $lastEnchere->date_debut,
            'date_fin' => $lastEnchere->date_fin,
        ]);
    }
}