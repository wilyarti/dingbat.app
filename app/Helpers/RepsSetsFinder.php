<?php


namespace App\Helpers;


use App\Models\muscle;
use App\Models\workout;

class RepsSetsFinder
{
    public function findRepsSets(workout $workout)
    {
        /*
        * Get workout percentages for today....
        */
        $muscles = muscle::all();
        $sets = [];
        $reps = [];
        if ($workout) {
            for ($i = 0; $i < $workout->number_of_exercises; $i++) {
                if (isset($workout->exercises_sets[$i])) {
                    $sets[$i] = intval($workout->exercises_sets[$i]);
                    if (preg_match("/-/", $workout->exercises_reps[$i])) {
                        $range = explode("-", $workout->exercises_reps[$i]);
                        if (intval($range[1])) {
                            $reps[$i] = $range[1];
                        }
                    } elseif (is_numeric($workout->exercises_reps[$i])) {
                        $range = intval($workout->exercises_reps[$i]);
                        $reps[$i] = $range;
                    }
                }
            }
        }
        return (['reps' => $reps, 'sets' => $sets]);
    }
}
