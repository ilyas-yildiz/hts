<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualGoal extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['goal_category_id', 'year', 'period_label', 'title', 'is_completed', 'order_index']; // order_index eklendi

    protected static function booted()
    {
        static::deleting(function (AnnualGoal $annualGoal) {
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