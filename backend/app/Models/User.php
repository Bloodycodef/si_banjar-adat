<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use PDO;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Role Check Methods
    public function isKlian(): bool
    {
        return $this->role === 'klian';
    }

    public function isPenyarikan(): bool
    {
        return $this->role === 'penyarikan';
    }

    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    public function isKrame(): bool
    {
        return $this->role === 'krame';
    }

    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    public function isActive(): bool
    {
        return $this->is_active == 1;
    }

    // Scope untuk query
    public function scopeByRole($query, $role)
    {
        if (is_array($role)) {
            return $query->whereIn('role', $role);
        }
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
