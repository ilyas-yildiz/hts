<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bu, 'tasks' tablosunu V2'ye geçirir.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // 1. YENİ: 'goal_date' (tarih) sütununu ekle.
            // Bu, 'daily_goal_id'nin yerini alacak.
            $table->date('goal_date')->nullable()->after('id');

            // 2. YENİ: 'goal_category_id' (proje) sütununu ekle.
            // Bu, görevin hangi ana hedefe bağlı olduğunu gösterir.
            $table->foreignId('goal_category_id')
                  ->nullable()
                  ->after('goal_date')
                  ->constrained('goal_categories')
                  ->onDelete('cascade'); // Kategori silinirse, görevler de silinsin

            // 3. ESKİ: 'daily_goal_id' sütununu ve foreign key'ini kaldır.
            // (Önce foreign key'i kaldırmalıyız)
            $table->dropForeign(['daily_goal_id']);
            $table->dropColumn('daily_goal_id');
        });
    }

    /**
     * Reverse the migrations.
     * Bu, değişikliği geri alır.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // 1. ESKİ: 'daily_goal_id' sütununu geri ekle
            $table->foreignId('daily_goal_id')
                  ->nullable()
                  ->constrained('daily_goals')
                  ->onDelete('cascade');

            // 2. YENİ: 'goal_category_id' sütununu ve foreign key'ini kaldır
            $table->dropForeign(['goal_category_id']);
            $table->dropColumn('goal_category_id');

            // 3. YENİ: 'goal_date' sütununu kaldır
            $table->dropColumn('goal_date');
        });
    }
};