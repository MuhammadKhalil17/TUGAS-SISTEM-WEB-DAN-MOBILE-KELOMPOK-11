<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('bookmarks', function (Blueprint $table) {
        $table->id();
        // Menghubungkan resep favorit dengan ID user yang menyimpannya
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Menyimpan ID unik resep dari Spoonacular API agar bisa ditarik lagi detailnya nanti
        $table->unsignedBigInteger('spoonacular_recipe_id');
        $table->string('title'); // Judul resep makanan
        $table->string('image')->nullable(); // URL gambar makanan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
