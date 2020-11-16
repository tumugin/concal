<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 20)->unique();
            $table->string('name', 100);
            $table->string('password', 512);
            $table->string('email', 256)->unique();
            $table->string('user_privilege', 100);
            $table->rememberToken();
            $table->timestamps();
            $table->index('user_name', 'user_name');
            $table->index('email', 'email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
