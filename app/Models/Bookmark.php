<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = ['user_id', 'spoonacular_recipe_id', 'title', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}