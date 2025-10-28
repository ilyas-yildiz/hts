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
        Schema::create('annual_goals', function (Blueprint $table) {
            $table->id();
            // Hiyerarşik bağ: Bu yıllık hedef, bir ana kategoriye aittir.
            $table->foreignId('goal_category_id')->constrained()->onDelete('cascade');
            $table->integer('year'); // 1, 2, 3, 4, 5
            $table->string('period_label'); // örn: "Eylül 2026 Sonu"
            $table->text('title'); // örn: "Yılda 50 kitap okuma hedefi"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_goals');
    }
};
