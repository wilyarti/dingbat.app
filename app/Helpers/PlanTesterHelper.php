<?php


namespace App\Helpers;


use App\Models\active_plan;
use App\Models\exercise;
use App\Models\plan;
use App\Models\week;
use App\Models\set;
use App\Models\workout;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class planTesterHelper
{
    public function testPlan($planId, $userId, $timeZone, $cleanUp)
    {

        //$start_date = new DateTime("11/22/2021", $timeZone);
        $start_date = new DateTime("05/24/2021", $timeZone);
        $plan = plan::find($planId);


        echo "Seeding for ($planId)$plan->plan_name for $userId.\n";
        $active_plan = active_plan::create([
            'plan' => $plan->plan_id,
            'start_date' => $start_date,
            'end_date' => $start_date,
            'user' => $userId,
        ]);
        $active_plan->save();

        $baseWeight = 100;
        $we = 0;
        $wo = 0;
        $ex = 0;
        $plan = plan::find($planId);
        $todayFinder = new DateFinder;
        $error = 0;
        $setIds = [];
        foreach ($plan['weeks'] as $key => $weekId) {
            $week = week::find($weekId);
            $we++;

            foreach ($week['workouts'] as $subKey => $workoutId) {
                $workout = workout::find($workoutId);
                $wo++;
                $today = $todayFinder->findDateFromWorkoutDayNoAuth($plan, $week, $workout, $userId, $timeZone);
                if (!$today) {
                    //echo $workoutId . ":$weekId is null \n";
                    $error++;
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
                        $newSet->plan_id = $planId;
                        $newSet->user_id = $userId;
                        $newSet->workout_id = $workoutId;
                        $newSet->week_id = $weekId;
                        $newSet->exercise_id = $exerciseId;
                        $newSet->exercises_index = $subSubKey;
                        $newSet->weight = $weight;
                        $newSet->reps = $reps;
                        $newSet->date = $today;
                        $newSet->one_rep_max = $oneRm;
                        $newSet->save();
                        if ($cleanUp) {
                            $newSet->delete();
                        }
                        //array_push($setIds, $newSet->set_id);
                        //echo "E($exerciseId): $weight x $reps\n";
                    }
                }
                $baseWeight += 2.5;
            }
        }
        echo "Plan: " . $plan->plan_name . "\n";
        echo "Start date: " . $start_date->format(DATE_RFC1123) . "\n";
        echo "Number of weeks: " . $we . "\n";
        echo "Number of workouts: " . $wo . "\n";
        echo "Number of exercises: " . $ex . "\n";
        echo "Errors: $error\n";
        return $error;
    }
}
