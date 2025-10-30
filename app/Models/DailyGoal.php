<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGoal extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['weekly_goal_id', 'day_label', 'title', 'is_completed', 'order_index']; // order_index eklendi

    protected static function booted()
    {
        static::deleting(function (DailyGoal $dailyGoal) {
            $dailyGoal->tasks()->each(function ($task) {
                $task->delete();
            });
        });
    }
    public function weeklyGoal(): BelongsTo
    {
        return $this->belongsTo(WeeklyGoal::class);
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}