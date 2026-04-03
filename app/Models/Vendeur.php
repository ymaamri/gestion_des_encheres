<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prenom',
        'siret',
        'note_moyenne',
        'nombre_ventes',
    ];

    /**
     * Relation: Vendeur belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Example: a vendeur can have many enchères
     */
    public function encheres()
    {
        return $this->hasMany(Enchere::class);
    }
}