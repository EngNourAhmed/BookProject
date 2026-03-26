<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // بيانات أساسية للمستخدم
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // دور المستخدم
            // reader / writer / publisher / admin
            $table->enum('role', ['reader', 'writer', 'admin'])
                ->default('reader');

            // // حالة الحساب
            // $table->boolean('is_active')->default(true); // الحساب شغال او معطل

            // // الحظر
            // $table->boolean('is_banned')->default(false);
            // $table->timestamp('ban_until')->nullable(); // حظر مؤقت


            // مراجعة الناشر
            $table->boolean('publisher_approved')->default(false);
            // true → تم قبول الناشر ويقدر ينشر
            // false → ينتظر مراجعة الإدارة

            // للتحويل من كاتب إلى قارئ (تقييد نشر)
            $table->boolean('writing_restricted')->default(false);


            // بيانات إضافية (اختياري)
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
