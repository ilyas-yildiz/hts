<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_category_id',
        'annual_goal_id',
        'monthly_goal_id',
        'weekly_goal_id',
        'daily_goal_id',
        'goal_date',
        'start_time',
        'end_time',
        'task_description',
        'is_completed',
        'order_index'
    ];

    /**
     * DÜZELTME (V3 - Timezone Fix): 'date' (timezone'lu) yerine 'date:Y-m-d' (timezone'suz)
     * kullanarak "bir gün geri kayma" JSON hatasını düzelt.
     */
    protected $casts = [
        'goal_date'  => 'date:Y-m-d', // 'date' -> 'date:Y-m-d'
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    /**
     * Bu görevin ait olduğu ana kategori (Proje).
     */
   public function goalCategory()
    {
        return $this->belongsTo(GoalCategory::class);
    }

    public function annualGoal()
    {
        return $this->belongsTo(AnnualGoal::class);
    }

    public function monthlyGoal()
    {
        return $this->belongsTo(MonthlyGoal::class);
    }

    public function weeklyGoal()
    {
        return $this->belongsTo(WeeklyGoal::class);
    }

    public function dailyGoal()
    {
        return $this->belongsTo(DailyGoal::class);
    }
}