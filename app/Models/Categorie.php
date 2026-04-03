<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'icone',
    ];

    /**
     * Relations
     */

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }
}