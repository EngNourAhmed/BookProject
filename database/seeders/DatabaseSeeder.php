<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 2. Create Writers
        User::factory(5)->create([
            'role' => 'writer',
            'status' => 'active',
        ]);

        // 3. Create Readers
        User::factory(5)->create([
            'role' => 'reader',
            'status' => 'active',
        ]);

        // 4. Run Model Seeders
        $this->call([
            ArticleSeeder::class,
            ReportSeeder::class,
            SentNotificationSeeder::class,
        ]);
    }
}
