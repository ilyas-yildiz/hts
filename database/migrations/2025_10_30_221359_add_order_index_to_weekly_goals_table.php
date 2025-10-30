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
            // 'is_completed' sütunundan sonra 'order_index' sütununu ekle
            $table->integer('order_index')->default(0)->after('is_completed');
        });
    }

    public function down(): void
    {
        Schema::table('weekly_goals', function (Blueprint $table) {
            $table->dropColumn('order_index');
        });
    }
};
