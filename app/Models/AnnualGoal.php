<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualGoal extends Model
{
    use HasFactory;
    
    public $timestamps = false; // 'updated_at' hatası için

    protected $fillable = ['goal_category_id', 'year', 'period_label', 'title', 'is_completed'];

    /**
     * YENİ: Model Olayları (Events)
     * Bu model (AnnualGoal) silinmeden hemen önce bu fonksiyon çalışır.
     */
    protected static function booted()
    {
        static::deleting(function (AnnualGoal $annualGoal) {
            // Bu yıllık hedefe bağlı tüm aylık hedefleri de sil.
            $annualGoal->monthlyGoals()->each(function ($monthlyGoal) {
                $monthlyGoal->delete();
            });
        });
    }

    public function goalCategory(): BelongsTo
    {
        return $this->belongsTo(GoalCategory::class);
    }

    public function monthlyGoals(): HasMany
    {
        return $this->hasMany(MonthlyGoal::class);
    }
}