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
            // 'title' sütunundan sonra 'is_completed' sütununu ekle
            $table->boolean('is_completed')->default(false)->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('weekly_goals', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });
    }
};
