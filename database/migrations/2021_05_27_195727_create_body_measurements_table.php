<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBodyMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * body_measurements_id: Int <pk>
measurement_unit: String [metric, Imperial]
bodyHeight: Real <nullable>
bodyWeight: Real <nullable>
bodyFatPercentage: Real <nullable>
viceralFatLevel: Real <nullable>
bodyFatMass: Real <nullable>
BMI: Real <nullable>
basalMetabolicRate: Real <nullable>
neckCircumference: Real <nullable>
chestCircumference: Real <nullable>
abdomentCircumference: Real <nullable>
HipCircumference: Real <nullable>
leftBicepCircumference: Real <nullable>
leftThighCircumference: Real <nullable>
rightThighCircumference: Real <nullable>
leftCalfCircumference: Real <nullable>
rightCalfCircumference: Real <nullable>

         */
        Schema::create('body_measurement', function (Blueprint $table) {
            $table->increments('body_measurement_id');
            $table->integer('user_id');
            $table->integer('plan_id')->nullable();
            $table->double('body_height')->nullable();
            $table->double('body_weight')->nullable();
            $table->double('body_fat_percentage')->nullable();
            $table->double('visceral_fat_level')->nullable();
            $table->double('body_fat_mass')->nullable();
            $table->double('bmi')->nullable();
            $table->double('bmr')->nullable();
            $table->double('neck_circumference')->nullable();
            $table->double('chest_circumference')->nullable();
            $table->double('abdomen_circumference')->nullable();
            $table->double('hip_circumference')->nullable();
            $table->double('left_bicep_circumference')->nullable();
            $table->double('right_bicep_circumference')->nullable();
            $table->double('left_thigh_circumference')->nullable();
            $table->double('right_thigh_circumference')->nullable();
            $table->double('left_calf_circumference')->nullable();
            $table->double('right_calf_circumference')->nullable();
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
        Schema::dropIfExists('body_measurement');
    }
}
