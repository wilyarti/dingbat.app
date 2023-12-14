<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJp3ColumnToBodyMeasurement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('body_measurement', function (Blueprint $table) {
            $table->double('jp3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('body_measurement', function (Blueprint $table) {
            $table->drop('jp3');
        });
    }
}
