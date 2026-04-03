<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'message',
        'date_envoi',
        'type',
        'lue',
    ];

    protected $casts = [
        'lue' => 'boolean',
        'date_envoi' => 'datetime',
    ];

    /**
     * 🔗 Relations
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * 🔥 Helpers
     */
    public function envoyer()
    {
        $this->date_envoi = now();
        $this->save();
    }

    public function marquerCommeLue()
    {
        $this->lue = true;
        $this->save();
    }
}