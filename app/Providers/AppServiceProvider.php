<?php

namespace App\Providers;

use App\Contracts\AuthContract;
use App\Contracts\FavoriteRecipeContract;
use App\Contracts\RecipeContract;
use App\Contracts\RefrigeratorContract;
use App\Services\AuthService;
use App\Services\FavoriteRecipeService;
use App\Services\RecipeService;
use App\Services\RefrigeratorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan aplikasi service apa pun di sini.
     */
    public function register(): void
    {
        // 1. Binding untuk Autentikasi
        $this->app->bind(AuthContract::class, AuthService::class);

        // 2. Binding untuk Manajemen Kulkas
        $this->app->bind(RefrigeratorContract::class, RefrigeratorService::class);

        // 3. Binding untuk Fitur Resep Favorit
        $this->app->bind(FavoriteRecipeContract::class, FavoriteRecipeService::class);

        // 4. Binding untuk Fitur Pencarian Resep (Spoonacular)
        $this->app->bind(RecipeContract::class, RecipeService::class);
    }

    /**
     * Jalankan proses bootstrap service apa pun di sini.
     */
    public function boot(): void
    {
        //
    }
}