<?php

namespace App\Http\Livewire;

use App\Helpers\WeekStats;
use App\Models\exercise;
use App\Models\muscle;
use App\Models\plan;
use App\Models\User;
use App\Models\week;
use App\Models\workout;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;

class CreatePlan extends Component
{
    public $notImported = true;
    public $lastPlanID = null;
    public $plans;
    public $userPlans;
    public $step = 0;
    public $subStep = 0;
    public $confirmPlanDeletion = false;

    public $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Monday week', 'Tuesday week', 'Wednesday week', 'Thursday week', 'Friday week', 'Saturday week', 'Sunday week'];
    public $cardioList = [ // TODO put this into a table
        '15 mins low',
        '30 mins low',
        '60 mins low',
        '15 mins med',
        '30 mins med',
        '60 mins med',
        '15 mins high',
        '30 mins high',
        '60 mins high',
    ];

    public $adapter = 0;
    public $adapterList = [ // TODO put this into a table
        0 => "No Adapter",
        1 => "1RM",
        2 => "1RM, TUT, AMRAP, TEMPO"
    ];

    public $step1percentage = 0;
    public $step2percentage = 0;
    public $step3percentage = 0;

    /* Plan Settings */
    public $copyMode = false;
    public $globalMode = false;
    public $lastPlanSelected = null;
    public $planSelected = null;
    public $planSettings = [
        'plan_id' => null,
        'price' => 0,
        'plan_name' => 'Custom Plan',
        'owner' => 0,
        'number_of_weeks' => 4,
        'weeks' => [],
        'description' => "Enter your description....<br/><br/><br/><br/><br/><br/><br/><br/>"
    ];

    /* Week Settings */
    public $weekSettingsArray = [];
    public $weekSelected = 0;
    public $weekSettings = [
        'week_id' => null,
        'week_name' => 'Example Week',
        'number_of_days' => 7,

        'number_of_workouts' => 4,
        'workouts' => [1, 2, 3, 4],
        'workouts_indexs' => [],

        'number_of_cardio' => 0,
        'cardio' => [],
        'cardio_indexs' => [],
    ];

    /* Workout Settings */
    public $workoutSettingsArray = [];
    public $workoutSelected = 0;
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
        'adapter_array' => [
            'one_rep_max' => [],
            'as_many_reps_as_possible' => [],
            'time_under_tension' => [],
            'rest_per_set' => [],
        ],
    ];
    public $muscles;
    public $exercises;
    public $user;

    public $adjustedWeekIndex = [];
    public $changeDaySelector;
    public $workoutIndexSelector;
    public $workoutIndexWeekDaySelector;
    public $confirmWorkoutDeletion = false;
    public $splice;
    public $spliced;

    public $workOutKeeper;
    public $showExercises = false;

    public function calculateStep()
    {
        if ($this->step == 1) {
            $this->step1percentage = 100;
            if ($this->subStep) {
                $this->step2percentage = ((100 / $this->planSettings['number_of_weeks']) * $this->subStep);
            }
        } elseif ($this->step == 2) {
            $this->step2percentage = 100;
            if ($this->subStep) {
                $counter = 0;
                $currentPos = 0;
                foreach ($this->weekSettingsArray as $key => $ws) {
                    $counter += $ws['number_of_workouts'];
                    if ($this->weekSelected > $key) {
                        $currentPos += $ws['number_of_workouts'];
                    }
                }
                $this->step3percentage = (100 / $counter) * ($currentPos + $this->subStep);
            }
        } elseif ($this->step == 3) {
            $this->step3percentage = 100;
        }
    }

    public function runLogic()
    {
        $this->calculateStep();
        if ($this->step == 0) {
            $this->notImported = true;
        }
        /*
         * Calculate $workoutIndexSelector
         */
        if ($this->step == 2 && $this->subStep) {
            $counter = 0;
            for ($i = 0; $i < $this->weekSettingsArray[$this->weekSelected]['number_of_days']; $i++) {
                if ($this->weekSettingsArray[$this->weekSelected]['workouts_indexs'][$i] == true) {
                    if ($counter == $this->subStep - 1) {
                        $this->workOutKeeper = $i;
                        $this->workoutIndexWeekDaySelector = $i;
                        $this->changeDaySelector = $this->workoutIndexWeekDaySelector;
                    }
                    $counter++;
                }
            }
            $this->workoutIndexSelector = $this->subStep - 1;

        }

        if ($this->step == 1 && $this->notImported) { // Stop overriding our changes. Resets on first week.....
            //$this->validate($this->weekRules);
            $this->notImported = false;
            $this->weekSettingsArray = [];
            /*
             * Derived Plan
             */
            if ($this->planSelected) {
                $originalPlan = plan::find($this->planSelected);
                if ($originalPlan->user_id != Auth::user()->id) {
                    $this->copyMode = true;
                }
                /* //TODO this
                foreach ($this->planSettings as $key => $value) {
                    $this->planSettings[$key] = $originalPlan[$key];
                }
                */
                for ($i = 0; $i < $this->planSettings['number_of_weeks']; $i++) {

                    if (!isset($originalPlan['weeks'][$i])) {
                        $thisWeek = $this->weekSettings;
                    } else {
                    }
                    $thisWeek = week::find($originalPlan['weeks'][$i]);

                    $this->workoutSettingsArray[$i] = [];
                    $this->weekSettingsArray[$i] = [];
                    if ($thisWeek) {
                        foreach ($this->weekSettings as $key => $value) {
                            $this->weekSettingsArray[$i][$key] = $thisWeek[$key];
                        }
                        $this->weekSettingsArray[$i]['workouts_indexs'] = [];
                        $this->weekSettingsArray[$i]['cardio_indexs'] = [];

                        foreach ($thisWeek['workouts_indexs'] as $c) {
                            if ($c !== null) {
                                array_push($this->weekSettingsArray[$i]['workouts_indexs'], true);
                            } else {
                                array_push($this->weekSettingsArray[$i]['workouts_indexs'], false);
                            }
                        }
                        foreach ($thisWeek['cardio_indexs'] as $c) {
                            if ($c !== null) {
                                array_push($this->weekSettingsArray[$i]['cardio_indexs'], true);
                            } else {
                                array_push($this->weekSettingsArray[$i]['cardio_indexs'], false);
                            }
                        }
                        if ($this->weekSettingsArray[$i]['number_of_days']) {
                            // Populate our ticks on our workout/cardio day selector.
                            for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_workouts']; $j++) {
                                $thisWorkout = workout::find($thisWeek['workouts'][$j]);
                                $this->workoutSettingsArray[$i][$j] = [];
                                if ($thisWorkout) {
                                    foreach ($this->workoutSettings as $key => $value) {
                                        $this->workoutSettingsArray[$i][$j][$key] = $thisWorkout[$key];
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                for ($i = 0; $i < $this->planSettings['number_of_weeks']; $i++) {
                    if (!isset($this->weekSettingsArray[$i])) {
                        $this->weekSettingsArray[$i] = $this->weekSettings;
                        $this->weekSettingsArray[$i]['week_name'] = $this->planSettings['plan_name'] . " Week " . ($i + 1);
                        $this->weekSettingsArray[$i]['workouts_indexs'] = [];
                        $this->weekSettingsArray[$i]['cardio_indexs'] = [];

                        // Populate our ticks on our workout/cardio day selector.
                        for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_days']; $j++) {
                            //$this->weekSettingsArray[$i]['workouts_indexs'][$j] = true;
                            if ($j < $this->weekSettingsArray[$i]['number_of_workouts']) {
                                array_push($this->weekSettingsArray[$i]['workouts_indexs'], true);
                            } else {
                                array_push($this->weekSettingsArray[$i]['workouts_indexs'], false);
                            }
                            array_push($this->weekSettingsArray[$i]['cardio_indexs'], true); // Will be cleaned up by render logic.

                        }

                    }
                }
                $this->workoutSettingsArray = [];
                for ($i = 0; $i < $this->planSettings['number_of_weeks']; $i++) {
                    if (isset($this->weekSettingsArray[$i])) {
                        for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_days']; $j++) {
                            $this->workoutSettingsArray[$i][$j] = $this->workoutSettings;
                            $this->workoutSettingsArray[$i][$j]['workout_name'] = "Week: " . ($i + 1) . " Workout: " . ($j + 1);
                            $this->workoutSettingsArray[$i][$j]['exercises_reps'] = array_fill(0, $this->workoutSettingsArray[$i][$j]['number_of_exercises'], "12");
                            $this->workoutSettingsArray[$i][$j]['exercises_sets'] = array_fill(0, $this->workoutSettingsArray[$i][$j]['number_of_exercises'], "3");
                        }
                    }
                }
            }
        }

        // Are we up to step 2?
        // Populate our settings array
        if ($this->step == 2 && $this->subStep == 1) {
            /*
             * Derived Plan
             */
            if ($this->planSelected) {

            } else {

            }
        }
        // Save our data
        if ($this->step == 3) {
            /*
             * Derived Plan
             */

            $this->planSettings['weeks'] = [];

            // Fresh plan?
            if (!$this->planSelected) {
                $plan = new plan;
                // Copy mode?
            } elseif ($this->copyMode) {
                $plan = new plan;
                // Edit mode
            } else {
                $plan = plan::find($this->planSelected);
            }

            // Transverse week workouts
            for ($i = 0; $i < $this->planSettings['number_of_weeks']; $i++) {
                // Zero out week
                $this->weekSettingsArray[$i]['workouts'] = [];
                // Transverse each workout
                for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_workouts']; $j++) {
                    $wo = new workout;
                    foreach ($this->workoutSettingsArray[$i][$j] as $key => $value) {
                        if ($key != 'workout_id' && $key != 'muscles') {
                            $wo[$key] = $this->workoutSettingsArray[$i][$j][$key];
                        }
                        if ($key == 'muscles') {
                            $muscles = [];
                            foreach ($this->workoutSettingsArray[$i][$j]['exercises'] as $ex) {
                                $exercise = exercise::find($ex);
                                array_push($muscles, $exercise['muscle_id']);
                            }
                            $wo['muscles'] = $muscles;
                        }
                    }
                    $wo['adapter'] = $this->adapter;
                    if ($this->adapter ==0) {
                        $wo['adapter_array'] = [];
                    }
                    $wo->save();
                    array_push($this->weekSettingsArray[$i]['workouts'], $wo['workout_id']);
                }
            }

            // Transverse our weeks
            for ($i = 0; $i < sizeof($this->weekSettingsArray); $i++) {
                $w = new week;

                $counter = 0;
                $workouts_indexs = [];
                $cardio_indexs = [];
                foreach ($this->weekSettingsArray[$i]['workouts_indexs'] as $key => $woi) {
                    if ($woi) {
                        array_push($workouts_indexs, $counter);
                        $counter++;
                    } else {
                        array_push($workouts_indexs, null);
                    }
                }
                $counter = 0;
                foreach ($this->weekSettingsArray[$i]['cardio_indexs'] as $key => $woi) {
                    if ($woi) {
                        array_push($cardio_indexs, $counter);
                        $counter++;
                    } else {
                        array_push($cardio_indexs, null);
                    }
                }

                foreach ($this->weekSettings as $key => $value) {
                    if ($key == 'workouts_indexs') {
                        $w[$key] = $workouts_indexs;
                    } elseif ($key == 'cardio_indexs') {
                        $w[$key] = $cardio_indexs;
                    } elseif ($key == 'week_id') {
                    } else {
                        $w[$key] = $this->weekSettingsArray[$i][$key];
                    }
                }
                $wo['workouts_indexs'] = $workouts_indexs;
                $wo['cardio_indexs'] = $cardio_indexs;

                $w->save();
                array_push($this->planSettings['weeks'], $w['week_id']);
            }

            $plan['plan_name'] = $this->planSettings['plan_name'];
            $plan['description'] = $this->planSettings['description'];
            $plan['weeks'] = $this->planSettings['weeks'];
            $plan['number_of_weeks'] = $this->planSettings['number_of_weeks'];
            $plan['price'] = $this->planSettings['price'];
            if ($this->globalMode) {
                $plan['owner'] = -1;
            } else {
                $plan['owner'] = Auth::user()->id;
            }
            $plan['user_id'] = Auth::user()->id;

            $plan['is_clone'] = false;
            $plan->save();
            if (isset($plan['plan_id'])) {
                session()->flash('doneMessage', 'SUCCESS! Plan ID: ' . $plan['plan_id']);
            } else {
                session()->flash('doneMessage', 'FAILED!');
            }
        }
    }

    public function incrementStep()
    {
        // Select Plan
        if ($this->step == 0) {
            $this->step++;
            $this->subStep = 1;
            // Add Weeks
        } elseif ($this->step == 1) {
            $this->subStep++;
            if ($this->subStep > $this->planSettings['number_of_weeks']) {
                $this->subStep = 1;
                $this->step++;
                $this->weekSelected = 0;
            }
            // Add Workouts
        } elseif ($this->step == 2) {
            $this->subStep++;
            if ($this->subStep > $this->weekSettingsArray[$this->weekSelected]['number_of_workouts']) {
                $this->subStep = 1;
                $this->weekSelected++;
            }
            if ($this->weekSelected == ($this->planSettings['number_of_weeks'])) {
                $this->subStep = 1;
                $this->step++;
                // $this->weekSelected = 0;
            }
        } else {
            $this->subStep++;
        }
        $this->runLogic();
    }

    public function decrementStep()
    {
        // Select Plan
        if ($this->step == 0) {
            $this->step++;
            $this->subStep = 1;
            // Add Weeks
        } elseif ($this->step == 1) {
            $this->subStep--;
            if ($this->subStep == 0) {
                $this->subStep = 1;
                $this->step--;
                $this->weekSelected = 0;
                $this->step1percentage = 0;
                $this->step2percentage = 0;
            }
            // Add Workouts
        } elseif ($this->step == 2) {
            $this->subStep--;
            if ($this->subStep <= 0) {
                $this->subStep = 1;
                $this->weekSelected--;
            }
            if ($this->weekSelected < 0) {
                $this->subStep = 1;
                $this->step--;
                $this->step2percentage = 0;
                $this->step3percentage = 0;
            }
        } else {
            $this->subStep--;
        }
        $this->runLogic();
    }

    public function duplicatePlan()
    {
        $plan = plan::find($this->selectedPlanID);
        if (!isset($plan)) {
            session()->flash('message', 'FAILED');
            return;
        }
        $walker = new WeekStats();
        $data = $walker->walkPlan($this->selectedPlanID);
        $weeks = $data['weeks'];
        $workouts = $data['workouts'];

        $newWeekIds = [];
        foreach ($weeks as $week) {
            $newWeek = $week->replicate();
            $newWeek->week_name = $plan->plan_name . ":" . $week->week_id;

            $workouts = workout::findMany($week->workouts);
            $newWorkoutIds = [];
            foreach ($workouts as $workout) {
                $newWorkout = $workout->replicate();
                $newWorkout->workout_name = Auth::user()->name;
                $newWorkout->user_id = Auth::user()->id;
                $newWorkout->save();

                array_push($newWorkoutIds, $newWorkout->workout_id);
            }
            $newWeek->workouts = $newWorkoutIds;
            $newWeek->save();
            array_push($newWeekIds, $newWeek->week_id);
        }
        $newPlan = $plan->replicate();
        $newPlan->weeks = $newWeekIds;
        $newPlan->plan_name = $plan->plan_name . " copy";
        $newPlan->user_id = Auth::user()->id;
        $newPlan->save();
        session()->flash('message', 'COPIED');

        // $toggle('confirmDuplicate');
    }

    public function savePlan()
    {
        $this->step = 3;
        $this->runLogic();
    }

    public function cloneSet($i, $j)
    {
        // TODO comprehend this code ??
        $k = $this->workoutSettingsArray[$i][$j]['number_of_exercises'];
        array_push($this->workoutSettingsArray[$i][$j]['exercises'], $this->workoutSettingsArray[$i][$j]['exercises'][$k - 1]);
        array_push($this->workoutSettingsArray[$i][$j]['exercises_sets'], $this->workoutSettingsArray[$i][$j]['exercises_sets'][$k - 1]);
        array_push($this->workoutSettingsArray[$i][$j]['exercises_reps'], $this->workoutSettingsArray[$i][$j]['exercises_reps'][$k - 1]);
        $this->workoutSettingsArray[$i][$j]['number_of_exercises']++;
        array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max'], $this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max'][$k - 1]);
        array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible'], $this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible'][$k - 1]);
        array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension'], $this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension'][$k - 1]);
        array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set'], $this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set'][$k - 1]);

    }

    public function copyForward($i, $j)
    {
        if (isset($this->workoutSettingsArray[$i][$j + 1])) {
            $this->workoutSettingsArray[$i][$j + 1] = $this->workoutSettingsArray[$i][$j];

            session()->flash('copyMessage', "Copied to next workout.");

        } else {
            session()->flash('copyMessage', "Failed to copy to next workout.");
        }
    }

    public function copyForwardAllWeeks($i, $j)
    {
        // Does next week exist.
        for ($index = $i; $index < $this->planSettings['number_of_weeks']; $index++) {
            if (isset($this->workoutSettingsArray[$i + $index][0]) && $index != $i) {
                $this->workoutSettingsArray[$i + $index] = $this->workoutSettingsArray[$i];
                $this->weekSettingsArray[$i + $index] = $this->weekSettingsArray[$i];
                session()->flash('copyMessage', "Cloned to all weeks.");
            } else {
                session()->flash('copyMessage', "Failed to copy week.");
            }
        }
    }

    public function copyForwardWeek($i, $j)
    {
        // Does next week exist.
        if (isset($this->workoutSettingsArray[$i + 1][0])) {
            $this->workoutSettingsArray[$i + 1] = $this->workoutSettingsArray[$i];
            $this->weekSettingsArray[$i + 1] = $this->weekSettingsArray[$i];
            session()->flash('copyMessage', "Copied to next week.");
        } else {
            session()->flash('copyMessage', "Failed to copy week.");
        }
    }

    public function downloadPlan()
    {
        // Transverse our workouts and set our adapter setting....
        for ($i = 0; $i < $this->planSettings['number_of_weeks']; $i++) {
            // Do we have a week?
            if (isset($this->weekSettingsArray[$i])) {
                // Foreach workout day
                for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_workouts']; $j++) {
                    $this->workoutSettingsArray[$i][$j]['adapter'] = $this->adapter;
                }
            }
        }
        $data = [];
        $data['weekSettings'] = $this->weekSettings;
        $data['weekSettingsArray'] = $this->weekSettingsArray;
        $data['planSettings'] = $this->planSettings;
        $data['planSelected'] = $this->planSelected;
        $data['workoutSettings'] = $this->workoutSettings;
        $data['workoutSettingsArray'] = $this->workoutSettingsArray;

        $data['planSettings']['weeks'] = []; // Zero this out or it causes dramas
        $data['weekSettings']['workouts'] = []; // Just in case


        $filename = $this->planSettings['plan_name'] . "-" . Str::uuid() . ".json";
        Storage::disk('public')->put($filename, json_encode($data));

        return response()->download(storage_path('app/public/' . $filename));
//        return response()->json($data);
    }

    public function save()
    {
    }

    public function deleteButton()
    {
        $this->confirmPlanDeletion = true;
    }

    public function deleteConfirmed()
    {
        $plan = plan::find($this->planSelected);
        if (!isset($plan)) {
            session()->flash('deleteButtonMessage', 'FAILED ' . $this->planSelected);
            return;
        }
        if ($plan->user_id == Auth::user()->id) {
            session()->flash('deleteButtonMessage', 'DELETED');
            $plan->delete();
        } else {
            session()->flash('deleteButtonMessage', 'FAILED PERMISSION DENIED.');
        }
        $this->confirmPlanDeletion = false;
        $this->planSelected = null;
        //$this->plans = plan::all();
    }

    public function deleteWorkout()
    {
        $this->confirmWorkoutDeletion = true;
    }

    public function deleteWorkoutConfirmed()
    {
        $weekIndex = $this->weekSelected;
        $workoutIndex = $this->workoutIndexWeekDaySelector;

        if (isset($this->workoutSettingsArray[$weekIndex])) {
            $splice = $this->workoutSettingsArray[$weekIndex];

            array_splice($splice, $workoutIndex, 1);
            $this->workoutSettingsArray[$weekIndex] = $splice;


            //$this->splice = $splice;
            //$this->spliced = $spliced;
            /*
             * Week Settings Array
             */

            $this->weekSettingsArray[$weekIndex]['number_of_workouts']--;
            $this->weekSettingsArray[$weekIndex]['workouts_indexs'][$workoutIndex] = false;
            $this->confirmWorkoutDeletion = false;

            /*
             * Week reindex
             */
            $this->adjustedWeekIndex = [];
            for ($i = 0; $i < $this->weekSettingsArray[$this->weekSelected]['number_of_days']; $i++) {
                if ($this->weekSettingsArray[$this->weekSelected]['workouts_indexs'][$i]) {
                    array_push($this->adjustedWeekIndex, $this->weekDays[$i]);
                }
            }
            session()->flash('moveMessage', "Deleted workout");
            $this->incrementStep();
            $this->decrementStep();
        }
    }

    public function cloneWorkout()
    {
        $weekIndex = $this->weekSelected;
        $workoutIndex = $this->workoutIndexWeekDaySelector;
        $workoutIndexSelector = $this->workoutIndexSelector;


        if (isset($this->workoutSettingsArray[$weekIndex][$workoutIndexSelector])) {
            if ($this->weekSettingsArray[$weekIndex]['number_of_workouts'] + 1 <= $this->weekSettingsArray[$weekIndex]['number_of_days']) {
                array_push($this->workoutSettingsArray[$weekIndex], $this->workoutSettingsArray[$weekIndex][$workoutIndexSelector]);

                $this->weekSettingsArray[$weekIndex]['number_of_workouts'] = $this->weekSettingsArray[$weekIndex]['number_of_workouts'] + 1;
                for ($i = 0; $i < $this->weekSettingsArray[$weekIndex]['number_of_workouts']; $i++) {
                    if (!$this->weekSettingsArray[$weekIndex]['workouts_indexs'][$i]) {
                        $this->weekSettingsArray[$weekIndex]['workouts_indexs'][$i] = true;
                        $this->weekSettingsArray[$weekIndex]['cardio_indexs'][$i] = false;
                        break;
                    }
                }

                session()->flash('moveMessage', "Successfully cloned workout.");
            } else {
                session()->flash('moveMessage', "Failed. No available days.");
            }
        } else {
            session()->flash('moveMessage', "Failed. Workout not found. - WeekIndex: " . $weekIndex . " WorkoutIndex: " . $workoutIndex . " Substep: " . $this->workoutIndexSelector);
        }
    }

    public function toggleExercises()
    {
        if ($this->showExercises) {
            $this->showExercises = false;
        } else {
            $this->showExercises = true;
        }
    }

    public function shuffleDown($weekIndex, $workoutIndex, $exerciseIndex)
    {
        $length = $this->workoutSettingsArray[$weekIndex][$workoutIndex]['number_of_exercises'];
        if ($length == 1) {
            return;
        }
        if ($exerciseIndex == $length - 1) {
            return;
        } else {

            foreach (['exercises', 'exercises_sets', 'exercises_reps'] as $key) {
                // Shuffle through keys
                $temp = $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex];
                $temp2 = $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex + 1];
                $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex] = $temp2;
                $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex + 1] = $temp;
            }
            /*
             * 'adapter_array' => [
            'one_rep_max' => [],
            'as_many_reps_as_possible' => [],
            'time_under_tension' => [],
            'rest_per_set' => [],
             */
            // Adapter Array
            foreach (['one_rep_max', 'as_many_reps_as_possible', 'time_under_tension', 'rest_per_set'] as $key) {
                // Shuffle through keys
                $temp = $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex];
                $temp2 = $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex + 1];
                $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex] = $temp2;
                $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex + 1] = $temp;
            }

            // Reps

            //
        }
    }

    public function shuffleUp($weekIndex, $workoutIndex, $exerciseIndex)
    {
        $length = $this->workoutSettingsArray[$weekIndex][$workoutIndex]['number_of_exercises'];
        if ($length == 1) {
            return;
        }
        if ($exerciseIndex == 0) {
            return;
        } else {

            foreach (['exercises', 'exercises_sets', 'exercises_reps'] as $key) {
                // Shuffle through keys
                $temp = $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex];
                $temp2 = $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex - 1];
                $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex] = $temp2;
                $this->workoutSettingsArray[$weekIndex][$workoutIndex][$key][$exerciseIndex - 1] = $temp;
            }
            /*
             * 'adapter_array' => [
            'one_rep_max' => [],
            'as_many_reps_as_possible' => [],
            'time_under_tension' => [],
            'rest_per_set' => [],
             */
            // Adapter Array
            foreach (['one_rep_max', 'as_many_reps_as_possible', 'time_under_tension', 'rest_per_set'] as $key) {
                // Shuffle through keys
                $temp = $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex];
                $temp2 = $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex - 1];
                $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex] = $temp2;
                $this->workoutSettingsArray[$weekIndex][$workoutIndex]['adapter_array'][$key][$exerciseIndex - 1] = $temp;
            }

            // Reps

            //
        }
    }

    public function mount()
    {
        // $this->selectedPlanID = request()->planID; NOT IMPLEMENTED USE ANOTHER METHOD
        $this->planSettings['plan_name'] = Auth::user()->name;
        $this->muscles = [];
        $this->exercises = [];
        $this->muscles = muscle::all();
        $this->exercises = exercise::all();
        $this->plans = plan::where('is_clone', false)->whereIn('user_id', [Auth::user()->id, 0])->get();
        $this->user = Auth::user();
    }

    public function render()
    {
        $this->user = user::find(Auth::user()->id);
        $this->plans = plan::where('is_clone', false)->whereIn('user_id', [Auth::user()->id, 0])->get();
        $this->userPlans = plan::where('user_id', Auth::user()->id)->get();


        /*
         * Default Week settings validator
         */
        if ($this->weekSettings['number_of_days'] > 10) {
            session()->flash('planSettings', 'Days can\'t exceed 10!');
            $this->weekSettings['number_of_days'] = 10;
        }
        if ($this->weekSettings['number_of_days'] < 3) {
            session()->flash('planSettings', 'Days can\'t be below 3!');
            $this->weekSettings['number_of_days'] = 3;
        }
        if ($this->weekSettings['number_of_workouts'] < 1) {
            session()->flash('planSettings', 'Workouts can\'t be below 1!');
            $this->weekSettings['number_of_workouts'] = 1;
        }
        if ($this->weekSettings['number_of_cardio'] + $this->weekSettings['number_of_workouts'] > $this->weekSettings['number_of_days']) {
            session()->flash('planSettings', 'Cardio + Workouts can\'t exceed the number of days!');
            $this->weekSettings['number_of_cardio'] = $this->weekSettings['number_of_days'] - $this->weekSettings['number_of_workouts'];
        }

        if ($this->weekSettings['number_of_workouts'] > $this->weekSettings['number_of_days']) {
            session()->flash('planSettings', 'Workouts can\'t exceed number of days!');
            $this->weekSettings['number_of_workouts'] = $this->weekSettings['number_of_days'];
        }

        /*
         * I can't get validate to work for arrays with keys
         * So this is the validation part.
         */
        for ($i = 0; $i < sizeof($this->weekSettingsArray); $i++) {

            $workoutCounter = 0;
            $cardioCounter = 0;

            // Dynamically change the length of our array
            for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_days']; $j++) {
                // Initialise workouts_indexs and cardio_indexs
                if (isset($this->weekSettingsArray[$i]['workouts_indexs'][$j])) {
                    if ($this->weekSettingsArray[$i]['workouts_indexs'][$j] == true) {
                        $this->weekSettingsArray[$i]['cardio_indexs'][$j] = false; // $this->weekSettingsArray[$i]['workouts_indexs'];
                        if ($this->weekSettingsArray[$i]['cardio_indexs'][$j] == true) {
                            session()->flash('messageWeeks', 'Cardio and workouts can\'t be on the same day!');
                        }
                        $workoutCounter++;
                    }
                } else {
                    $this->weekSettingsArray[$i]['workouts_indexs'][$j] = false;
                }
                /*
                 * array_adapter->one_rep_max
                 */
                if (!isset($this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max'])) {
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max'] = [];
                }
                if (!isset($this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible'])) {
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible'] = [];
                }
                if (!isset($this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension'])) {
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension'] = [];
                }
                if (!isset($this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set'])) {
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set'] = [];
                }
                if (isset($this->workoutSettingsArray[$i][$j]['number_of_exercises'])) {
                    for ($x = 0; $x < $this->workoutSettingsArray[$i][$j]['number_of_exercises']; $x++) {
                        // Is our array shorter than the list?
                        if ($x < $this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max']) {
                            array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max'], 75);
                        }
                        if ($x < $this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible']) {
                            array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible'], false);
                        }
                        if ($x < $this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension']) {
                            array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension'], null);
                        }
                        if ($x < $this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set']) {
                            array_push($this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set'], null);
                        }
                        /*
                        * exercise_sets and exercise_reps
                        */
                        if ($x < $this->workoutSettingsArray[$i][$j]['exercises_reps']) {
                            array_push($this->workoutSettingsArray[$i][$j]['exercises_reps'], 12);
                        }
                        if ($x < $this->workoutSettingsArray[$i][$j]['exercises_sets']) {
                            array_push($this->workoutSettingsArray[$i][$j]['exercises_sets'], 3);
                        }
                    }

                    // Slice our array to size:
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max'] = array_slice($this->workoutSettingsArray[$i][$j]['adapter_array']['one_rep_max'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible'] = array_slice($this->workoutSettingsArray[$i][$j]['adapter_array']['as_many_reps_as_possible'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension'] = array_slice($this->workoutSettingsArray[$i][$j]['adapter_array']['time_under_tension'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);
                    $this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set'] = array_slice($this->workoutSettingsArray[$i][$j]['adapter_array']['rest_per_set'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);

                    /*
                    * exercise_sets and exercise_reps
                    */
                    $this->workoutSettingsArray[$i][$j]['exercises_reps'] = array_slice($this->workoutSettingsArray[$i][$j]['exercises_reps'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);
                    $this->workoutSettingsArray[$i][$j]['exercises_sets'] = array_slice($this->workoutSettingsArray[$i][$j]['exercises_sets'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);
                }
            }
            // Dynamically change the length of our array
            for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_days']; $j++) {
                if (isset($this->weekSettingsArray[$i]['cardio_indexs'][$j])) {
                    if ($this->weekSettingsArray[$i]['cardio_indexs'][$j] == true) {
                        $cardioCounter++;
                    }
                } else {
                    $this->weekSettingsArray[$i]['cardio_indexs'][$j] = false;
                }
            }
            /*
          * Is our cardio array empty
          *
          */
            while (sizeof($this->weekSettingsArray[$i]['cardio']) < $cardioCounter) {
                array_push($this->weekSettingsArray[$i]['cardio'], $this->cardioList[4]);
            }

            //$this->weekSettingsArray[$i]['number_of_workouts'] =
            $this->weekSettingsArray[$i]['number_of_days'] = intval($this->weekSettingsArray[$i]['number_of_days']);
            $this->weekSettingsArray[$i]['number_of_workouts'] = intval($this->weekSettingsArray[$i]['number_of_workouts']);
            $this->weekSettingsArray[$i]['week_name'] = substr($this->weekSettingsArray[$i]['week_name'], 0, 50);
            /*
            * Shrink our arrays
            */
            $this->weekSettingsArray[$i]['number_of_workouts'] = $workoutCounter;
            // Shrink our workoutSettingsArray
            $this->workoutSettingsArray[$i] = array_slice($this->workoutSettingsArray[$i], 0, $workoutCounter);
            $this->weekSettingsArray[$i]['number_of_cardio'] = $cardioCounter;


            $this->weekSettingsArray[$i]['workouts_indexs'] = array_slice($this->weekSettingsArray[$i]['workouts_indexs'], 0, $this->weekSettingsArray[$i]['number_of_days']);
            $this->weekSettingsArray[$i]['cardio_indexs'] = array_slice($this->weekSettingsArray[$i]['cardio_indexs'], 0, $this->weekSettingsArray[$i]['number_of_days']);
            $this->weekSettingsArray[$i]['cardio'] = array_slice($this->weekSettingsArray[$i]['cardio'], 0, $this->weekSettingsArray[$i]['number_of_cardio']);

            if ($this->weekSettingsArray[$i]['number_of_days'] > 10) {
                $this->weekSettingsArray[$i]['number_of_days'] = 10;
                session()->flash('messageNumberOfDays', 'Can\'t have a week longer than 10 days.');

            } elseif ($this->weekSettingsArray[$i]['number_of_days'] <= 3) {
                $this->weekSettingsArray[$i]['number_of_days'] = 3;
            }
            if ($this->weekSettingsArray[$i]['number_of_workouts'] > 10) {
                $this->weekSettingsArray[$i]['number_of_workouts'] = 10;
                session()->flash('messageNumberOfWorkouts', 'Exceeded max number of workouts!');

            } elseif ($this->weekSettingsArray[$i]['number_of_workouts'] <= 1) {
                $this->weekSettingsArray[$i]['number_of_workouts'] = 1;
                session()->flash('messageNumberOfWorkouts', 'Minimum of 1 workout per week!');
            }
            if ($this->weekSettingsArray[$i]['number_of_workouts'] > $this->weekSettingsArray[$i]['number_of_days']) {
                $this->weekSettingsArray[$i]['number_of_workouts'] = $this->weekSettingsArray[$i]['number_of_days'];
                session()->flash('messageNumberOfWorkouts', 'Workouts can\'t be more than the number of days!');
            }

        }

        // Transverse our workouts
        for ($i = 0; $i < $this->planSettings['number_of_weeks']; $i++) {
            // Do we have a week?
            if (isset($this->weekSettingsArray[$i])) {
                // Foreach workout day
                for ($j = 0; $j < $this->weekSettingsArray[$i]['number_of_days']; $j++) {
                    // Is workout day set
                    if (isset($this->workoutSettingsArray[$i][$j]['number_of_exercises'])) {
                        // Transverse our exercises
                        for ($k = 0; $k < $this->workoutSettingsArray[$i][$j]['number_of_exercises']; $k++) {
                            // Is our exercise array set?
                            if (!isset($this->workoutSettingsArray[$i][$j]['exercises'][$k])) {
                                // Set it to one
                                $this->workoutSettingsArray[$i][$j]['exercises'][$k] = 1;
                                $this->workoutSettingsArray[$i][$j]['muscles'][$k] = 1;
                            }
                        }
                        // Truncate our array
                        //var_dump($this->workoutSettingsArray[$i][$j]);
                        $this->workoutSettingsArray[$i][$j]['exercises'] = array_slice($this->workoutSettingsArray[$i][$j]['exercises'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);
                        $this->workoutSettingsArray[$i][$j]['muscles'] = array_slice($this->workoutSettingsArray[$i][$j]['muscles'], 0, $this->workoutSettingsArray[$i][$j]['number_of_exercises']);

                    }
                }
            }
        }
        /*
         * Load our data if selecting a plan
         */
        if ($this->lastPlanSelected != $this->planSelected) {
            $plan = plan::find($this->planSelected);
            // Send a message to the text editor to update
            if ($plan) {
                $this->dispatchBrowserEvent('plan-updated', ['description' => $plan['description']]);
                // Update our default settings
                foreach ($this->planSettings as $key => $value) {
                    $this->planSettings[$key] = $plan[$key];
                }
                // Get our first week and update the default settings....
                if (isset($plan['weeks'][0])) {
                    $week = week::find($plan['weeks'][0]);
                    if ($week) {
                        foreach ($this->weekSettings as $key => $value) {
                            $this->weekSettings[$key] = $week[$key];
                        }
                    }
                }

            }
            $this->lastPlanSelected = $this->planSelected;
        }

        /*
         * Swap our workout with another day....
         *
         */


        if ($this->step == 2) {

            $counter = 0;
            $currentWeekWorkoutIndex = 0;
            for ($i = 0; $i < $this->weekSettingsArray[$this->weekSelected]['number_of_days']; $i++) {
                if ($this->weekSettingsArray[$this->weekSelected]['workouts_indexs'][$i]) {
                    $counter++;

                    if ($counter == ($this->subStep - 1)) {
                        $currentWeekWorkoutIndex = $counter;
                    }
                }
            }
            /*
            * Week reindex
            */
            $this->adjustedWeekIndex = [];
            for ($i = 0; $i < $this->weekSettingsArray[$this->weekSelected]['number_of_days']; $i++) {
                if ($this->weekSettingsArray[$this->weekSelected]['workouts_indexs'][$i]) {
                    array_push($this->adjustedWeekIndex, $this->weekDays[$i]);
                }
            }


            if ($this->workoutIndexWeekDaySelector != $this->workOutKeeper) {
                $weekIndex = $this->weekSelected;

                $workoutIndex = $this->subStep - 1;
                $workoutIndexSelector = $this->workoutIndexWeekDaySelector;

                $workoutSettingsArrayIndex = 0;
                $j = 0;
                for ($i = 0; $i < $this->weekSettingsArray[$weekIndex]['number_of_days']; $i++) {
                    if ($i == $workoutIndexSelector) {
                        $workoutSettingsArrayIndex = $j;
                    }
                    if ($this->weekSettingsArray[$weekIndex]['workouts_indexs'][$i]) {
                        $j++;
                    }

                }
                //session()->flash('moveMessage', "Indexs: " . $workoutIndex . ":" . $workoutIndexSelector . "($workoutSettingsArrayIndex)" );


                // moving/swapping
                $temp = $this->workoutSettingsArray[$weekIndex][$workoutIndex];
                $temp2 = $this->workoutSettingsArray[$weekIndex][$workoutSettingsArrayIndex];

                $this->workoutSettingsArray[$weekIndex][$workoutIndex] = $temp2;
                $this->workoutSettingsArray[$weekIndex][$workoutSettingsArrayIndex] = $temp;
                session()->flash('moveMessage', "$workoutIndex/$workoutSettingsArrayIndex" . $this->weekDays[$currentWeekWorkoutIndex] . ' swapped with ' . $this->weekDays[$this->workoutIndexWeekDaySelector]);

                $this->subStep = $this->workoutIndexWeekDaySelector;

                /*
                $this->subStep++;
                $this->incrementStep();
                $this->decrementStep();
                */
                $this->decrementStep();

            }

        }


        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.create-plan');
    }
}
