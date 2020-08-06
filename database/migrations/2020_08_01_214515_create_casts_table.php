<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casts', function (Blueprint $table) {
            $table->id('cast_id');
            $table->string('cast_name', 100);
            $table->string('cast_short_name', 50);
            $table->string('cast_twitter_id', 50);
            $table->string('cast_description');
            $table->string('cast_color', 10);
            $table->boolean('cast_disabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('casts');
    }
}
