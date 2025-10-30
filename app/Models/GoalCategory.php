<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoalCategory extends Model
{
    use HasFactory;

    // 'timestamps' (oluşturma/güncelleme) sütunlarını KULLANMA
    // (Bir önceki 500 hatası düzeltmemiz)
    public $timestamps = false;

    protected $fillable = ['user_id', 'name', 'is_completed'];

    /**
     * YENİ: Model Olayları (Events)
     * Bu model (GoalCategory) silinmeden hemen önce bu fonksiyon çalışır.
     */
    protected static function booted()
    {
        static::deleting(function (GoalCategory $goalCategory) {
            // Bu kategoriye bağlı tüm yıllık hedefleri de sil.
            // Bu, zincirleme bir reaksiyonu (cascade) tetikleyecek.
            $goalCategory->annualGoals()->each(function ($annualGoal) {
                $annualGoal->delete();
            });
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function annualGoals(): HasMany
    {
        return $this->hasMany(AnnualGoal::class);
    }
}

