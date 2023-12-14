<?php


namespace App\Helpers;


use App\Models\exercise;
use App\Models\plan;
use App\Models\week;
use App\Models\workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cloner
{
    public $planSettings = [
        'plan_id' => null,
        'price' => 0,
        'plan_name' => 'Custom Plan',
        'owner' => 0,
        'number_of_weeks' => 4,
        'weeks' => [],
        'description' => "This modal doesn't update due to a bug but will submit correctly."
    ];
    public $weekSettings = [
        'week_id' => null,
        'week_name' => 'Example Week',
        'number_of_days' => 7,

        'number_of_workouts' => 5,
        'workouts' => [1, 2, 3, 4, 5],
        'workouts_indexs' => [0, 1, null, 2, 4, 5, null, null],

        'number_of_cardio' => 2,
        'cardio' => [null, null, null, null, null, null, null],
        'cardio_indexs' => [null, null, 0, null, null, 1, null],
    ];
    public $workoutSettings = [
        'workout_id' => null,
        'workout_name' => 'Workout Name',
        'muscles' => [],

        'number_of_circuits' => 0,
        'circuits' => [],
        'circuit_sets' => [],
        'circuit_reps' => [],

        'number_of_exercises' => 4,
        'exercises' => [],
        'exercises_sets' => [],
        'exercises_reps' => [],

        'adapter' => 0,
        'adapter_array' => [],
    ];

    public function clonePlan($planId, $userId)
    {
        $originalPlan = plan::find($planId);
        if (!$originalPlan) {
            return null;
        }

        $weekIds = [];

        /*
         * Transverse weeks
         */
        foreach ($originalPlan['weeks'] as $weekId) {
            $originalWeek = week::find($weekId);
            $week = new week;
            $workoutIds = [];

            /*
             * Transverse workouts
             */
            foreach ($originalWeek['workouts'] as $workoutId) {
                $originalWorkout = workout::find($workoutId);

                /*
                 * Create new workout
                 */
                $workout = new workout;
                foreach ($this->workoutSettings as $key => $value) {
                    if ($key != 'workout_id') {
                        $workout[$key] = $originalWorkout[$key];
                    }
                }
                $workout->save();
                array_push($workoutIds, $workout->workout_id);
            }

            /*
             * Copy our week
             */
            foreach ($this->weekSettings as $key => $value) {
                if ($key == 'workouts') {
                    $week[$key] = $workoutIds;
                } elseif ($key != "week_id") {
                    $week[$key] = $originalWeek[$key];
                }
            }

            $week->save();
            array_push($weekIds, $week->week_id);
        }
        $plan = new plan;

        foreach ($this->planSettings as $key => $value) {
            if ($key == "weeks") {
                $plan[$key] = $weekIds;
            } elseif ($key != "plan_id") {
                $plan[$key] = $originalPlan[$key];
            }
        }
        $plan['plan_name'] = $plan['plan_name'];
        $plan['description'] = "<h3>CLONE OF " . $originalPlan->plan_name . " </h3><br/>" . $originalPlan['description'];
        $plan['is_clone'] = true;
        $plan->user_id = $userId;
        $plan->save();
        return $plan->plan_id;
    }
}
