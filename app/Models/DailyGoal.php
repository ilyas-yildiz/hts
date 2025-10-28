<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGoal extends Model // Hata buradaydı, "WeeklyGoal" yazıyordu
{
    use HasFactory;

    protected $fillable = ['weekly_goal_id', 'day_label', 'title'];

    /**
     * Bu günlük hedefin ait olduğu haftalık hedef.
     */
    public function weeklyGoal(): BelongsTo
    {
        return $this->belongsTo(WeeklyGoal::class);
    }

    /**
     * Bu günlük hedefe ait görevler (tasks).
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}

