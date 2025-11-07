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

    // DÜZENLENDİ: 'start_date' eklendi
    protected $fillable = [
        'monthly_goal_id', 
        'week_label', 
        'start_date', // YENİ EKLENDİ
        'title', 
        'is_completed', 
        'order_index'
    ];

    /**
     * YENİ: 'start_date' sütununun otomatik olarak bir Tarih (Carbon)
     * objesine dönüştürülmesini sağlar.
     */
    protected $casts = [
        'start_date' => 'date',
    ];

    /**
     * Model Olayları (Events) - Zincirleme silme
     */
    protected static function booted()
    {
        static::deleting(function (WeeklyGoal $weeklyGoal) {
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