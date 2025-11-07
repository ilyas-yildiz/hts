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

    // 'goal_date' (tarih) sütunu bir önceki adımda eklenmişti
    protected $fillable = [
        'weekly_goal_id', 
        'day_label', 
        'goal_date', 
        'title', 
        'is_completed', 
        'order_index'
    ];

    protected $casts = [
        'goal_date' => 'date',
    ];

    /**
     * DÜZENLENDİ: 'booted()' metodu kaldırıldı.
     * Görevler (Tasks) artık 'DailyGoal'a değil, 'GoalCategory'ye bağlı,
     * bu yüzden 'DailyGoal' silinirken 'Task'ları silmesine gerek kalmadı.
     */
    
    public function weeklyGoal(): BelongsTo
    {
        return $this->belongsTo(WeeklyGoal::class);
    }

    /**
     * DÜZENLENDİ: 'tasks(): HasMany' ilişkisi kaldırıldı.
     * Görevler artık bu modele doğrudan bağlı değil.
     */
}