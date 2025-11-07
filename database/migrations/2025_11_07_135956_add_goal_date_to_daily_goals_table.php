<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_goals', function (Blueprint $table) {
            // 'day_label' sütunundan sonra, 'goal_date' (Hedef Tarihi)
            // adında yeni bir tarih sütunu ekle.
            $table->date('goal_date')->nullable()->after('day_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_goals', function (Blueprint $table) {
            $table->dropColumn('goal_date');
        });
    }
};