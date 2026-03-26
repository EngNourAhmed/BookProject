<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $writers = User::where('role', 'writer')->get();

        if ($writers->isEmpty()) {
            return;
        }

        foreach ($writers as $writer) {
            // Create 3 articles for each writer
            foreach (range(1, 3) as $i) {
                Article::create([
                    'user_id' => $writer->id,
                    'title'   => "Test Article $i by " . $writer->name,
                    'content' => "This is a sample content for test article $i. It contains some informative text for testing the dashboard UI and layout.",
                    'status'  => collect(['active', 'pending', 'rejected'])->random(),
                    'published_at' => now()->subDays(rand(1, 10)),
                ]);
            }
        }
    }
}
