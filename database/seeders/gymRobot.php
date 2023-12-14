<?php

namespace Database\Seeders;

use App\Helpers\DateFinder;
use App\Helpers\RepMax;
use App\Models\active_plan;
use App\Models\exercise;
use App\Models\plan;
use App\Models\week;
use App\Models\workout;
use App\Models\set;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class gymRobot extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = 1;
        $plan_id = 53;

        echo "Seeding for $user_id plan $plan_id\n";


        $timeZone = new \DateTimeZone("Australia/Brisbane");
        $start_date = new DateTime("06/07/2021", $timeZone);
        $active_plan = active_plan::create([
            'plan' => $plan_id,
            'start_date' => $start_date,
            'end_date' => $start_date,
            'user' => $user_id,
        ]);
        $active_plan->save();

        $baseWeight = 100;
        $we = 0;
        $wo = 0;
        $ex = 0;
        $plan = plan::find($plan_id);
        $todayFinder = new DateFinder;

        foreach ($plan['weeks'] as $key => $weekId) {
            $week = week::find($weekId);
            $we++;

            foreach ($week['workouts'] as $subKey => $workoutId) {
                $workout = workout::find($workoutId);
                $wo++;
                $today = $todayFinder->findDateFromWorkoutDayNoAuth($plan, $week, $workout, $user_id, $timeZone);
                if (!$today) {
                    echo $workoutId . ":$weekId is null \n";
                }


                foreach ($workout['exercises'] as $subSubKey => $exerciseId) {

                    $exercise = exercise::find($exerciseId);
                    $ex++;
                    if ($workout['adapter']) {
                        //echo "Adapter\n";
                        $weight = $baseWeight * ($workout['adapter_array']['one_rep_max'][$subSubKey] / 100);
                    } else {
                        $weight = $baseWeight;
                    }
                    $reps = $workout['exercises_reps'][$subSubKey];

                    for ($i = 0; $i < $workout['exercises_sets'][$subSubKey]; $i++) {
                        $oneRepMaxCalculator = new RepMax();
                        $oneRm = $oneRepMaxCalculator->calculateOneRepMax($weight, $reps);
                        $newSet = new set;
                        $newSet->plan_id = $plan_id;
                        $newSet->user_id = $user_id;
                        $newSet->workout_id = $workoutId;
                        $newSet->week_id = $weekId;
                        $newSet->exercise_id = $exerciseId;
                        $newSet->exercises_index = $subSubKey;
                        $newSet->weight = $weight;
                        $newSet->reps = $reps;
                        $newSet->date = $today;
                        $newSet->one_rep_max = $oneRm;
                        $newSet->save();
                        echo "E($exerciseId): $weight x $reps\n";
                    }
                }
                $baseWeight += 2.5;
            }
        }
        echo "Start date: " . $start_date->format(DATE_RFC1123) . "\n";
        echo "Number of weeks: " . $we . "\n";
        echo "Number of workouts: " . $wo . "\n";
        echo "Number of exercises: " . $ex . "\n";
    }
}
