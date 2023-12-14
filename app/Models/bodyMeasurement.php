<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bodyMeasurement extends Model
{
    /*
     * $table->increments('body_measurement_id');
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
           $table->boolean('sex')->nullable();
            $table->double('chest_skinfold')->nullable();
            $table->double('abdominal_skinfold')->nullable();
            $table->double('thigh_skinfold')->nullable();
            $table->double('suprailiac_skinfold')->nullable();
            $table->double('axillary_skinfold')->nullable();
            $table->double('lowerBack_skinfold')->nullable();
            $table->double('tricep_skinfold')->nullable();
            $table->double('subscapular_skinfold')->nullable();
            $table->double('calf_skinfold')->nullable();
            $table->double('bicep_skinfold')->nullable();
            //$table->double('weight_skinfold')->nullable();
            $table->double('age_skinfold')->nullable();
            $table->double('parillo')->nullable();
            $table->double('jp7')->nullable();
            $table->double('durnin')->nullable();
            $table->timestamps();
     */


    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'body_measurement_id';
    protected $table = 'body_measurement';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = [
        'user_id', 'plan_id', 'body_height', 'body_weight',
        'body_fat_percentage', 'visceral_fat_level', 'body_fat_mass',
        'bmi', 'bmr', 'neck_circumference',
        'chest_circumference', 'abdomen_circumference',
        'hip_circumference', 'left_bicep_circumference', 'right_bicep_circumference',
        'left_thigh_circumference', 'right_thigh_circumference', 'left_calf_circumference',
        'right_calf_circumference', 'date',
        'sex', 'chest_skinfold', 'abdominal_skinfold', 'thigh_skinfold',
        'suprailiac_skinfold', 'axillary_skinfold', 'lower_back_skinfold',
        'tricep_skinfold', 'subscapular_skinfold', 'calf_skinfold',
        'bicep_skinfold', 'age_skinfold', 'parillo', 'jp7', 'durnin',
    ];
    protected $casts = [
        'date' => 'date',
    ];
}
