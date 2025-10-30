<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGoal extends Model
{
    use HasFactory;

    public $timestamps = false; // 'updated_at' hatası için

    protected $fillable = ['weekly_goal_id', 'day_label', 'title', 'is_completed'];

    /**
     * YENİ: Model Olayları (Events)
     * Bu model (DailyGoal) silinmeden hemen önce bu fonksiyon çalışır.
     */
    protected static function booted()
    {
        static::deleting(function (DailyGoal $dailyGoal) {
            // Bu günlük hedefe bağlı tüm görevleri (task) de sil.
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