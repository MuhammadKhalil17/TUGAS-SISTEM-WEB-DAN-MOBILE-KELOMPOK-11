<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up(): void
{
    Schema::create('fridges', function (Blueprint $table) {
        $table->id();
        // Menghubungkan bahan makanan dengan ID user yang punya kulkas
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Nama bahan makanan (misal: egg, tomato, onion)
        $table->string('name');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fridges');
    }
};
