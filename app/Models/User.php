<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isStaff() && $this->is_active;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'Administratif';
    }

    public function isComptable(): bool
    {
        return $this->role === 'Comptable';
    }

    public function isConcierge(): bool
    {
        return $this->role === 'Concierge';
    }

    public function isTechnicien(): bool
    {
        return $this->role === 'Technicien';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['Administratif', 'Comptable', 'Concierge', 'Technicien']);
    }

    public function getFilamentName(): string
    {
        return $this->email;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }

    public function administratif()
    {
        return $this->hasOne(Administratif::class);
    }

    public function comptable()
    {
        return $this->hasOne(Comptable::class);
    }

    public function concierge()
    {
        return $this->hasOne(Concierge::class);
    }

    public function technicien()
    {
        return $this->hasOne(Technicien::class);
    }
}