<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // ربط المقال بالكاتب (User)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('title');
            $table->text('content')->nullable();


            // // سبب الرفض (إن وجد)
            // $table->text('rejection_reason')->nullable();

            // تاريخ النشر الفعلي (إن تم نشره)
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
