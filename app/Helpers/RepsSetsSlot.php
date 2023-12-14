<?php


namespace App\Helpers;


use App\Models\workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepsSetsSlot
{
    public function getRepSetsSlot($workoutId, $date)
    {
        $oneRepMaxSlots = [];
        $setSlots = [];
        $workout = workout::find($workoutId);
        //var_dump($workout);
        for ($i = 0; $i < $workout->number_of_exercises; $i++) {
            //$setSlots[$i]
            $setsForExercisesIndex = DB::table('set')
                ->where('user_id', auth()->user()->id)
                //->where('week_id', $thisWeek->week_id)
                ->where('workout_id', $workoutId)
                ->whereDate('date', $date)
                ->where('exercises_index', $i)
                ->join('exercise', 'exercise.exercise_id', '=', 'set.exercise_id')
                ->join('muscles', 'muscles.muscle_id','=','exercise.muscle_id')

                ->select('set.*',  'muscles.*','exercise.exercise_name')
                ->get();
            $exerciseIndex = [];
            foreach ($setsForExercisesIndex as $set) {
                if (!isset($exerciseIndex[$set->exercise_id])) {
                    $exerciseIndex[$set->exercise_id] = [];
                }
                array_push($exerciseIndex[$set->exercise_id], $set);
            }
            $setSlots[$i] = $exerciseIndex;
            $oneRepMaxRow = DB::table('set')
                ->where('user_id', auth()->user()->id)
                ->where('workout_id', $workoutId)
                ->where('date', $date)
                ->where('exercises_index', $i)
                ->join('exercise', 'exercise.exercise_id', '=', 'set.exercise_id')
                ->select('set.*', 'exercise.exercise_name')
                ->orderBy('one_rep_max', 'desc')->first();
            if ($oneRepMaxRow) {
                $oneRepMax = intval($oneRepMaxRow->one_rep_max);
                array_push($oneRepMaxSlots, $oneRepMax);
            } else {
                array_push($oneRepMaxSlots, null);
            }
            //var_dump($repMax->oneRepMaxDB(Auth::user()->id));
        }
        // Get our DB of all records
        $repMax = new RepMax();
        $oneRepMaxDB = $repMax->oneRepMaxDB(Auth::user()->id);
        asort($oneRepMaxSlots);
        return (['oneRepMaxDB' => $oneRepMaxDB, 'oneRepMaxSlots' => $oneRepMaxSlots, 'setSlots' => $setSlots]);
    }
    private function comparatorFunc( $x, $y)
    {
        // If $x is equal to $y it returns 0
        if ($x[0]->exercises_index == $y[0]->exercises_index)
            return 0;

        // if x is less than y then it returns -1
        // else it returns 1
        if ($x[0]->exercises_index < $y[0]->exercises_index)
            return -1;
        else
            return 1;
    }
    public function getWorkoutsForDay($date)
    {
        $oneRepMaxSlots = [];
        $setSlots = [];
        //$setSlots[$i]
        $setsForExercisesIndex = DB::table('set')
            ->where('user_id', auth()->user()->id)
            //->where('week_id', $thisWeek->week_id)
            //->where('workout_id', $workoutId)
            ->whereDate('date', $date)
            //->where('exercises_index', $i)
            ->join('exercise', 'exercise.exercise_id', '=', 'set.exercise_id')
            ->join('muscles', 'muscles.muscle_id','=','exercise.muscle_id')
            ->select('set.*', 'muscles.*','exercise.exercise_name')
            // Sort exerxises by date....
            ->orderBy('set.exercises_index', 'ASC')
            ->get();
        $exerciseIndex = [];

        foreach ($setsForExercisesIndex as $set) {
            if (!isset($exerciseIndex[$set->exercise_id])) {
                $exerciseIndex[$set->exercise_id] = [];
            }
            array_push($exerciseIndex[$set->exercise_id], $set);
        }
        // Sort our exercises by exercises_index
        usort($exerciseIndex, [$this,"comparatorFunc"]);
        $setSlots[0] = $exerciseIndex;

        $oneRepMaxRow = DB::table('set')
            ->where('user_id', auth()->user()->id)
            //->where('workout_id', $workoutId)
            ->where('date', $date)
            //->where('exercises_index', $i)
            ->join('exercise', 'exercise.exercise_id', '=', 'set.exercise_id')
            ->select('set.*', 'exercise.exercise_name')
            ->orderBy('one_rep_max', 'desc')->first();
        if ($oneRepMaxRow) {
            $oneRepMax = intval($oneRepMaxRow->one_rep_max);
            array_push($oneRepMaxSlots, $oneRepMax);
        } else {
            array_push($oneRepMaxSlots, null);
        }
        //var_dump($repMax->oneRepMaxDB(Auth::user()->id));

        // Get our DB of all records
        $repMax = new RepMax();
        $oneRepMaxDB = $repMax->oneRepMaxDB(Auth::user()->id);
        asort($oneRepMaxSlots);
        return (['oneRepMaxDB' => $oneRepMaxDB, 'oneRepMaxSlots' => $oneRepMaxSlots, 'setSlots' => $setSlots]);
    }
}
