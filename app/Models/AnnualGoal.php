<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualGoal extends Model
{
    use HasFactory;

    protected $fillable = ['goal_category_id', 'year', 'period_label', 'title'];

    /**
     * Bu yıllık hedefin ait olduğu ana kategori.
     */
    public function goalCategory(): BelongsTo
    {
        return $this->belongsTo(GoalCategory::class);
    }

    /**
     * Bu yıllık hedefe bağlı tüm aylık hedefler.
     * (Sadece 1. yıl için doldurulacak olsa da, ilişki tümü için geçerlidir)
     */
    public function monthlyGoals(): HasMany
    {
        return $this->hasMany(MonthlyGoal::class);
    }
}
