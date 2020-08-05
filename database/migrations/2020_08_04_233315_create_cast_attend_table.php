<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastAttendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_attend', function (Blueprint $table) {
            $table->id('attend_id');
            $table->integer('cast_id', false, true);
            $table->integer('store_id', false, true);
            $table->dateTimeTz('start_time');
            $table->dateTimeTz('end_time');
            $table->string('attend_info');
            $table->integer('added_by_user_id', false, true);
            $table->index(['cast_id', 'start_time', 'end_time'], 'cast_to_attend');
            $table->index(['store_id', 'start_time', 'end_time'], 'store_to_cast_attend');
            $table->index(['added_by_user_id', 'start_time', 'end_time'], 'add_by_user_id_to_attend');
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
        Schema::dropIfExists('cast_attend');
    }
}
