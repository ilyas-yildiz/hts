<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    // (Timestamps bu dosyada zaten vardı, $timestamps = false; yok)

    /**
     * DÜZENLENDİ (V3): 'time_label' kaldırıldı.
     * 'start_time' ve 'end_time' eklendi.
     */
    protected $fillable = [
        'goal_category_id', 
        'goal_date',        
        'start_time',       // YENİ
        'end_time',         // YENİ
        'task_description', 
        'is_completed', 
        'order_index'
    ];

    /**
     * DÜZELTME: 'start_time' ve 'end_time' için olan $casts (dönüşüm)
     * kuralları, 'TIME' tipiyle çakıştığı için KALDIRILDI.
     * Artık 'H:i:s' (string) olarak dönecekler.
     */
    protected $casts = [
        'goal_date' => 'date',
    ];

    /**
     * Bu görevin ait olduğu ana kategori (Proje).
     */
    public function goalCategory(): BelongsTo
    {
        return $this->belongsTo(GoalCategory::class);
    }
}