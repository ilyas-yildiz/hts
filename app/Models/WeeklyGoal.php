<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyGoal extends Model
{
    use HasFactory;

    public $timestamps = false; // 'updated_at' hatası için

    protected $fillable = ['monthly_goal_id', 'week_label', 'title', 'is_completed'];

    /**
     * YENİ: Model Olayları (Events)
     * Bu model (WeeklyGoal) silinmeden hemen önce bu fonksiyon çalışır.
     */
    protected static function booted()
    {
        static::deleting(function (WeeklyGoal $weeklyGoal) {
            // Bu haftalık hedefe bağlı tüm günlük hedefleri de sil.
            $weeklyGoal->dailyGoals()->each(function ($dailyGoal) {
                $dailyGoal->delete();
            });
        });
    }

    public function monthlyGoal(): BelongsTo
    {
        return $this->belongsTo(MonthlyGoal::class);
    }

    public function dailyGoals(): HasMany
    {
        return $this->hasMany(DailyGoal::class);
    }
}