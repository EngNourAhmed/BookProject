<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateSentNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('sent_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('target')->comment('all, authors, readers, user');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->json('meta')->nullable();
            $table->string('channel')->default('database');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('sent_notifications');
    }
}
