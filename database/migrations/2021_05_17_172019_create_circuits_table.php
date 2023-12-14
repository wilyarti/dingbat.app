<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCircuitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circuit', function (Blueprint $table) {
            $table->increments('circuit_id');
            $table->string('circuit_name');
            $table->string('circuit_link');
            $table->integer('equipment_id');
            $table->integer('number_of_exercises');
            $table->text('exercise_list');
            $table->text('exercise_types');
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
        Schema::dropIfExists('circuit');
    }
}
