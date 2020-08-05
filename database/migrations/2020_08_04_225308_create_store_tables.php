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
        Schema::create('store_group', function (Blueprint $table) {
            $table->id('store_group_id');
            $table->string('group_name', 100);
        });
        Schema::create('store', function (Blueprint $table) {
            $table->id('store_id');
            $table->string('store_name', 100);
            $table->integer('store_group_id', false, true);
            $table->boolean('store_disabled');
            $table->timestamps();
        });
        Schema::create('store_cast', function (Blueprint $table) {
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
        Schema::dropIfExists('store_group');
        Schema::dropIfExists('store');
        Schema::dropIfExists('store_cast');
    }
}
