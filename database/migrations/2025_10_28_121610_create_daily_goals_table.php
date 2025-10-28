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
        Schema::create('daily_goals', function (Blueprint $table) {
            $table->id();
            // Hiyerarşik bağ: Bu günlük hedef, bir haftalık hedefe aittir.
            $table->foreignId('weekly_goal_id')->constrained()->onDelete('cascade');
            $table->string('day_label'); // örn: "Pazartesi"
            $table->text('title')->nullable(); // Bazı günlerin özel bir hedefi olmayabilir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_goals');
    }
};
