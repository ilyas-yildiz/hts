<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bu, 'tasks' tablosunu V3'e (Saatli Ajanda) geçirir.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            
            // 1. YENİ: 'start_time' (Başlangıç Saati) sütununu ekle.
            // 'nullable()' (boş bırakılabilir) -> "Tüm Gün" görevleri için.
            $table->time('start_time')->nullable()->after('goal_date');

            // 2. YENİ: 'end_time' (Bitiş Saati) sütununu ekle.
            $table->time('end_time')->nullable()->after('start_time');

            // 3. ESKİ: 'time_label' (metin) sütununu kaldır.
            $table->dropColumn('time_label');
        });
    }

    /**
     * Reverse the migrations.
     * Bu, değişikliği geri alır.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // 1. ESKİ: 'time_label' sütununu geri ekle
            $table->string('time_label')->nullable()->after('goal_date');

            // 2. YENİ: 'end_time' sütununu kaldır
            $table->dropColumn('end_time');
            
            // 3. YENİ: 'start_time' sütununu kaldır
            $table->dropColumn('start_time');
        });
    }
};