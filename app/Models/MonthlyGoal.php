<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyGoal extends Model
{
    use HasFactory;

    public $timestamps = false; // 'updated_at' hatası için

    protected $fillable = ['annual_goal_id', 'month_label', 'title', 'is_completed'];

    /**
     * YENİ: Model Olayları (Events)
     * Bu model (MonthlyGoal) silinmeden hemen önce bu fonksiyon çalışır.
     */
    protected static function booted()
    {
        static::deleting(function (MonthlyGoal $monthlyGoal) {
            // Bu aylık hedefe bağlı tüm haftalık hedefleri de sil.
            $monthlyGoal->weeklyGoals()->each(function ($weeklyGoal) {
                $weeklyGoal->delete();
            });
        });
    }

    public function annualGoal(): BelongsTo
    {
        return $this->belongsTo(AnnualGoal::class);
    }

    public function weeklyGoals(): HasMany
    {
        return $this->hasMany(WeeklyGoal::class);
    }
}