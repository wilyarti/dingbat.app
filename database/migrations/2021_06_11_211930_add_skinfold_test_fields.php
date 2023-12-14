<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkinfoldTestFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('body_measurement', function (Blueprint $table) {
            $table->boolean('sex')->nullable();
            $table->double('chest_skinfold')->nullable();
            $table->double('abdominal_skinfold')->nullable();
            $table->double('thigh_skinfold')->nullable();
            $table->double('suprailiac_skinfold')->nullable();
            $table->double('axillary_skinfold')->nullable();
            $table->double('lower_back_skinfold')->nullable();
            $table->double('tricep_skinfold')->nullable();
            $table->double('subscapular_skinfold')->nullable();
            $table->double('calf_skinfold')->nullable();
            $table->double('bicep_skinfold')->nullable();
            //$table->double('weight_skinfold')->nullable();
            $table->double('age')->nullable();
            $table->double('parillo')->nullable();
            $table->double('jp7')->nullable();
            $table->double('durnin')->nullable();
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
            $table->drop('sex');
            $table->drop('chest_skinfold');
            $table->drop('abdominal_skinfold');
            $table->drop('thigh_skinfold');
            $table->drop('suprailiac_skinfold');
            $table->drop('axillary_skinfold');
            $table->drop('lower_back_skinfold');
            $table->drop('tricep_skinfold');
            $table->drop('subscapular_skinfold');
            $table->drop('calf_skinfold');
            $table->drop('bicep_skinfold');
            //$table->drop('weight_skinfold');
            $table->drop('age');
            $table->drop('parillo');
            $table->drop('jp7');
            $table->drop('durnin');
        });
    }
}
