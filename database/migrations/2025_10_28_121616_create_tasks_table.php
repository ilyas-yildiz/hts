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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // Hiyerarşik bağ: Bu görev, bir günlük hedefe aittir.
            $table->foreignId('daily_goal_id')->constrained()->onDelete('cascade');
            $table->string('time_label'); // örn: "09:00 - 10:00"
            $table->text('task_description'); // örn: "Proje Planı Revize"
            $table->boolean('is_completed')->default(false); // Tamamlandı mı?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
