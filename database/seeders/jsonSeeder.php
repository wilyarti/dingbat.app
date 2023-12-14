<?php

namespace Database\Seeders;

use App\Models\exercise;
use App\Models\plan;
use App\Models\week;
use App\Models\workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class jsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $planSettings;
    public $weekSettings;
    public $weekSettingsArray;
    public $workoutSettingsArray;

    public function run()
    {
        // DB::table('plan')->truncate();
        // DB::table('week')->truncate();
        // DB::table('workout')->truncate();
        $dataDir = 'data/jsonPlans/';
        $files = preg_grep('/^([^.])/', scandir($dataDir));
        foreach ($files as $file) {
            if (preg_match("/json$/", $file)) {
                $this->seeder($dataDir . $file);
            }
        }
    }

    public function seeder($file)
    {
        echo $file . "\n";
        $str = file_get_contents($file);
        $data = json_decode($str, true);
        $this->planSettings = $data['planSettings'];
        $this->weekSettings = $data['weekSettings'];
        $this->weekSettingsArray = $data['weekSettingsArray'];
        $this->workoutSettingsArray = $data['workoutSettingsArray'];


        $this->planSettings['weeks'] = []; // Zero out our weeks or extra weeks will be added corrupting it.
        $plan = new plan;


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
        $plan['owner'] = 0;
        $plan['user_id'] = 0;


        $plan->save();
        echo "Saved plan: " . $this->planSettings['plan_name'] . " : " . $plan->plan_id . "\n";
    }
}
