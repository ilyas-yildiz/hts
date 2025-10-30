<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyGoal extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['annual_goal_id', 'month_label', 'title', 'is_completed', 'order_index']; // order_index eklendi

    protected static function booted()
    {
        static::deleting(function (MonthlyGoal $monthlyGoal) {
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