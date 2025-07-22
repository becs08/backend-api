<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'city',
        'postal_code', 'user_type', 'is_verified', 'last_login_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_verified' => 'boolean',
        'password' => 'hashed',
    ];

    // Relations
    public function offres()
    {
        return $this->hasMany(Offre::class);
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'demandeur_id');
    }

    // Méthodes
    public function estOffreur()
    {
        return $this->user_type === 'offreur';
    }

    public function estAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function mettreAJourDerniereConnexion()
    {
        $this->update(['last_login_at' => now()]);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['user_type_label'] = match($this->user_type) {
            'demandeur' => 'Demandeur',
            'offreur' => 'Offreur',
            'admin' => 'Administrateur',
            default => $this->user_type
        };
        return $array;
    }
}
