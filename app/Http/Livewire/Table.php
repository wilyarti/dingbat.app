<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Table extends Component
{
    public $workout;
    public $exercises;
    public $planId;
    public $weekId;
    public $workoutId;
    public $exerciseDB;
    public $exercisesMuscles;
    public $adapterData;
    public $completed = [];

    public function mount($planId, $workout, $exercises, $weekId, $workoutId, $exerciseDB, $exercisesMuscles, $adapterData)
    {
        $this->workout = $workout;
        $this->exercises = $exercises;
        $this->weekId = $weekId;
        $this->workoutId = $workoutId;
        $this->exerciseDB = $exerciseDB;
        $this->exercisesMuscles = $exercisesMuscles;

        for ($i = 0; $i < $this->workout->number_of_exercises; $i++) {
            $count = DB::table('set')
                ->where('user_id', auth()->user()->id)
                ->where('week_id', $weekId)
                ->where('workout_id', $workoutId)
                ->where('exercises_index', $i)
                ->join('exercise', 'exercise.exercise_id', '=', 'set.exercise_id')
                ->select('set.*', 'exercise.exercise_name')
                ->count();
            if ($count >= intval($this->workout->exercises_sets[$i])) {
                array_push($this->completed, true);
            } else {
                array_push($this->completed, false);
            }
        }
    }

    public function click($id)
    {
        error_log("Clicked $id");
    }

    public function render()
    {
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.exercise.table');
    }
}
