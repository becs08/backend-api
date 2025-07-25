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
        
        // S'assurer que user_type existe avant de l'utiliser
        if (isset($this->user_type)) {
            switch($this->user_type) {
                case 'demandeur':
                    $array['user_type_label'] = 'Demandeur';
                    break;
                case 'offreur':
                    $array['user_type_label'] = 'Offreur';
                    break;
                case 'admin':
                    $array['user_type_label'] = 'Administrateur';
                    break;
                default:
                    $array['user_type_label'] = $this->user_type;
            }
        } else {
            $array['user_type_label'] = 'Non défini';
        }
        
        return $array;
    }
}
