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
        'prix_final',
        'statut',
    ];

    protected $casts = [
        'prix_depart' => 'decimal:2',
        'prix_actuel' => 'decimal:2',
        'montant_mise' => 'decimal:2',
        'prix_final' => 'decimal:2',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    // =============================== Relations ===============================

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

    // Alias for encheres (used in some views)
    public function mises()
    {
        return $this->encheres();
    }

    // =============================== Auction Helpers ===============================

    /**
     * Get the current highest bid amount.
     * Returns prix_actuel if set, otherwise prix_depart.
     */
    public function getMontantActuel()
    {
        return $this->prix_actuel ?? $this->prix_depart;
    }

    /**
     * Get the current highest bid with client info.
     */
    public function getHighestBid()
    {
        return $this->encheres()
            ->with('client')
            ->orderBy('montant', 'desc')
            ->first();
    }

    /**
     * Get all bids for this auction.
     */
    public function getBids()
    {
        return $this->encheres()
            ->with('client')
            ->orderBy('montant', 'desc')
            ->get();
    }

    /**
     * Get bid count.
     */
    public function getBidCount()
    {
        return $this->encheres()->count();
    }

    /**
     * Check if auction is currently active.
     */
    public function estActive()
    {
        return $this->statut === 'ACTIVE' && $this->date_fin && $this->date_fin > now();
    }

    /**
     * Check if auction has ended.
     */
    public function estTerminee()
    {
        return $this->statut === 'CLOTUREE';
    }

    /**
     * Check if a specific user has placed a bid on this auction.
     */
    public function userHasBid($userId)
    {
        return $this->encheres()
            ->whereHas('client', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->exists();
    }

    /**
     * Get the user's highest bid on this auction.
     */
    public function getUserBid($userId)
    {
        return $this->encheres()
            ->whereHas('client', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('montant', 'desc')
            ->first();
    }

    // =============================== Lifecycle Methods ===============================

    /**
     * Mark the auction as pending (called after creation).
     */
    public function publier()
    {
        $this->statut = 'EN_ATTENTE';
        $this->save();
    }

    /**
     * Activate the auction with start and end dates.
     * Called by admin after validation.
     */
    public function valider($dateDebut, $dateFin)
    {
        $this->date_debut = $dateDebut;
        $this->date_fin = $dateFin;
        $this->prix_actuel = $this->prix_depart; // initial current price
        $this->statut = 'ACTIVE';
        $this->save();
    }

    /**
     * Close the auction, set final price based on winning bid.
     */
    public function cloturer()
    {
        $winningBid = $this->getHighestBid();
        if ($winningBid) {
            $this->prix_final = $winningBid->montant;
        } else {
            $this->prix_final = $this->prix_depart;
        }
        $this->statut = 'CLOTUREE';
        $this->save();
    }

    /**
 * Get the end date attribute.
 * Returns date_fin directly (already cast to Carbon/datetime).
 */
 public function getDateFinAttribute()
    {
        $lastEnchere = $this->encheres()->orderBy('date_fin', 'desc')->first();
        return $lastEnchere ? $lastEnchere->date_fin : null;
    }

/**
 * Get the start date attribute.
 * Returns date_debut directly (already cast to Carbon/datetime).
 */
public function getDateDebutAttribute()
{
    return $this->encheres->date_debut;
}
}