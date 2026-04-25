<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'telephone',
        'adresse_livraison',
        'solde',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendeur()
    {
        return $this->hasOne(Vendeur::class);
    }

    // Change from mises() to encheres()
    public function encheres()
    {
        return $this->hasMany(Enchere::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}