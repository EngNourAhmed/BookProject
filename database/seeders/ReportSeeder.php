<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use App\Models\Article;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $articles = Article::all();
        $reporters = User::whereIn('role', ['reader', 'writer'])->get();

        if ($articles->isEmpty() || $reporters->isEmpty()) {
            return;
        }

        foreach (range(1, 10) as $i) {
            $article = $articles->random();
            $reporter = $reporters->where('id', '!=', $article->user_id)->random();

            Report::create([
                'reporter_id' => $reporter->id,
                'reported_id' => $article->user_id,
                'article_id'  => $article->id,
                'reason'      => "Sample report reason $i: This content contains some inappropriate information or violates testing policies.",
                'status'      => collect(['reviewing', 'resolved', 'blocked'])->random(),
            ]);
        }
    }
}
