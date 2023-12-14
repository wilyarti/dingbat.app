<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week', function (Blueprint $table) {
            $table->increments('week_id');
            $table->integer('number_of_days');
            $table->integer('number_of_workouts');
            $table->text('workouts');
            $table->text('workouts_indexs');
            $table->text('cardio');
            $table->text('cardio_indexs');
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
        Schema::dropIfExists('week');
    }
}
