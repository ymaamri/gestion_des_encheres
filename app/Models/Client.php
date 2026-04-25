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


    // Récupérer les enchères gagnées par le client
    public function encheresGagnees()
    {
        return $this->hasMany(Enchere::class)->whereHas('annonce', function($q) {
            $q->where('statut', 'CLOTUREE');
        })->orderBy('montant', 'desc');
    }

    // Récupérer les enchères actives du client
    public function encheresActives()
    {
        return $this->hasMany(Enchere::class)
            ->whereHas('annonce', function($q) {
                $q->where('statut', 'ACTIVE');
            })
            ->where('date_fin', '>', now());
    }
}