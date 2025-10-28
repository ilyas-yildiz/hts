<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['daily_goal_id', 'time_label', 'task_description', 'is_completed'];

    /**
     * Bu görevin ait olduğu günlük hedef.
     */
    public function dailyGoal(): BelongsTo
    {
        return $this->belongsTo(DailyGoal::class);
    }
}
