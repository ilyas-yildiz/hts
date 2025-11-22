<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // goal_category_id zaten vardı, onun altına diğerlerini ekleyelim
            
            $table->foreignId('annual_goal_id')->nullable()->after('goal_category_id')->constrained('annual_goals')->nullOnDelete();
            
            $table->foreignId('monthly_goal_id')->nullable()->after('annual_goal_id')->constrained('monthly_goals')->nullOnDelete();
            
            $table->foreignId('weekly_goal_id')->nullable()->after('monthly_goal_id')->constrained('weekly_goals')->nullOnDelete();
            
            // Eğer V3'e geçerken daily_goal_id'yi sildiysen burası onu geri getirir.
            // Eğer silmediysen hata vermemesi için kontrol edelim:
            if (!Schema::hasColumn('tasks', 'daily_goal_id')) {
                $table->foreignId('daily_goal_id')->nullable()->after('weekly_goal_id')->constrained('daily_goals')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['annual_goal_id']);
            $table->dropColumn('annual_goal_id');
            
            $table->dropForeign(['monthly_goal_id']);
            $table->dropColumn('monthly_goal_id');
            
            $table->dropForeign(['weekly_goal_id']);
            $table->dropColumn('weekly_goal_id');
            
            if (Schema::hasColumn('tasks', 'daily_goal_id')) {
                $table->dropForeign(['daily_goal_id']);
                $table->dropColumn('daily_goal_id');
            }
        });
    }
};
