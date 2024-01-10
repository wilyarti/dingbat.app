<?php


namespace App\Helpers;


use App\Models\set;
use App\Models\workout;
use App\Models\plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanAdapter
{
    public function wendlerAdapter($planId, workout $workout, $userId)
    {
        $workoutTargets = [];

        foreach ($workout['exercises'] as $key => $exerciseId) {
            $oneRepMax = set::where('plan_id', $planId)
                ->where('reps', 1)
                ->where('user_id', $userId)
                ->where('exercise_id', $exerciseId)
                ->orderBy('one_rep_max', 'DESC')
                ->first();

            //var_dump($workout['adapter_array']['one_rep_max']);
            //var_dump($oneRepMax['one_rep_max']);
            if (isset($workout['adapter_array']['one_rep_max'][$key]) && isset($oneRepMax['one_rep_max'] )) {
                $workoutTargets[$key] = ($oneRepMax['one_rep_max'] * intval($workout['adapter_array']['one_rep_max'][$key])) / 100;
            } else {
                $workoutTargets[$key] = null;
                /*
                 *  activity()
                    ->inLog("debug")
                    ->log($userId . "warn: missing 1RM");
                 */
            }
        }

        return $workoutTargets;
    }
}
