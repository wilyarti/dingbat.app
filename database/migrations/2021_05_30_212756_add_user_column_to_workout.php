<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserColumnToWorkout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workout', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
            $table->string('workout_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workout', function (Blueprint $table) {
            $table->dropColumn('user_id')->nullable();
            $table->dropColumn('workout_name')->nullable();
        });
    }
}
