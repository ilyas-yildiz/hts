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
        Schema::create('goal_categories', function (Blueprint $table) {
            $table->id();
            // Bu proje sadece size ait olacağı için, `users` tablosundaki
            // varsayılan (ID=1) kullanıcıya bağlayabiliriz.
            // (Sail'i ilk kurduğunuzda 'users' tablosu boş gelir, 
            // sonrasında oraya bir kullanıcı eklememiz gerekecek)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // örn: Kişisel Gelişim
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_categories');
    }
};
