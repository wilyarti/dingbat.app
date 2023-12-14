<?php


namespace App\Helpers;


use App\Models\active_plan;
use App\Models\plan;
use App\Models\set;
use App\Models\User;
use App\Models\week;
use App\Models\workout;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class WeekStats
{
    public $weekStats;
    public $slotVolume;
    public $dailySlotVolume;
    public $weekStartAndEnd;
    public $weekStartAndEndStr;
    public $compliance;
    public $targetCompliance;
    public $workouts;
    public $weeks;
    public $dailyVolume;
    public $activePlan;
    private function order_date($a1,$b1) {
        $format = 'd M y';
        $a = strtotime(date_format(DateTime::createFromFormat($format, $a1), 'Y-m-d H:i:s'));
        $b = strtotime(date_format(DateTime::createFromFormat($format, $b1), 'Y-m-d H:i:s'));
        if ($a == $b)
        {
            return 0;
        }
        else if ($a > $b)
        {
            return 1;
        }
        else {
            return -1;
        }
    }
    public function statsForWeeks(user $user, plan $plan, active_plan $activePlan)
    {
        $this->plan = $plan;
        $this->activePlan = $activePlan;
        // Our Set database... used for calculating weekly volume, compliance etc.
        $this->sets = set::whereIn('week_id', $this->plan->weeks)->get();
        foreach ($this->sets as $set) {
            // Initialise our values
            if (!isset($this->weekStats[$set->week_id])) {
                $this->weekStats[$set->week_id]['sets'] = null;
                $this->weekStats[$set->week_id]['reps'] = null;
                $this->weekStats[$set->week_id]['volume'] = null;
                $this->weekStats[$set->week_id][$set->workout_id] = [];
            }
            // Tally up each workout slot to get weekly compliance figure....
            if (!isset($this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index])) {
                $this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index] = [];
                $this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index]['completedSets'] = 1;
                $this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index]['totalReps'] = $set->reps;
                $this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index]['totalVolume'] = $set->weight * $set->reps;

            } else {
                $this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index]['completedSets']++;
                $this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index]['totalReps'] += $set->reps;
                $this->weekStats[$set->week_id][$set->workout_id][$set->exercises_index]['totalVolume'] += $set->weight * $set->reps;
            }
            // Store our slot volume.
            if (!isset($this->slotVolume[$set->week_id][$set->exercises_index])) {
                $this->slotVolume[$set->week_id][$set->exercises_index] = $set->weight * $set->reps;
            } else {
                $this->slotVolume[$set->week_id][$set->exercises_index] += $set->weight * $set->reps;
            }
            $this->weekStats[$set->week_id]['sets']++;
            $this->weekStats[$set->week_id]['reps'] += $set->reps;
            $this->weekStats[$set->week_id]['volume'] += ($set->reps * $set->weight);

            // Calculate daily volume by date index
            // i.e 20/08/2020 = 3030
            // can referenced like volume[weekid]['20/08/2020'] - makes graphing easy
            $dateString = date_format(new DateTime($set->date, $user->time_zone), 'd M y');

            if (!isset($this->dailyVolume[$dateString])) {
                $this->dailyVolume[$dateString] = ($set->reps * $set->weight);
            } else {
                $this->dailyVolume[$dateString] += ($set->reps * $set->weight);
            }

            // Calculate daily slot volume...
            // i.e [2][20/8/20][0] => foo
            if (!isset($this->dailySlotVolume[$set->week_id][$set->exercises_index][$dateString])) {
                $this->dailySlotVolume[$set->week_id][$set->exercises_index][$dateString] = ($set->reps * $set->weight);
                //var_dump(value($this->dailySlotVolume[$set->exercises_index][$dateString]));
            } else {
                $this->dailySlotVolume[$set->week_id][$set->exercises_index][$dateString] += ($set->reps * $set->weight);
            }
        }
        $weekCount = 0;
        $dayCount = 0;
        foreach ($this->plan->weeks as $weekId) {
            $week = week::find($weekId);
            //var_dump($this->weekChartSelected);

            //Calculate week start and end
            // Create _new_ instance of DateTime or it will just modify a reference.
            $this->weekStartAndEnd[$weekId]['start'] = new DateTime($this->activePlan->start_date, new DateTimeZone($user->timezone));
            $this->weekStartAndEnd[$weekId]['end'] = new DateTime($this->activePlan->start_date, new DateTimeZone($user->timezone));

            $this->weekStartAndEnd[$weekId]['start']->modify('+' . ($dayCount) . ' day');
            $this->weekStartAndEnd[$weekId]['end']->modify('+' . ($dayCount + $week->number_of_days) . ' day');

            $this->weekStartAndEndStr[$weekId]['start'] = $this->weekStartAndEnd[$weekId]['start']->format('d F');
            $this->weekStartAndEndStr[$weekId]['end'] = $this->weekStartAndEnd[$weekId]['end']->format('d F Y');


            /*
             * Increment counters for above
             */
            $weekCount++;
            $dayCount += $week->number_of_days;

            foreach ($week->workouts as $workoutId) {
                $workout = workout::find($workoutId);
                $repsAndSetsFinder = new RepsSetsFinder();
                $repsSets = $repsAndSetsFinder->findRepsSets($workout);
                if (!isset($this->workouts[$weekId])) {
                    $this->workouts[$weekId] = [];
                }
                array_push($this->workouts[$weekId], $workout);

                // Calculate week compliance. Add up each set in each slot to see if the number matches. Ignore extra.
                // Can't calculate on reps only sets...
                if (isset($this->weekStats[$weekId][$workoutId])) {
                    foreach ($this->weekStats[$weekId][$workoutId] as $key => $slot) {
                        if (!isset($key)) {
                            error_log($key . $slot['completedSets'] . "Not set\n");
                        }

                        if ($repsSets['sets'][$key] >= $slot['completedSets']) {
                            if (isset($repsSets['reps'][$key])) { // TODO implement time on exercises
                                if ($repsSets['reps'][$key] * $repsSets['sets'][$key] < $slot['totalReps']) {
                                    // negative point
                                } else {
                                    if (!isset($this->compliance[$weekId])) {
                                        $this->compliance[$weekId] = 1;
                                    } else {
                                        $this->compliance[$weekId]++;
                                    }
                                }
                            }
                        } else { // TODO fix this assumption
                            error_log("Cardio?\n");
                            // can't do cardio yet so just assuming cardio slot is a compliance
                            if (!isset($this->compliance[$weekId])) {
                                $this->compliance[$weekId] = 1;
                            } else {
                                $this->compliance[$weekId]++;
                            }
                        }
                    }
                }
                if (!isset($this->targetCompliance[$weekId])) {
                    $this->targetCompliance[$weekId] = $workout->number_of_exercises;
                } else {
                    $this->targetCompliance[$weekId] += $workout->number_of_exercises;
                }
            }
            $this->weeks[$week->week_id] = $week;
        }


        uksort($this->dailyVolume, [$this,"order_date"]);
        return ['weeks' => $this->weeks,
            'weekStats' => $this->weekStats,
            'dailyVolume' => $this->dailyVolume,
            'dailySlotVolume' => $this->dailySlotVolume,
            'weekStartAndEnd' => $this->weekStartAndEnd,
            'weekStartAndEndStr' => $this->weekStartAndEndStr,
            'compliance' => $this->compliance,
            'targetCompliance' => $this->targetCompliance,
            'workouts' => $this->workouts,
            'slotVolume' => $this->slotVolume
        ];

    }


    public function walkPlan($planID)
    {
        error_log("Plan ID: $planID");
        $plan = plan::find($planID);
        $weeks = week::findMany($plan->weeks);

        error_log("Plan weeks: ");
        error_log(var_dump($plan->weeks[0]));
        error_log("Weeks: ");
        error_log(var_dump($weeks));
        $workouts = [];
        foreach ($weeks as $week) {
            $workouts[$week->week_id] = workout::findMany($week->workouts);
        }

        return ['weeks' => $weeks,
            'workouts' => $workouts,];
    }

    public function walkWeek($weekID)
    {
        $week = week::find($weekID);

        $workouts[$week->week_id] = workout::findMany($week->workouts);

        return ['week' => $week,
            'workouts' => $workouts,];
    }
}
