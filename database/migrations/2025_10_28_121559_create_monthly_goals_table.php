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
        Schema::create('monthly_goals', function (Blueprint $table) {
            $table->id();
            // Hiyerarşik bağ: Bu aylık hedef, bir yıllık hedefe aittir.
            $table->foreignId('annual_goal_id')->constrained()->onDelete('cascade');
            $table->string('month_label'); // örn: "Ekim 2025"
            $table->text('title'); // örn: "4 kitap bitir"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_goals');
    }
};
