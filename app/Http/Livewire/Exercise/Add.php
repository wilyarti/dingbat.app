<?php

namespace App\Http\Livewire\Exercise;

use App\Helpers\DateFinder;
use App\Helpers\enabledExercises;
use App\Helpers\RepMax;
use App\Helpers\RepsSetsFinder;
use App\Helpers\RepsSetsSlot;
use App\Models\exercise;
use App\Models\muscle;
use App\Models\plan;
use App\Models\set;
use App\Models\week;
use App\Models\workout;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Add extends Component
{
    protected $listeners = [
        'incrementReps' => 'incrementReps',
        'decrementReps' => 'decrementReps'

    ];
    public $debug;
    public $recommendedSets;
    public $recommendedReps;
    public $weight = 24;
    public $reps = 10;
    public $thisExercise;
    public $thisWorkout;
    public $thisWeek;
    public $thisPlan;
    public $exercises;
    public $sets;
    public $exerciseDB;
    public $selectedExercise;
    public $exercisesIndex;
    public $autoLoadBoolean = false;
    public $thisDate;
    public $editSet = null;
    public $editMode = false;
    public $user;

    public $setSlots = [];
    public $oneRepMaxDB;
    public $oneRepMaxSlots;

    public $oneRepMax;
    public $muscles;
    public $selectedMuscle;
    public $foundKey;

    public $hashMap;
    public $reverseHashMap;
    public $exercisesMuscles;

    public function mount()
    {
        /*
         * Initialise
         */
        // $planId, $weekId, $workoutId, $exerciseId, date
        $planId = request()->planId;
        $weekId = request()->weekId;
        $workoutId = request()->workoutId;
        $exerciseId = request()->exerciseId;
        $date = request()->date;
        $this->user = Auth::user();
        $this->muscles = muscle::all();

        // Route is /plan/19/week/184/workout/814/exercise/13/slot/1/reps/3/weight/
        if ($planId && $weekId && $workoutId && $exerciseId) {
            $this->thisPlan = plan::find($planId);
            $this->thisWeek = week::find($weekId);
            $this->thisWorkout = workout::find($workoutId);
            $this->thisExercise = exercise::find($exerciseId);
            $this->selectedExercise = $this->thisExercise->exercise_id;
            $this->selectedMuscle = $this->thisExercise->muscle_id;

            $lastSet = set::where('user_id', $this->user->id)
                ->where('exercise_id', $this->thisExercise->exercise_id)
                ->latest('updated_at')->first();

            if (isset($lastSet)) {
                if ($this->reps < $lastSet->reps) {
                    $this->reps = $lastSet->reps;

                }
                if ($this->weight < $lastSet->weight) {
                    $this->weight = $lastSet->weight;
                }
            }
            $index = array_search($this->thisExercise->exercise_id, $this->thisWorkout->exercises);
            $this->exercisesIndex = $index;
            if (isset($index)) {
                $maxRepsOnSet = DB::table('set')
                    ->where('user_id', '=', auth()->user()->id)
                    ->where('exercise_id', '=', $this->thisExercise->exercise_id)
                    ->max('reps');

                $maxWeightOnSet = DB::table('set')
                    ->where('user_id', '=', auth()->user()->id)
                    ->where('exercise_id', '=', $this->thisExercise->exercise_id)
                    ->max('weight');
                if ($maxRepsOnSet) {
                    $this->reps = $maxRepsOnSet;
                }
                if ($maxWeightOnSet) {
                    $this->weight = $maxWeightOnSet;
                }
            }

            /*
             * Found recommended reps on workout plan, load if larger.
             */
            $find = new RepsSetsFinder();
            $repsSets = $find->findRepsSets($this->thisWorkout);
            //var_dump($repsSets['reps']);
            if (isset($repsSets['reps'][$index])) {
                if ($this->reps < $repsSets['reps'][$index]) {
                    $this->reps = $repsSets['reps'][$index];
                }
                $this->recommendedSets = $repsSets['sets'][$index];
                $this->recommendedReps = $repsSets['reps'][$index];
            }
        }
        if ($date) {
            $this->thisDate = new DateTime($date, $this->user->timeZone);
            //$this->selectedExercise = 10;
            $this->selectedMuscle = 5;
        } else {
            /* Find our date */
            $dateFinder = new DateFinder();
            $this->thisDate = $dateFinder->findDateFromWorkoutDay($this->thisPlan, $this->thisWeek, $this->thisWorkout);
        }

        /*
         * Optional parameters
         * Set last because they are overwritten
         */
        if (request()->weight) {
            $this->weight = request()->weight;
        }
        if (request()->reps) {
            $this->reps = request()->reps;
            $this->recommendedSets = request()->reps;
        }
        if (request()->slot) {
            $this->exercisesIndex = request()->slot;
        }
        $this->exercisesMuscles = muscle::all();
    }

    protected $rules = [
        'reps' => 'required|numeric|between:0.00,4000.40',
        'weight' => 'required|numeric|between:0.00,4000.40',
        'selectedExercise' => 'required',
        'selectedMuscle' => 'required'
    ];


    public function incrementWeight()
    {
        if (!is_numeric($this->weight)) {
            $this->weight = 0;
            //$this->validateOnly($this->weight);
            return;
        }
        $this->weight += 2.5;
        $this->weight = 2.5 * ceil($this->weight /2.5);
    }

    public function decrementWeight()
    {
        if (!is_numeric($this->weight)) {
            $this->weight = 0;
            //$this->validateOnly($this->weight);
            return;
        }
        if ($this->weight > 2.5) {
            $this->weight -= 2.5;
        }
        $this->weight = 2.5 * ceil($this->weight /2.5);
    }
    public function newReps($x) {
        $this->reps= $x;
    }

    public function incrementReps()
    {
        if (!is_numeric($this->reps)) {
            $this->reps = 1;
            //$this->validateOnly($this->reps);
            return;
        }
        $this->reps += 1;
        //$this->validateOnly($this->reps);
    }

    public function decrementReps()
    {
        if (!is_numeric($this->reps)) {
            $this->reps = 1;
            //$this->validateOnly($this->reps);
            return;
        }
        $this->reps--;
        if ($this->reps < 1) {
            $this->reps =1;
        }
    }

    public function clear()
    {
        if ($this->editMode) {
            $thisSet = set::find($this->editSet);
            $thisSet->delete();
            $this->editMode = false;
            $this->editSet = null;
        } else {
            $this->weight = 2.5;
            $this->reps = 8;
        }
    }

    public function updated($weight)
    {
        // Annoying AF
       // $this->validateOnly($weight);
    }

    public function edit($id)
    {
        if ($this->editMode) {
            $this->editMode = false;
            $this->editSet = null;
        } else {
            $this->editMode = true;
            $this->thisSet = set::find($id);
            $this->editSet = $id;

            if (isset($this->thisSet)) {
                $this->reps = $this->thisSet->reps;
                $this->weight = $this->thisSet->weight;
                $this->selectedExercise = $this->thisSet->exercise_id;
                $exercise = exercise::find($this->thisSet->exercise_id);
                $this->selectedMuscle = $exercise->muscle_id;
            }
        }
        $user = Auth::user();
        activity()
            ->inLog("edit_set")
            ->causedBy($user->id)
            ->withProperties(['set_id' => $id])
            ->log($user->name . " edited set.");
    }

    public function save()
    {
        $validatedData = $this->validate();
        $this->debug =$validatedData;

        $this->reps = $validatedData['reps'];
        $this->weight = $validatedData['weight'];

        if ($this->editMode) {
            $oneRepMaxCalculator = new RepMax();
            $oneRm = $oneRepMaxCalculator->calculateOneRepMax($this->weight, $this->reps);
            $thisSet = set::find($this->editSet);
            //$thisSet->plan_id = $this->thisPlan->plan_id;
            $thisSet->weight = $this->weight;
            $thisSet->reps = $this->reps;
            $thisSet->exercise_id = $this->selectedExercise;
            $thisSet->one_rep_max = $oneRm;
            $thisSet->exercises_index = $this->exercisesIndex;
            $thisSet->save();
            $user = Auth::user();
            activity()
                ->inLog("update_set")
                ->causedBy($user->id)
                ->withProperties(['set_id' => $thisSet->set_id])
                ->log($user->name . " updated set.");
            $this->editMode = false;
            $this->editSet = null;
        } else {
            $oneRepMaxCalculator = new RepMax();
            $oneRm = $oneRepMaxCalculator->calculateOneRepMax($this->weight, $this->reps);
            $newSet = new set;
            if (isset($this->thisPlan->plan_id) && isset($this->thisWorkout->workout_id) && isset($this->thisWeek->week_id) && isset($this->exercisesIndex)) {
                $newSet->plan_id = $this->thisPlan->plan_id;
                $newSet->workout_id = $this->thisWorkout->workout_id;
                $newSet->week_id = $this->thisWeek->week_id;
            } else {
                $newSet->plan_id = null;
                $newSet->workout_id = null;
                $newSet->week_id = null;
                //$newSet->exercises_index = 0;
            }
            $newSet->exercises_index = $this->exercisesIndex;
            $newSet->exercise_id = $this->selectedExercise;
            $newSet->user_id = $this->user->id;
            $newSet->weight = $this->weight;
            $newSet->reps = $this->reps;
            $newSet->date = $this->thisDate;
            $newSet->one_rep_max = $oneRm;
            $newSet->save();
            $user = Auth::user();
            activity()
                ->inLog("create_set")
                ->causedBy($user->id)
                ->withProperties(['set_id' => $newSet->set_id])
                ->log($user->name . " create set.");
        }
        //session()->flash('message', 'SAVED');
        $this->dispatchBrowserEvent('added');
    }

    public function autoload()
    {
        /*
      *
      * Populate our day if empty
      */
        //var_dump($this->recommendedSets);
        for ($i = 0; $i < $this->recommendedSets; $i++) {
            $oneRepMaxCalculator = new RepMax();
            $oneRm = $oneRepMaxCalculator->calculateOneRepMax($this->weight, $this->reps);
            $newSet = new set;
            $newSet->user_id = $this->user->id;
            $newSet->workout_id = $this->thisWorkout->workout_id;
            $newSet->week_id = $this->thisWeek->week_id;
            $newSet->exercise_id = $this->selectedExercise;
            $newSet->exercises_index = $this->exercisesIndex;
            $newSet->weight = $this->weight;
            $newSet->reps = $this->reps;
            $newSet->date = $this->thisDate;
            $newSet->one_rep_max = $oneRm;
            $newSet->save();
        }

        $this->autoLoadBoolean = false;
    }

    public function muscleChanged()
    {
        $this->selectedExercise = null;
    }

    public function render()
    {
        $user = Auth::user();
        /*
         * Get our sets and autoload
         */
        if (isset($this->thisWeek->week_id) && isset($this->thisWorkout->workout_id) && isset($this->exercisesIndex) && isset($this->thisWorkout->workout_id)) {
            // Find number of recommended sets
            $this->sets = DB::table('set')
                ->where('user_id', auth()->user()->id)
                ->where('week_id', $this->thisWeek->week_id)
                ->where('workout_id', $this->thisWorkout->workout_id)
                ->where('exercises_index', $this->exercisesIndex)
                ->join('exercise', 'exercise.exercise_id', '=', 'set.exercise_id')
                ->select('set.*', 'exercise.exercise_name')
                ->get();

            if (sizeof($this->sets) <= 0) {
                $this->autoLoadBoolean = true;
            }
            if ($this->thisWorkout->workout_id) {
                $getDB = new RepsSetsSlot();
                $data = $getDB->getRepSetsSlot($this->thisWorkout->workout_id, $this->thisDate);
                $this->oneRepMaxDB = $data['oneRepMaxDB'];
                $this->oneRepMaxSlots = $data['oneRepMaxSlots'];
                $this->setSlots = $data['setSlots'];
            }
        } else {
            $getDB = new RepsSetsSlot();
            $data = $getDB->getWorkoutsForDay($this->thisDate);
            $this->oneRepMaxDB = $data['oneRepMaxDB'];
            $this->oneRepMaxSlots = $data['oneRepMaxSlots'];
            $this->setSlots = $data['setSlots'];
        }

        $this->exercises = [];
        $this->exercises = exercise::all();
        $enabledExercise = new enabledExercises();
        /*
        * Get exercises
        */
        $enabledExercise = new enabledExercises();
        $muscle_ids = [];
        if (isset($this->thisExercise->muscle_id) && isset($this->thisPlan)) {
            array_push($muscle_ids, $this->thisExercise->muscle_id);
        } elseif ($this->selectedMuscle) {
            //$muscle_ids = muscle::select('muscle_id')->get()->toArray();
            array_push($muscle_ids, $this->selectedMuscle);
        }
        $this->exerciseDB = $enabledExercise->getEnabledExercises($this->user->id, $muscle_ids);

        if ($this->selectedExercise) {
            $this->thisExercise = exercise::find($this->selectedExercise);
        }

        /* find a slot for our exercise. increment if not found */
        /* only run when doing custom workout */
        $this->hashMap = [];
        if (!isset($this->thisPlan) && isset($this->selectedExercise)) {
            $this->foundKey = null;
            foreach ($this->setSlots as $key => $value) {
                foreach ($value as $subKey => $subValue) {

                    foreach ($subValue as $set) {
                        if ($set->exercise_id == $this->selectedExercise) {
                            $this->foundKey = $set->exercises_index;
                            error_log($subKey . ":" . $this->selectedExercise . ":" . $key);
                        }
                        if (!isset($this->hashMap[$set->exercises_index])) {
                            $this->hashMap[$set->exercises_index] = [];
                        }
                        if (!in_array($subKey, $this->hashMap[$set->exercises_index])) {
                            array_push($this->hashMap[$set->exercises_index], $set->exercise_id);
                        }
                        //$this->hashMap[$set->exercises_index] = $subKey;
                        $this->reverseHashMap[$subKey] = $set->exercises_index;
                    }
                }
            }
            /*
             * Search our array on indexs and if our exercise_id is there set the index to match previous....
             * If we find our key, remember that.
                */
            $foundBoolean = false;
            foreach ($this->hashMap as $key => $value) {
                if (in_array($this->selectedExercise, $value)) {
                    $this->exercisesIndex = $key;
                    $foundBoolean = true;
                    error_log("Found $this->selectedExercise in slot key: " . $key);
                }
            }
            /*
             * We didn't find a matching slot, so find the smallest slot number and add 1.
             */
            $minValue = 0;
            $availableSlot =null;
            if (!$foundBoolean) {
                error_log("not found");
                for ($i =0 ; $i < sizeof($this->hashMap); $i++) {
                    if (isset($this->hashMap[$i])) {
                        $minValue = $i;
                        error_log("Found used slot: " . $i);
                    } else {
                        if (!isset($availableSlot)){
                            $availableSlot = $i;
                        }
                    }
                }
                foreach ($this->hashMap as $key => $value) {
                    if ($key > $minValue) {
                        $minValue = $key;
                    }
                }
                if (isset($availableSlot)) {
                    $this->exercisesIndex = $availableSlot;
                    error_log("Found available slot: " . $availableSlot);
                } else {
                    $this->exercisesIndex = $minValue +1;
                    if (sizeof($this->hashMap) ==0) {
                        $this->exercisesIndex = 0;
                    }
                    error_log("Slot not found, using minValue: " . $minValue+1);
                }
            }
        }

        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . $this->user->id . ")" . " on " . request()->path());

        return view('livewire.exercise.add');
    }
}
