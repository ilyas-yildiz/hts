<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App_Models_Task; // DÜZELTME: Task modelini (silme için) dahil et

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

    protected $casts = [
        'goal_date' => 'date:Y-m-d',
    ];
    
    /**
     * DÜZELTME (V2 Hatası): Zincirleme silme mantığı,
     * V2 Ajanda ('tasks()' ilişkisi kaldırıldı) için güncellendi.
     */
    protected static function booted()
    {
        // 'deleting' olayını dinle
        static::deleting(function (DailyGoal $dailyGoal) {
            
            // 1. Bu 'Gün'ün hangi 'Kategori'ye ait olduğunu bul
            // (ilişkileri yükle: Gün -> Hafta -> Ay -> Yıl -> Kategori)
            // (N+1 sorununu önlemek için 'loadMissing' kullanmak daha güvenli)
            $dailyGoal->loadMissing('weeklyGoal.monthlyGoal.annualGoal.goalCategory');
            
            $goalCategory = $dailyGoal->weeklyGoal->monthlyGoal->annualGoal->goalCategory;
            
            // 2. Eğer bir tarih VEYA kategori bulunamazsa, (güvenlik için) dur
            if (!$dailyGoal->goal_date || !$goalCategory) {
                return; 
            }

            // 3. Bu güne (goal_date) VE bu kategoriye (goal_category_id)
            // ait tüm Görevleri (Task) bul ve sil.
            Task::where('goal_date', $dailyGoal->goal_date)
                ->where('goal_category_id', $goalCategory->id)
                ->delete();
        });
    }

    public function weeklyGoal(): BelongsTo
    {
        return $this->belongsTo(WeeklyGoal::class);
    }

    // 'tasks()' ilişkisi (doğru bir şekilde) burada OLMAMALI.
}