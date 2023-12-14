<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToWorkoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    /*
     * -
        number_of_circuits: Int
        circuits: Circutis [] nullable
        circuit_sets: Int [] nullable
        circuit_reps: Int []nullable
        circuits: Circuit [] nullable
        --
        adapter: Adapter <fk>
     */
    public function up()
    {
        Schema::table('workout', function (Blueprint $table) {
            $table->integer('number_of_circuits');
            $table->text('circuit_sets');
            $table->text('circuit_reps');
            //$table->text('circuits');
            $table->integer('adapter')->default(0); // No adapter
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
            $table->drop('number_of_circuits');
            $table->drop('circuit_sets');
            $table->drop('circuit_reps');
            //$table->drop('circuits');
            $table->drop('adapter');
        });
    }
}
