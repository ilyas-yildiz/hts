<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // İlişki için bu gerekli
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * DÜZELTME: 'email' ve 'password' alanlarını, 'firstOrCreate'
     * metodumuzun çalışabilmesi için $fillable dizisine ekliyoruz.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password' => 'hashed', // Parolayı otomatik hash'ler
        ];
    }

    /**
     * Kullanıcının tüm ana hedef kategorilerini alır.
     * (Bu ilişkiyi daha önce eklemiştik)
     */
    public function goalCategories(): HasMany
    {
        return $this->hasMany(GoalCategory::class);
    }
}