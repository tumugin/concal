<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('screen_name')->primary();
            $table->string('screen_name', 20);
            $table->string('name', 100);
            $table->string('password', 512);
            $table->string('api_token', 512)->unique();
            $table->string('mail', 256);
            $table->rememberToken();
            $table->timestamps();
            $table->index('screen_name', 'screen_name');
            $table->index('mail', 'mail');
            $table->index('api_token', 'api_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
