<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('goal', function (Blueprint $table) {
            $table->increments('goal_id');
            $table->string('goal_name');
            $table->string('goal_type_name');
            $table->text('goal_description');
            $table->integer('user_id');
            $table->string('table_name');
            $table->string('table_key');
            $table->double('goal_value');
            $table->timestamp('date');
            $table->timestamps();

            $table->string('table_primary_name')->nullable();
            $table->string('table_primary_key')->nullable();
            $table->string('table_primary_value')->nullable();
            $table->integer('table_primary_target')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goal');
    }
}
