<?php

namespace App\Http\Livewire\Plan;

use App\Models\equipment;
use App\Models\exercise_settings;
use App\Models\exercise_type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SettingsExercises extends Component
{
    /*
     * Equipment Settings
     */
    public $bodyweight;
    public $barbell;
    public $dumbbell;
    public $kettlebell;
    public $resistance_band;
    public $cables;
    public $chin_up_bar;
    public $step_box;
    public $swiss_ball;
    public $medicine_ball;
    public $suspension;
    public $weight_plate;
    public $other;
    public $equipmentSettingsList = [
        'Bodyweight' => 'bodyweight',
        'Barbell' => 'barbell',
        'Dumbbell' => 'dumbbell',
        'Kettlebell' => 'kettlebell',
        'Resistance Bands' => 'resistance_band',
        'Cables' => 'cables',
        'Chin Up Bar' => 'chin_up_bar',
        'Step Box' => 'step_box',
        'Swiss Ball' => 'swiss_ball',
        'Medicine Ball' => 'medicine_ball',
        'Suspension / TRX' => 'suspension',
        'Weight Plate' => 'weight_plate',
        'Other' => 'other',
    ];
    public $exerciseSettingsList = [
      'overhead' => 'overhead',
        'Lunge' => 'lunge',
        'Squat' => 'squat',
        'Deadlift' => 'deadlift',
        'Jump' => 'jump',
    ];

    /*
     * Exercise Settings
     */
    public $overhead;
    public $lunge;
    public $squat;
    public $deadlift;
    public $jump;

    public function mount() {
        // Equipment settings
        $lastSetting = exercise_settings::where('user', auth()->user()->id)->where('exercise_setting_type', 0)->latest('updated_at')->first();

        if ($lastSetting) {
            $this->bodyweight = $lastSetting->exercise_settings_value[0];
            $this->barbell = $lastSetting->exercise_settings_value[1];
            $this->dumbbell = $lastSetting->exercise_settings_value[2];
            $this->kettlebell = $lastSetting->exercise_settings_value[3];
            $this->resistance_band = $lastSetting->exercise_settings_value[4];
            $this->cables = $lastSetting->exercise_settings_value[5];
            $this->chin_up_bar = $lastSetting->exercise_settings_value[6];
            $this->step_box = $lastSetting->exercise_settings_value[7];
            $this->swiss_ball = $lastSetting->exercise_settings_value[8];
            $this->medicine_ball = $lastSetting->exercise_settings_value[9];
            $this->suspension = $lastSetting->exercise_settings_value[10];
            $this->weight_plate = $lastSetting->exercise_settings_value[11];
            $this->other = $lastSetting->exercise_settings_value[12];
        } else {
            $this->bodyweight = true;
            $this->barbell = true;
            $this->dumbbell = true;
            $this->kettlebell = true;
            $this->resistance_band = true;
            $this->cables = true;
            $this->chin_up_bar = true;
            $this->step_box = true;
            $this->swiss_ball = true;
            $this->medicine_ball = true;
            $this->suspension = true;
            $this->weight_plate = true;
            $this->other = true;
        }

        // Exercise settings
        $lastSetting = exercise_settings::where('user', auth()->user()->id)->where('exercise_setting_type', 1)->latest('updated_at')->first();

        if ($lastSetting) {
            $this->overhead = $lastSetting->exercise_settings_value[0];
            $this->lunge = $lastSetting->exercise_settings_value[1];
            $this->squat = $lastSetting->exercise_settings_value[2];
            $this->deadlift = $lastSetting->exercise_settings_value[3];
            $this->jump = $lastSetting->exercise_settings_value[4];

        } else {
            $this->overhead = true;
            $this->lunge = true;
            $this->squat = true;
            $this->deadlift = true;
            $this->jump = true;
        }
        error_log("Done mounting.");
        error_log("Done.");

    }
    public function submit() {
        $user = Auth::user();
        //$setting = new exercise_settings;
        $settings = equipment::all();
        $settingsArrayKeys = [];
        $settingsArrayValues = [];
        foreach ($settings as $key) {
            error_log($key->equipment_id . ":" .$key->equipment_name);
            array_push($settingsArrayKeys, $key->equipment_id);
            array_push($settingsArrayValues, true);
        }
        $settingsArrayValues[0] = $this->bodyweight;
        $settingsArrayValues[1] = $this->barbell;
        $settingsArrayValues[2] = $this->dumbbell;
        $settingsArrayValues[3] = $this->kettlebell;
        $settingsArrayValues[4] = $this->resistance_band;
        $settingsArrayValues[5] = $this->cables;
        $settingsArrayValues[6] = $this->chin_up_bar;
        $settingsArrayValues[7] = $this->step_box;
        $settingsArrayValues[8] = $this->swiss_ball;
        $settingsArrayValues[9] = $this->medicine_ball;
        $settingsArrayValues[10] = $this->suspension;
        $settingsArrayValues[11] = $this->weight_plate;
        $settingsArrayValues[12] = $this->other;

        $new = new exercise_settings;
        $new->user = Auth::user()->id;
        $new->exercise_setting_type = 0;
        $new->exercise_settings_key = $settingsArrayKeys;
        $new->exercise_settings_value = $settingsArrayValues;
        $new->save();

        error_log("Done saving");
        activity()
            ->causedBy($user)
            ->inLog("update_exercise_settings")
            ->withProperties(['exercise_settings'=> $new])
            ->log($user->name . " is update exercise settings. New exercise_settings is:  " .  $new);
        session()->flash('message', 'Options updated.');
    }
    public function submit2() {
        $user = Auth::user();
        $settings = exercise_type::all();
        $settingsArrayKeys = [];
        $settingsArrayValues = [];
        foreach ($settings as $key) {
            error_log($key->exercise_type_name);
            array_push($settingsArrayKeys, $key->exercise_type_id);
            array_push($settingsArrayValues, true);
        }

        $settingsArrayValues[0] = $this->overhead;
        $settingsArrayValues[1] = $this->lunge;
        $settingsArrayValues[2] = $this->squat;
        $settingsArrayValues[3] = $this->deadlift;
        $settingsArrayValues[4] = $this->jump;

        $new = new exercise_settings;
        $new->user = $user->id;
        $new->exercise_setting_type = 1;
        $new->exercise_settings_key = $settingsArrayKeys;
        $new->exercise_settings_value = $settingsArrayValues;
        $new->save();
        error_log( "Finished saving exercise settings.");

        activity()
            ->causedBy($user)
            ->inLog("update_exercise_settings")
            ->withProperties(['exercise_settings'=> $new])
            ->log($user->name . " is update exercise settings. New exercise_settings is:  " .  $new);
        session()->flash('message2', 'Options updated.');

    }
    public function save() {
        error_log( "Done save() function");
    }
    public function render()
    {
        $user = Auth::user();

        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.plan.settings-exercises');
    }
}
