<?php


namespace App\Helpers;


use App\Models\active_plan;
use App\Models\plan;
use App\Models\week;
use App\Models\workout;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class DateFinder
{
    public function findDateFromWorkoutDayNoAuth(plan $plan, week $week, workout $workout, $user_id, DateTimeZone $timeZone)
    {
        $daysSince = 0;
        $workoutId = $workout->workout_id;
        $active_plan = active_plan::where('user', $user_id)->latest('updated_at')->first();
        $activePlanDate = new DateTime($active_plan->start_date, $timeZone);
        $activePlanDateStr = $activePlanDate->format('d-m-Y');
        $last = "";
        for ($i = 0; $i < $plan->number_of_weeks; $i++) {
            $thisWeek = week::find($plan->weeks[$i]);
            for ($j = 0; $j < $thisWeek->number_of_days; $j++) {
                $workoutIndex = $thisWeek->workouts_indexs[$j];
                //var_dump("WorkoutIndex: " . $workoutIndex);
                // Must use isset or it will skip the first result.
                if (isset($workoutIndex)) {
                    //var_dump($workoutId . " vs " . $thisWeek->workouts[$workoutIndex] . ":" . $workoutIndex . "<br/>");
                    if ($workoutId == $thisWeek->workouts[$workoutIndex]) {
                        $returnDate = new DateTime($activePlanDateStr, $timeZone);
                        $returnDate->add(new DateInterval('P' . $daysSince . 'D'));
                        return ($returnDate);
                    } else {
                        //echo " mismatch: " .$workoutId . ":" . $thisWeek->workouts[$workoutIndex] . "\n";
                    }
                    $last = $thisWeek->workouts[$workoutIndex];
                }
                //var_dump("Days since: " . $daysSince . "<br/>");
                $daysSince++;
            }

        }

        //echo "Nothing found (last $last): $workoutId, " . $active_plan->plan . ":" . $plan->plan_id . ", $activePlanDateStr - ";

        return null;
    }

    public function findDateFromWorkoutDay(plan $plan, week $week, workout $workout)
    {
        $daysSince = 0;
        $workoutId = $workout->workout_id;
        $active_plan = active_plan::where('user', auth()->user()->id)->latest('updated_at')->first();
        $activePlanDate = new DateTime($active_plan->start_date, new DateTimeZone(Auth::user()->timezone));
        $activePlanDateStr = $activePlanDate->format('d-m-Y');
        for ($i = 0; $i < $plan->number_of_weeks; $i++) {
            $thisWeek = week::find($plan->weeks[$i]);
            for ($j = 0; $j < $thisWeek->number_of_days; $j++) {
                $workoutIndex = $thisWeek->workouts_indexs[$j];
                //var_dump("WorkoutIndex: " . $workoutIndex);
                // Must use isset or it will skip the first result.
                if (isset($workoutIndex)) {
                    //var_dump($workoutId . " vs " . $thisWeek->workouts[$workoutIndex] . ":" . $workoutIndex . "<br/>");
                    if ($workoutId == $thisWeek->workouts[$workoutIndex]) {
                        $returnDate = new DateTime($activePlanDateStr, new DateTimeZone(Auth::user()->timezone));
                        $returnDate->add(new DateInterval('P' . $daysSince . 'D'));
                        return ($returnDate);
                    }
                }
                //var_dump("Days since: " . $daysSince . "<br/>");
                $daysSince++;
            }
        }

        return null;
    }

    public function findWorkoutFromDate(DateTime $date)
    {
        //var_dump("Running.");
        //var_dump($date);
        $daysSince = 0;
        $activePlan = active_plan::where('user', auth()->user()->id)->latest('updated_at')->first();
        $plan = plan::find($activePlan->plan);

        //var_dump("Plan: " . $plan->number_of_weeks);
        $activePlanDate = new DateTime($activePlan->start_date, new DateTimeZone(Auth::user()->timezone));
        $activePlanDateStr = $activePlanDate->format('d-m-Y');
        for ($i = 0; $i < $plan->number_of_weeks; $i++) {
            //var_dump("loop <br/>");
            $thisWeek = week::find($plan->weeks[$i]);
            for ($j = 0; $j < $thisWeek->number_of_days; $j++) {
                $workoutIndex = $thisWeek->workouts_indexs[$j];
                //var_dump($workoutId . " vs " . $thisWeek->workouts[$workoutIndex] );
                $returnDate = new DateTime($activePlanDateStr, new DateTimeZone(Auth::user()->timezone));
                $returnDate->add(new DateInterval('P' . $daysSince . 'D'));
                //var_dump("Current date " . $returnDate->format('d-m-Y') . ':' . $date->format('d-m-Y'));
                if ($returnDate->format('d-m-Y') == $date->format('d-m-Y')) {
                    if (isset($workoutIndex)) {
                        return $thisWeek->workouts[$workoutIndex];
                    } else {
                        return null;
                    }
                }
                //var_dump("Days since: " . $daysSince . "<br/>");
                $daysSince++;
            }
        }

        return null;
    }
}
