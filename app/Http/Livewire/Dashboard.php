<?php

namespace App\Http\Livewire;

use App\Helpers\ColorPalette;
use App\Helpers\PlanAdapter;
use App\Models\active_plan;
use App\Models\circuit;
use App\Models\equipment;
use App\Models\exercise;
use App\Models\exercise_settings;
use App\Models\exercise_type;
use App\Models\muscle;
use App\Models\plan;
use App\Models\week;
use App\Models\workout;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JamesMills\LaravelTimezone\Timezone;
use Livewire\Component;

class Dashboard extends Component
{
    /* Private Variables */
    private $active_plan;
    private $allEquipment;
    private $allExercises;
    private $circuitDB;
    private $enabledEquipment;
    private $enabledExercises;
    private $equipmentSettings;
    private $exerciseSettings;
    private $muscles;
    private $origin;
    private $planLength;
    private $target;
    private $weeks;
    private $reqDateObj;

    /* Dashboard public variables */
    public $adapterData;
    public $cardio;
    public $circuits;
    public $colorPalette;
    public $curDay;
    public $daysIn;
    public $error = null;
    public $exerciseDB;
    public $exercises;
    public $exercisesMuscles;
    public $nextDay;
    public $percentage;
    public $planId;
    public $planName;
    public $planWorkouts;
    public $prevDay;
    public $sortedExercises;
    public $totalDaysInPlan;
    public $totalReps;
    public $user;
    public $volume;
    public $weekId;
    public $workout;
    public $workoutId;
    public $workoutNumber;
    public $completed =[];
    public $showWeighInReminder;

    public function mount()
    {
        $this->user = Auth::user();
        $this->colorPalette = ColorPalette::getColors(12);
        $dateString = request()->dateSelected;
        $defaultTimezone = 'UTC';
        $userTimezone = $this->user->timezone ?? $defaultTimezone;

        if ($this->user->timezone === null) {
            $this->user->timezone = $defaultTimezone;

            // Optionally, you can update the user's timezone in the database
            if ($this->user) {
                $this->user->save();
            }
        }

        if ($dateString) {
            $this->reqDateObj = new DateTime($dateString, new DateTimeZone($this->user->timezone));
        } else {

            $this->reqDateObj = new DateTime('now', new DateTimeZone($this->user->timezone));
        }
        $this->nextDay = new DateTime($dateString, new DateTimeZone($this->user->timezone));
        $this->nextDay->add(new DateInterval('P1D'));

        $this->prevDay = new DateTime($dateString, new DateTimeZone($this->user->timezone));
        $this->prevDay->sub(new DateInterval('P1D'));

        $this->curDay = $this->reqDateObj;
        //var_dump($nextDay->format('d-m-y'));
    }

    public function dismissShowWeighInReminder() {
        $this->showWeighInReminder = false;
    }

    /* Handle Error */
    public function handleError($msg)
    {
        $this->error = $msg;
        activity()
            ->inLog("render_dashboard")
            ->causedBy($this->user)
            ->log($msg);
    }
    public function processTable() {
        $this->completed =[];
        if (!isset($this->workout->number_of_exercises)) {
            return;
        }
        for ($i = 0; $i < $this->workout->number_of_exercises; $i++) {
            $count = DB::table('set')
                ->where('user_id', $this->user->id)
                ->where('week_id', $this->weekId)
                ->where('workout_id', $this->workoutId)
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
    public function findDates() {

        /* Convert Date */
        $this->origin = new DateTime($this->reqDateObj->format('d-M-Y'), new DateTimeZone($this->user->timezone));
        error_log($this->origin->format(DATE_RFC822));
        $this->target = new DateTime($this->active_plan->start_date, new DateTimeZone($this->user->timezone));
        error_log($this->target->format(DATE_RFC822));
        error_log("start date: $this->active_plan->start_date");

        // How many days in? Let's find today
        $this->daysIn = $this->origin->diff($this->target)->days;
        $this->totalDaysInPlan = 0;
        $totalWorkoutsInPlan = 0;
        $totalCardioDaysInPlan = 0;
        foreach ($this->weeks as $checkWeek) {
            $this->totalDaysInPlan += $checkWeek->number_of_days;
            $totalWorkoutsInPlan += $checkWeek->number_of_workouts;
            $totalCardioDaysInPlan += sizeof($checkWeek->cardio);
        }
        error_log("Total Days in Plan: " . $this->totalDaysInPlan);
        error_log("Total Workouts in Plan: " . $totalWorkoutsInPlan);
        error_log("Total Cardio Days in Plan: " . $totalCardioDaysInPlan);
    }

    public function findData() {

        $this->daysIn++;
        $this->planLength = 0;
        $this->planWorkouts = 0;
        $this->workoutNumber = 0;
        $today = null;
        $ourWeek = null;
        $dayOfTheWeek = null;
        /**
         * Test
         *
         */
        //$this->daysIn = random_int(0, 7);
        //$this->daysIn = 3;
        error_log("$this->daysIn days into program...");
        $daysAvailable = 0;
        $daysReducer = 0;
        foreach ($this->weeks as $week) {
            //var_dump($week);
            $daysAvailable += $week->number_of_days;
            error_log("$daysAvailable available days. ");
            if ($daysAvailable >= $this->daysIn) {
                $array_index = $this->daysIn - $daysReducer - 1; // 14 days in minus 7 days minus 1
                //error_log("Week found at $daysAvailable reducer is: $daysReducer index is $array_index");
                // var_dump($week);
                //var_dump($week->workouts_indexs);
                //var_dump($week->week_id);
                $workoutIndex = $week->workouts_indexs[$array_index];
                error_log($workoutIndex);
                $this->workoutNumber += $array_index + 1; // add one due to zero index
                error_log("Workout number: $this->workoutNumber");
                if ($workoutIndex === null) {
                    error_log("null index - no workout");
                    $ourWeek = $week;
                    $dayOfTheWeek = $array_index;
                } else {
                    $today = $week->workouts[$workoutIndex];
                    $ourWeek = $week;
                    $this->weekId = $week->week_id;
                    error_log("Today's workout is $today at index:$workoutIndex");
                }
                break;
            }
            $this->workoutNumber += $week->number_of_workouts;
            $daysReducer += $week->number_of_days;
        }
        foreach ($this->weeks as $week) {
            $this->planLength += $week->number_of_days;
            $this->planWorkouts += $week->number_of_workouts;
        }
        /*
         *
         * End old code
         */
        $this->workout = null;
        $this->workoutId = null;
        $this->exercises = array();
        $this->sortedExercises = null;
        $this->exercisesMuscles = null;
        $this->exerciseDB = null;

        $this->cardio = null;

        /*
         * EQUIPMENT/EXERCISE SETTINGS
         */
        $this->equipmentSettings = exercise_settings::where('user', $this->user->id)->where('exercise_setting_type', 0)->latest('updated_at')->first();
        $this->exerciseSettings = exercise_settings::where('user', $this->user->id)->where('exercise_setting_type', 1)->latest('updated_at')->first();
        $this->enabledEquipment = []; // Body weight is never disabled.
        $this->enabledExercises = [0]; // No exercise type is 0
        $this->allEquipment = equipment::all();
        $this->allExercises = exercise_type::all();

        /*
         * Enabled Equipment
         */
        if ($this->equipmentSettings) {
            for ($i = 0; $i < sizeof($this->equipmentSettings->exercise_settings_key); $i++) {
                //error_log("Filtering exercise key: $i" . $this->equipmentSettings->exercise_settings_key[$i]);
                if ($this->equipmentSettings->exercise_settings_value[$i]) {
                    //error_log("Option $i enabled");
                    array_push($this->enabledEquipment, $this->equipmentSettings->exercise_settings_key[$i]);
                }
            }
        } else {
            for ($i = 0; $i < sizeof($this->allEquipment); $i++) {
                array_push($this->enabledEquipment, $this->allEquipment[$i]->equipment_id);
            }
        }
        /*
         * Enabled Exercises
         */
        if ($this->exerciseSettings) {
            for ($i = 0; $i < sizeof($this->exerciseSettings->exercise_settings_key); $i++) {
                //error_log("Filtering exercise key: $i" . $this->exerciseSettings->exercise_settings_key[$i]);
                if ($this->exerciseSettings->exercise_settings_value[$i]) {
                    //error_log("Option $i enabled");
                    array_push($this->enabledExercises, $this->exerciseSettings->exercise_settings_key[$i]);
                }
            }
        } else {
            for ($i = 0; $i < sizeof($this->allExercises); $i++) {
                array_push($this->enabledExercises, $this->allExercises[$i]->exercise_id);
            }
        }

        if ($today) {
            // Get the workout for today
            $this->workout = workout::find($today);
            $this->workoutId = $this->workout->workout_id;
            //array_pad($exercises, $workout->number_of_workouts, null);
            //echo "Workout id: $workout->workout_id";
            // Get the exercises for today
            error_log("Number of workouts: " . $this->workout->number_of_exercises);
            //$exDB = exercise::findMany($this->workout->exercises);

            for ($i = 0; $i < $this->workout->number_of_exercises; $i++) {
                error_log("exercise id: " . $this->workout->exercises[$i]);
                //if (isset($exDB[$i])) {
                    array_push($this->exercises, exercise::find($this->workout->exercises[$i]));
                //}
            }

            //$this->exercises = exercise::find($this->workout->exercises);
            if ($this->workout->number_of_circuits > 0) {
                $this->circuitDB = circuit::all();
                $this->circuits = null;
                $this->circuits = circuit::find($this->workout->circuits);
            }

            $this->sortedExercises = $this->exercises;
            $lookup = array_fill(0, $this->workout->number_of_exercises, 0);
            for ($i = 0; $i < $this->workout->number_of_exercises; $i++) {
                $lookup[$i] = $this->workout->exercises[$i];
            }
            for ($i = 0; $i < sizeof($lookup); $i++) {
                for ($j = $i; $j < $this->workout->number_of_exercises; $j++) {
                    if ($this->exercises[$j]->exercise_id == $lookup[$i]) {
                        $this->sortedExercises[$i] = $this->exercises[$j];
                    }
                }
            }
            // Get muscle types
            $this->muscles = muscle::all();
            $this->exercisesMuscles = array();
            $this->exerciseDB = array();

            $data = [];
            // Build exercise Database....
            //error_log(var_dump($this->enabledEquipment));
            $data = exercise::whereIn('muscle_id', $this->workout->muscles)->get();
            for ($i = 0; $i < sizeof($this->workout->muscles); $i++) {
                //echo muscle::find($this->exercises[$i]->muscle_id)->muscle_name;
                //$data = exercise::where('muscle_id', $this->workout->muscles[$i])->get();
                if ($this->equipmentSettings) {
                    $filteredData = [];
                    error_log("size of data: " . sizeof($data));
                    for ($j = 0; $j < sizeof($data); $j++) {
                        if (in_array($data[$j]->equipment_id, $this->enabledEquipment)) {
                            //error_log("Found $j in haystack.");
                            array_push($filteredData, $data[$j]);
                        } else {
                            // error_log($data[$i]->exercise_id . " not found in list of types...");
                        }
                    }
                    $data = $filteredData;
                }
                if ($this->exerciseSettings) {
                    $filteredData = [];
                    error_log("size of data: " . sizeof($data));
                    for ($j = 0; $j < sizeof($data); $j++) {
                        if (in_array($data[$j]->exercise_type, $this->enabledExercises)) {
                            // error_log("Found $j in haystack.");
                            array_push($filteredData, $data[$j]);
                        } else {
                            // error_log($data[$i]->exercise_id . " not found in list of types...");
                        }
                    }
                    $data = $filteredData;
                }
                array_push($this->exerciseDB, $data);
                //var_dump(exercise::where('muscle_id',$this->exercises[$i]->muscle_id ));
                //echo "Type: -> " . $this->muscles[$this->exercises[$i]->muscle_id]->muscle_name . "<br/>";
                array_push($this->exercisesMuscles, $this->muscles[$this->exercises[$i]->muscle_id - 1]->muscle_name);
            }
            error_log("----- Debug ------");
            error_log("workout#: " . $this->workout->workout_id);
            error_log("Exercises: ");
            foreach ($this->exercises as $exercise) {
                error_log("$exercise->exercise_id,");
            }
            foreach ($this->workout->exercises as $i) {
                error_log("$i");
            }
        } elseif ($ourWeek) {
            error_log("No workout.");
            $cardioDay = $ourWeek->cardio_indexs[$dayOfTheWeek];
            error_log("Cardio index $dayOfTheWeek");
            if (isset($cardioDay)) {
                $this->cardio = $ourWeek->cardio[$cardioDay];
            }
        }
        //var_dump($this->workout);
        $this->percentage = 100 / $this->planWorkouts * $this->workoutNumber * 1;
    }

    public function render()
    {
        /*
         * Old Dashboard controller code
         */
        //$this->plans = plan::get()->toArray();
        $this->active_plan = active_plan::where('user', $this->user->id)->latest('updated_at')->first();

        if (!$this->active_plan || !plan::find($this->active_plan->plan)) {
            $this->handleError("Please select a plan! <a href=\"/plan\">Click here.</a>");
            return view('livewire.dashboard');
        }
        $currentPlan = plan::find($this->active_plan->plan);
        if (!$currentPlan) {
           $this->handleError("Plan not found.");
            return view('livewire.dashboard');
        }
        $this->planName = $currentPlan->plan_name;
        $this->planId = $currentPlan->plan_id;
        //var_dump($this->active_plan);
        $this->weeks = week::findMany($currentPlan->weeks); // here

        if (!$this->weeks) {
            $this->handleError("Plan has no weeks.");
            return view('livewire.dashboard');
        }
        $this->findDates();
        if (!$this->totalDaysInPlan) {
            $this->handleError("Plan is corrupted or empty");
            return view('livewire.dashboard');
        }

        $dateInterval = $this->origin->diff($this->target);

        error_log($this->origin->format(DATE_RFC822));
        error_log($this->target->format(DATE_RFC822));
        if ($this->origin->format(DATE_RFC822) == $this->target->format(DATE_RFC822)) {
            error_log("Same day");
        } elseif ($this->origin < $this->target) {
            $this->handleError("Your program starts in " . $dateInterval->format('%a Day(s). Check your timezone settings... <a href="/user/profile">Click here.</a>'));
            return view('livewire.dashboard');
        } elseif (($this->daysIn) >= $this->totalDaysInPlan) {
            $this->handleError ("Your program has finished! <a href=\"/plan\">Click here.</a>");
            return view('livewire.dashboard');
        }
        $this->findData();
        /*
         * Get workout percentages for today....
         */

        $this->totalReps = 0;
        $this->volume = [];
        if ($this->workout) {
            for ($i = 0; $i < $this->workout->number_of_exercises; $i++) {
                if (!isset($this->workout->exercises_sets[$i])) {
                    $this->handleError("Set not found. Plan most likely corrupted.");
                    return view('livewire.dashboard');
                    //return null;
                }
                $sets = intval($this->workout->exercises_sets[$i]);
                if (preg_match("/-/", $this->workout->exercises_reps[$i])) {
                    error_log("Matched -");
                    $range = explode("-", $this->workout->exercises_reps[$i]);
                    error_log("Min: $range[0] Max: $range[1]");

                    if (intval($range[1])) {
                        $this->totalReps += $sets * $range[1];
                        $muscle_name = $this->muscles[$this->workout->muscles[$i] - 1]->muscle_name; // -1 on the index
                        if (isset($this->volume[$muscle_name])) {
                            $this->volume[$muscle_name] += $sets * $range[1];
                        } else {
                            $this->volume[$muscle_name] = $sets * $range[1];
                        }
                    }
                } elseif (is_numeric($this->workout->exercises_reps[$i])) {
                    $range = intval($this->workout->exercises_reps[$i]);
                    $this->totalReps += $sets * $range;
                    $muscle_name = $this->muscles[$this->workout->muscles[$i] - 1]->muscle_name; // -1 on the index
                    if (isset($this->volume[$muscle_name])) {
                        $this->volume[$muscle_name] += $sets * $range;
                    } else {
                        $this->volume[$muscle_name] = $sets * $range;
                    }
                    error_log("Workout_ID: $this->workoutId" . " Muscle_ID: " . $this->workout->muscles[$i]);
                }
                error_log("Integer values: $sets ");
            }
        }
        // Sort array in descending order by value
        arsort($this->volume);


        /*
         * Handle our adapter
         *
         */
        $this->adapterData = null;
        if (isset($this->workout['adapter'])) {
            $adapter = new PlanAdapter;
            $this->adapterData = $adapter->wendlerAdapter($currentPlan->plan_id, $this->workout, Auth::user()->id);
        }

        /*
         *  Change our palette
         */
        $this->colorPalette = ColorPalette::getColors(sizeof($this->volume));
        //var_dump("Size of volume: " . sizeof($this->volume));

        /*
         * Process data for livewire.exercise.table.blade.php
         */
        $this->processTable();

        /*
         * Show reminders/alerts
         */
        if (($this->daysIn -1) % 7 == 0 || $this->daysIn == 1) {
            error_log("Days in $this->daysIn " . $this->daysIn % 7 );
            $this->showWeighInReminder = true;
        }
        /*
         * Log Activity
         */
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.dashboard');
    }
}
