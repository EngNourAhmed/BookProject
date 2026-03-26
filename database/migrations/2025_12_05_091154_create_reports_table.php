<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // المستخدم اللي قدم البلاغ
            $table->foreignId('reporter_id')
                ->constrained('users')
                ->onDelete('cascade');

            // المستخدم اللي تم الإبلاغ عنه
            $table->foreignId('reported_id')
                ->constrained('users')
                ->onDelete('cascade');

            // المقال اللي تم الإبلاغ عنه
            $table->foreignId('article_id')
                ->constrained('articles')
                ->onDelete('cascade');


            // الحالة
            $table->enum('status', ['reviewing', 'resolved', 'blocked'])
                ->default('reviewing');

            // سبب البلاغ
            $table->text('reason');


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
