<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mise extends Model
{
    use HasFactory;

    protected $table = 'enchere'; // Changed from 'annonce_client'

    protected $fillable = [
        'annonce_id',  // Changed from 'annonce_id'
        'client_id',   // Changed from 'client_id'
        'montant',     // Changed from 'montant'
        'date_mise',   // Changed from 'date_mise'
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
}