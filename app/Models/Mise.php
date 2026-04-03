<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mise extends Model
{
    use HasFactory;

    protected $table = 'annonce_client';

    protected $fillable = [
        'annonce_id',
        'client_id',
        'montant',
        'date_mise',
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