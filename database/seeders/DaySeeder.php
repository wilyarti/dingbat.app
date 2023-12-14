<?php

namespace Database\Seeders;

use App\Models\cardio;
use App\Models\week;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [0,0,0,0,0,0,0];
        $week = new week;
        $week->number_of_days = 7;
        $week->number_of_workouts = 4;
        $week->workouts = [1,2,3,4];
        $week->workouts_indexs = [0,0,0,0,0,0,0];
        $week->cardio = [0];
        $week->cardio_indexs = [0];
        $week->save();
    }
}
