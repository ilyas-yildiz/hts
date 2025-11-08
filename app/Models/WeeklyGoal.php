<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyGoal extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'monthly_goal_id', 
        'week_label', 
        'start_date', 
        'title', 
        'is_completed', 
        'order_index'
    ];

    /**
     * DÜZELTME: 'date' (timezone'lu) yerine 'date:Y-m-d' (timezone'suz)
     * kullanarak "bir gün geri kayma" JSON hatasını düzelt.
     */
    protected $casts = [
        'start_date' => 'date:Y-m-d', // 'date' -> 'date:Y-m-d'
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