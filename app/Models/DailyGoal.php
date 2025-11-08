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

    protected $fillable = [
        'weekly_goal_id', 
        'day_label', 
        'goal_date', 
        'title', 
        'is_completed', 
        'order_index'
    ];

    /**
     * DÜZELTME: 'date' (timezone'lu) yerine 'date:Y-m-d' (timezone'suz)
     * kullanarak "bir gün geri kayma" JSON hatasını düzelt.
     */
    protected $casts = [
        'goal_date' => 'date:Y-m-d', // 'date' -> 'date:Y-m-d'
    ];
    
    /**
     * Model Olayları (Events) - Zincirleme silme
     */
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
}