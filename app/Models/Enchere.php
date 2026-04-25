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
}