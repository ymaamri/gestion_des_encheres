<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id',
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

    public function mises()
    {
        return $this->hasMany(Mise::class);
    }
}