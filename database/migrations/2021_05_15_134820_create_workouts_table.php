<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * workout_id: Int <pk>
            number_of_exercises: Int
            exercises: Exercise []
            exercises_sets: Int []
            exercises_reps: Int []

         */
        Schema::create('workout', function (Blueprint $table) {
            $table->increments('workout_id');
            $table->integer('workout_type')->default(0);
            $table->integer('number_of_exercises');
            $table->text('muscles');
            $table->text('exercises');
            $table->text('exercises_sets');
            $table->text('exercises_reps');
            $table->text('circuits')->nullable();
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
        Schema::dropIfExists('workout');
    }
}
