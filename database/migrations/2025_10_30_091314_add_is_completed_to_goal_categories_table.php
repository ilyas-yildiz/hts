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
        Schema::table('goal_categories', function (Blueprint $table) {
            // 'name' sütunundan sonra 'is_completed' sütununu ekle
            $table->boolean('is_completed')->default(false)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('goal_categories', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });
    }
};
