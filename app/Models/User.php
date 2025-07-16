<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TawafData;
use App\Models\TawafSimpan;

class User extends Authenticatable
{
    
    protected $connection = 'users_db'; // Tambahkan ini
    protected $table = 'users'; // Optional, jika nama table berbeda
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    // TawafData
    public function tawafData()
    {
        return $this->hasMany(TawafData::class, 'user_id');
    }

    // TawafSimpan
    public function tawafSimpan()
    {
        return $this->hasMany(TawafSimpan::class, 'user_id');
    }
}
