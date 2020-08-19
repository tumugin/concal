<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name', 100);
        });
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_name', 100);
            $table->integer('store_group_id', false, true);
            $table->boolean('store_disabled');
            $table->timestamps();
        });
        Schema::create('store_casts', function (Blueprint $table) {
            $table->integer('store_id', false, true);
            $table->integer('cast_id', false, true);
            $table->timestamps();
            $table->primary(['store_id', 'cast_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_groups');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('store_casts');
    }
}
