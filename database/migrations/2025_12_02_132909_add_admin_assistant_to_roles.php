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
        DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('reader', 'writer', 'admin', 'admin_assistant') DEFAULT 'reader'");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('reader', 'writer', 'admin') DEFAULT 'reader'");
    }
};
