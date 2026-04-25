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

    /**
     * Get the current highest bid amount
     */
    public function getMontantActuel()
    {
        // Get the highest bid from encheres table
        $highestBid = $this->encheres()->max('montant');
        
        // If there are bids, return the highest, otherwise return the starting price
        return $highestBid ?? $this->prix_depart;
    }

    /**
     * Get the current highest bid with client info
     */
    public function getHighestBid()
    {
        return $this->encheres()
            ->with('client')
            ->orderBy('montant', 'desc')
            ->first();
    }

    /**
     * Get all bids for this auction
     */
    public function getBids()
    {
        return $this->encheres()
            ->with('client')
            ->orderBy('montant', 'desc')
            ->get();
    }

    /**
     * Get bid count
     */
    public function getBidCount()
    {
        return $this->encheres()->count();
    }

    /**
     * Check if auction is active
     */
    public function estActive()
    {
        // Check if there's an active enchere for this annonce
        $activeEnchere = $this->encheres()
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>', now())
            ->exists();
            
        return $this->statut === 'ACTIVE' && $activeEnchere;
    }

    /**
     * Check if auction is ended
     */
    public function estTerminee()
    {
        return $this->statut === 'CLOTUREE';
    }

    /**
     * Get the current enchere session
     */
    public function getCurrentEnchere()
    {
        return $this->encheres()
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>', now())
            ->first();
    }

    /**
     * Get end date (from the latest enchere)
     */
    public function getDateFinAttribute()
    {
        $latestEnchere = $this->encheres()->latest('date_fin')->first();
        return $latestEnchere ? $latestEnchere->date_fin : null;
    }

    /**
     * Get start date (from the earliest enchere)
     */
    public function getDateDebutAttribute()
    {
        $earliestEnchere = $this->encheres()->oldest('date_debut')->first();
        return $earliestEnchere ? $earliestEnchere->date_debut : null;
    }

    /**
     * Check if user has bid on this auction
     */
    public function userHasBid($userId)
    {
        return $this->encheres()
            ->whereHas('client', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->exists();
    }

    /**
     * Get user's highest bid on this auction
     */
    public function getUserBid($userId)
    {
        return $this->encheres()
            ->whereHas('client', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('montant', 'desc')
            ->first();
    }

    // Helper methods for the auction lifecycle
    public function publier()
    {
        $this->statut = 'EN_ATTENTE';
        $this->save();
    }

    public function valider($dateDebut, $dateFin)
    {
        // Create the first enchere session
        Enchere::create([
            'annonce_id' => $this->id,
            'client_id' => null,
            'montant' => $this->prix_depart,
            'date_mise' => $dateDebut,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ]);
        
        $this->statut = 'ACTIVE';
        $this->save();
    }

    public function cloturer()
    {
        // Find the winning bid
        $winningBid = $this->getHighestBid();
        
        if ($winningBid) {
            $this->prix_final = $winningBid->montant;
        }
        
        $this->statut = 'CLOTUREE';
        $this->save();
    }
}