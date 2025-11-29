<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'peran',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship dengan transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Helper method untuk cek peran
    public function isAdmin()
    {
        return $this->peran === 'admin';
    }

    public function isKasir()
    {
        return $this->peran === 'kasir';
    }
}