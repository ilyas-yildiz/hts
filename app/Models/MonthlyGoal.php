<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyGoal extends Model
{
    use HasFactory;

    protected $fillable = ['annual_goal_id', 'month_label', 'title'];

    /**
     * Bu aylık hedefin ait olduğu yıllık hedef.
     */
    public function annualGoal(): BelongsTo
    {
        return $this->belongsTo(AnnualGoal::class);
    }

    /**
     * Bu aylık hedefe bağlı tüm haftalık hedefler.
     */
    public function weeklyGoals(): HasMany
    {
        return $this->hasMany(WeeklyGoal::class);
    }
}
