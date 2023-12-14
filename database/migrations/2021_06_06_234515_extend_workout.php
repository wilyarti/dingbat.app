<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendWorkout extends Migration
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
            $table->text('adapter_array')->nullable;
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
            $table->drop('adapter_array');
        });
    }
}
