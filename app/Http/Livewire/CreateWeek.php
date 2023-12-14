<?php

namespace App\Http\Livewire;

use App\Helpers\WeekStats;
use App\Models\plan;
use App\Models\User;
use App\Models\week;
use App\Models\workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateWeek extends Component
{
    public $weekID;
    public $week;
    public $workouts;
    public $user;
    public $workoutIds;

    /*
     * Editor variables
     */
    public $editMode = false;
    public $numberOfDays;
    public $numberOfWorkouts;
    public $name = "Template";
    public $weekSelector;

    protected $rules = [
        'name' => 'required|max:25',
        'numberOfDays' => 'required|numeric|min:2|max:10',

    ];

    public function createNewWeek()
    {
        $this->validate();
        $newWeekCreated = null;
        if ($this->editMode) {
            $newWeekCreated = week::find($this->weekID);
            session()->flash('message', 'UPDATED');
        } else {
            $newWeekCreated = new week;
            session()->flash('message', 'SAVED');
        }
        //return;
        $workoutIDarray = [];
        for ($i =0; $i < $this->numberOfWorkouts; $i++) {
            if (isset($this->workoutIds[$i])) {
                $workoutIDarray[$i] = intval($this->workoutIds[$i]);
            } else {
                $workoutIDarray[$i] = null;
            }
        }
        $newWeekCreated->number_of_days = $this->numberOfDays;
        $newWeekCreated->number_of_workouts = $this->numberOfWorkouts;
        $newWeekCreated->workouts = $workoutIDarray;
        $newWeekCreated->workouts_indexs = [0, 1, null, 2, 3, 4, null];
        $newWeekCreated->cardio = ["Light cardio (1 hour)"]; // TODO implement cardio
        $newWeekCreated->cardio_indexs = [null, null, 0, null, null, null, null];
        $newWeekCreated->user_id = $this->user->id;
        $newWeekCreated->week_name = $this->name;
        $newWeekCreated->save();
    }

    public function mount()
    {
        $this->weekID = request()->weekID;
    }

    public function render()
    {
        $this->week = week::find($this->weekID);
        $this->user = user::find(Auth::user()->id);

        //var_dump($this->week->workouts);

        // Editing a specific week
        if (isset($this->week->week_id)) {
            $this->editMode = true;
            $this->name = $this->week->week_name;
            $this->numberOfDays = $this->week->number_of_days;
            $this->numberOfWorkouts = $this->week->number_of_workouts;
            $this->workouts = workout::all();
            $this->currentWorkouts = $this->week->workouts;
            $this->workoutIds = [];

            for ($i = 0; $i < $this->numberOfWorkouts; $i++) {
                $this->workoutIds[$i] = $this->currentWorkouts[$i];
            }
            //var_dump($this->workoutIds);
        } else {
            $this->editMode = false;
        }
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.create-week');
    }
}
