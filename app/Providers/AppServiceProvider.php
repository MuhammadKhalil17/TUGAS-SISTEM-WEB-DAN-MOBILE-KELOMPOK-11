<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 🔑 PROSES BINDING CONTRACT KE SERVICE IMPLEMENTASI
        
        // 1. Otentikasi
        $this->app->bind(
            \App\Contracts\AuthContract::class, 
            \App\Services\AuthService::class
        );

        // 2. Kulkas / Inventaris Bahan Pangan
        $this->app->bind(
            \App\Contracts\RefrigeratorContract::class, 
            \App\Services\RefrigeratorService::class
        );

        // 3. Pencarian Resep (Spoonacular Proxy)
        $this->app->bind(
            \App\Contracts\RecipeContract::class, 
            \App\Services\RecipeService::class
        );

        // 4. Resep Favorit / Bookmark
        $this->app->bind(
            \App\Contracts\FavoriteRecipeContract::class, 
            \App\Services\FavoriteRecipeService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}