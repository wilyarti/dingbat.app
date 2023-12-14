<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Set extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set', function (Blueprint $table) {
            $table->increments('set_id');
            $table->integer('user_id');
            $table->integer('plan_id')->nullable();
            $table->integer('week_id')->nullable();
            $table->integer('exercise_id');
            $table->integer('workout_id')->nullable();
            $table->integer('exercises_index')->nullable();
            $table->double('one_rep_max');
            $table->double('weight');
            $table->integer('reps');
            $table->timestamp('date');
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
        Schema::dropIfExists('set');

    }
}
