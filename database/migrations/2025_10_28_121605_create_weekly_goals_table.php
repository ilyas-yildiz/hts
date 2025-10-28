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
        Schema::create('weekly_goals', function (Blueprint $table) {
            $table->id();
            // Hiyerarşik bağ: Bu haftalık hedef, bir aylık hedefe aittir.
            $table->foreignId('monthly_goal_id')->constrained()->onDelete('cascade');
            $table->string('week_label'); // örn: "1. Hafta (1-7 Ekim)"
            $table->text('title'); // örn: "1. Kitaba başla"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_goals');
    }
};
