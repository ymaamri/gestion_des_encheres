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

    /**
     * Client belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A client can participate in many enchères
     */
    public function encheres()
    {
        return $this->belongsToMany(Enchere::class);
    }

    /**
     * A client receives many notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}