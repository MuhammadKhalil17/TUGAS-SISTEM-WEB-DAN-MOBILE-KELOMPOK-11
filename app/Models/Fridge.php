<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fridge extends Model
{
    // Mengizinkan kolom 'user_id' dan 'name' diisi secara massal
    protected $fillable = ['user_id', 'name'];

    // Relasi balik: Bahan makanan ini milik seorang user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}