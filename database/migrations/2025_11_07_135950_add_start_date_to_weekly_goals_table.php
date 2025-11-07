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
        Schema::table('weekly_goals', function (Blueprint $table) {
            // 'week_label' sütunundan sonra, 'start_date' (Hafta Başlangıç Tarihi)
            // adında yeni bir tarih sütunu ekle.
            // 'nullable()' yapıyoruz ki mevcut (eski) veriler hata vermesin.
            $table->date('start_date')->nullable()->after('week_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_goals', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });
    }
};