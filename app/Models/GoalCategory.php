<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoalCategory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name'];

    /**
     * Bu kategorinin ait olduğu kullanıcı.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bu kategoriye bağlı tüm yıllık hedefler.
     */
    public function annualGoals(): HasMany
    {
        return $this->hasMany(AnnualGoal::class);
    }
}
