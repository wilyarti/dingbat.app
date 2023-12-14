<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercise_setting', function (Blueprint $table) {
            $table->increments('exercise_setting_id');
            $table->integer('user');
            $table->integer('exercise_setting_type');
            $table->text('exercise_settings_key');
            $table->text('exercise_settings_value');
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
        Schema::dropIfExists('exercise_setting');
    }
}
