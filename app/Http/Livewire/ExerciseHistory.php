<?php

namespace App\Http\Livewire;

use App\Helpers\DateFinder;
use App\Helpers\RepMax;
use App\Helpers\RepsSetsSlot;
use App\Models\active_plan;
use App\Models\plan;
use App\Models\set;
use App\Models\exercise;
use App\Models\week;
use App\Models\workout;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExerciseHistory extends Component
{
    public $exerciseId;
    public $sets;
    public $exercise;
    public $oneRepMaxDB;

    public $exercise_name;

    public $dateSlots;
    public $workOutSlots;
    public $workoutVolume;

    public function mount($exerciseId) {
        $sets = set::where('user_id', auth()->user()->id)
            ->where('exercise.exercise_id', $exerciseId)
            ->join('exercise', 'exercise.exercise_id', '=', 'set.exercise_id')
            ->select('set.*', 'exercise.exercise_name')
            ->orderBy('set.date', 'ASC')
            ->get();

        $this->sets = $sets;
        $this->exercise = exercise::find($exerciseId);

        $this->exercise_name = $this->exercise->exercise_name;

        $repMax = new RepMax();
        $this->oneRepMaxDB = $repMax->oneRepMaxDB(Auth::user()->id);

        $this->dateSlots = [];
        $this->workOutSlots = [];

        foreach ($sets as $set) {
            // Group our workouts into date slots for partials.exercise-chart-one-rep-max.blade.php
            $dateKey = date_format(new DateTime($set->date, new DateTimeZone(Auth::user()->timezone)), 'd F Y' );
            if (!isset($this->dateSlots[$dateKey])) {
                $this->dateSlots[$dateKey] = [];
            }
            array_push($this->dateSlots[$dateKey], $set);

            // Group our workouts into workout slots for partials.exercise-chart-volume-per-set.blade.php
            if (!isset($set->workout_id)) {
                // Set our workout_id to zero if it is null... Messy but i need this to allow for custom workouts
                $set->workout_id = 0;
            }
            if (isset($set->workout_id)) { // workout_id can be null
                if (!isset($this->workOutSlots[$set->workout_id])) {
                    $this->workOutSlots[$set->workout_id] = [];
                }
            }
            array_push($this->workOutSlots[$set->workout_id], $set);
        }

        foreach ($this->workOutSlots as $workOutSlot) {
            $id = null;
            foreach ($workOutSlot as $item) {
                $volume = $item->weight * $item->reps;
                //$id = $item->workout_id;
                $plan = $item->plan_id;
                $week = $item->week_id;
                $this->workoutVolume[$id] = $volume;
                $thisDate = new DateTime($item->date, Auth::user()->time_zone);
                $id = date_format(new DateTime($thisDate->format(DATE_RFC822), Auth::user()->time_zone), 'd M y');
            }
        }
        //var_dump($this->workoutVolume);
    }
    public function render()
    {
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.exercise-history');
    }
}
