<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Lab Assistant Account
        User::factory()->create([
            'name' => 'Asisten Laboratorium',
            'email' => 'asisten@kulkasku.com',
            'password' => \Illuminate\Support\Facades\Hash::make('asisten123'),
        ]);

        // Default Test User Account
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}
