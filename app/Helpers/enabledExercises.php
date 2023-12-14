<?php


namespace App\Helpers;


use App\Models\equipment;
use App\Models\exercise;
use App\Models\exercise_settings;
use App\Models\exercise_type;

class enabledExercises
{
    public function enabledEquipment($equipmentSettings)
    {
        /*
         * EQUIPMENT/EXERCISE SETTINGS
         */
        $enabledEquipment = []; // Body weight is never disabled.
        $allEquipment = equipment::all();

        /*
         * Enabled Equipment
         */
        if ($equipmentSettings) {
            for ($i = 0; $i < sizeof($equipmentSettings->exercise_settings_key); $i++) {
                //error_log("Filtering exercise key: $i" . $equipmentSettings->exercise_settings_key[$i]);
                if ($equipmentSettings->exercise_settings_value[$i]) {
                 //   error_log("Option $i enabled");
                    array_push($enabledEquipment, $equipmentSettings->exercise_settings_key[$i]);
                }
            }
        } else {
            for ($i = 0; $i < sizeof($allEquipment); $i++) {
                array_push($enabledEquipment, $allEquipment[$i]->equipment_id);
            }
        }
        return $enabledEquipment;

    }

    public function enabledExercises($exerciseSettings)
    {
        /*
         * EQUIPMENT/EXERCISE SETTINGS
         */
        $enabledExercises = [0]; // No exercise type is 0
        $allExercises = exercise_type::all();
        /*
        * Enabled Exercises
        */
        if ($exerciseSettings) {
            for ($i = 0; $i < sizeof($exerciseSettings->exercise_settings_key); $i++) {
                //error_log("Filtering exercise key: $i" . $exerciseSettings->exercise_settings_key[$i]);
                if ($exerciseSettings->exercise_settings_value[$i]) {
          //          error_log("Option $i enabled");
                    array_push($enabledExercises, $exerciseSettings->exercise_settings_key[$i]);
                }
            }
        } else {
            for ($i = 0; $i < sizeof($allExercises); $i++) {
                array_push($enabledExercises, $allExercises[$i]->exercise_id);
            }
        }
        return $enabledExercises;
    }

    public function getEnabledExercises($user_id, $muscle_ids)
    {
        $exerciseDB = [];
        $equipmentSettings = exercise_settings::where('user', $user_id)->where('exercise_setting_type', 0)->latest('updated_at')->first();
        $exerciseSettings = exercise_settings::where('user', $user_id)->where('exercise_setting_type', 1)->latest('updated_at')->first();

        $data = exercise::whereIn('muscle_id', $muscle_ids)->get();
        if ($equipmentSettings) {
            $enabledEquipment = $this->enabledEquipment($equipmentSettings);
            $filteredData = [];
      //      error_log("size of data: " . sizeof($data));
            for ($j = 0; $j < sizeof($data); $j++) {
                if (in_array($data[$j]->equipment_id, $enabledEquipment)) {
                //    error_log("Found $j in haystack.");
                    array_push($filteredData, $data[$j]);
                } else {
                    // error_log($data[$i]->exercise_id . " not found in list of types...");
                }
            }
            $data = $filteredData;
        }
        if ($exerciseSettings) {
            $enabledExercises = $this->enabledExercises($exerciseSettings);
            $filteredData = [];
            error_log("size of data: " . sizeof($data));
            for ($j = 0; $j < sizeof($data); $j++) {
                if (in_array($data[$j]->exercise_type, $enabledExercises)) {
              //      error_log("Found $j in haystack.");
                    array_push($filteredData, $data[$j]);
                } else {
                    // error_log($data[$i]->exercise_id . " not found in list of types...");
                }
            }
            $data = $filteredData;
        }
        array_push($exerciseDB, $data);
        return $exerciseDB;
    }
}
