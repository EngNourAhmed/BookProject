<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
        ALTER TABLE users 
        MODIFY role ENUM('reader', 'writer', 'admin') 
        NOT NULL DEFAULT 'reader';
    ");
    }

    public function down()
    {
        DB::statement("
        ALTER TABLE users 
        MODIFY role ENUM('reader', 'writer', 'publisher', 'admin') 
        NOT NULL DEFAULT 'reader';
    ");
    }
};
