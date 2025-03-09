<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Document;
use App\Models\DocumentExpiryReminder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        
        User::factory()->count(5)->state(['role' => 'hr'])->create();
        User::factory()->count(20)->state(['role' => 'employee'])->create();
        Category::factory()->count(4)->create();
        Document::factory()->count(50)->create();
        DocumentExpiryReminder::factory()->count(20)->create();
    }
}
