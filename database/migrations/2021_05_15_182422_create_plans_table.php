<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * plan_id: Int <pk>
price: Real
plan_name: String
owner: User
weeks: Weeks []
number_of_weeks: Int
         */
        Schema::create('plan', function (Blueprint $table) {
            $table->increments('plan_id');
            $table->double('price');
            $table->string('plan_name');
            $table->integer('owner');
            $table->integer('number_of_weeks');
            $table->text('weeks');
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
        Schema::dropIfExists('plan');
    }
}
