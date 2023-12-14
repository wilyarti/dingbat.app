<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_plan', function (Blueprint $table) {
            $table->increments('active_plan_id');
            $table->integer('plan');
            $table->integer('user');
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->useCurrent();
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
        Schema::dropIfExists('active_plan');
    }
}
