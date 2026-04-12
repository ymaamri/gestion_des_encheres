<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'siret',
        'note_moyenne',
        'nombre_ventes',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    // Access user easily
    public function user()
    {
        return $this->client->user();
    }
}