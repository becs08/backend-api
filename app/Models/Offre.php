<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'description', 'categorie', 'prix_min', 'prix_max',
        'localisation', 'type_offre', 'statut', 'user_id', 'images',
        'date_expiration', 'vues'
    ];

    protected $casts = [
        'images' => 'array',
        'date_expiration' => 'datetime',
        'prix_min' => 'decimal:2',
        'prix_max' => 'decimal:2',
    ];

    protected $appends = ['prix_formate', 'author_name'];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }

    // Accesseurs
    public function getPrixFormateAttribute()
    {
        if ($this->prix_min && $this->prix_max) {
            return "De {$this->prix_min}€ à {$this->prix_max}€";
        } elseif ($this->prix_min) {
            return "À partir de {$this->prix_min}€";
        } elseif ($this->prix_max) {
            return "Jusqu'à {$this->prix_max}€";
        }
        return 'Prix à négocier';
    }

    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : 'Utilisateur supprimé';
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('statut', 'active')
            ->where(function ($q) {
                $q->whereNull('date_expiration')
                    ->orWhere('date_expiration', '>', now());
            });
    }

    // Méthodes
    public function incrementerVues()
    {
        $this->increment('vues');
    }
}
