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
    protected $fillable = ['monthly_goal_id', 'week_label', 'title', 'is_completed', 'order_index']; // order_index eklendi

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