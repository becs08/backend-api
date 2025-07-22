<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'message', 'prix_propose', 'statut', 'offre_id',
        'demandeur_id', 'date_reponse', 'message_reponse'
    ];

    protected $casts = [
        'prix_propose' => 'decimal:2',
        'date_reponse' => 'datetime',
    ];

    protected $appends = ['statut_label', 'statut_color'];

    // Relations
    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function demandeur()
    {
        return $this->belongsTo(User::class, 'demandeur_id');
    }

    // Accesseurs
    public function getStatutLabelAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'acceptee' => 'Acceptée',
            'refusee' => 'Refusée',
            'annulee' => 'Annulée',
            default => $this->statut
        };
    }

    public function getStatutColorAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'yellow',
            'acceptee' => 'green',
            'refusee' => 'red',
            'annulee' => 'gray',
            default => 'gray'
        };
    }
}
