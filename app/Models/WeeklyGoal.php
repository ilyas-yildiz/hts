<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyGoal extends Model
{
    use HasFactory;

protected $fillable = ['monthly_goal_id', 'week_label', 'title', 'is_completed'];
    /**
     * Bu haftalık hedefin ait olduğu aylık hedef.
     */
    public function monthlyGoal(): BelongsTo
    {
        return $this->belongsTo(MonthlyGoal::class);
    }

    /**
     * Bu haftalık hedefe bağlı tüm günlük hedefler.
     */
    public function dailyGoals(): HasMany
    {
        return $this->hasMany(DailyGoal::class);
    }
}
