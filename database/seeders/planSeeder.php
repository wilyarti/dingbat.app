<?php


namespace Database\Seeders;


use App\Models\circuit;
use App\Models\exercise;
use App\Models\muscle;
use App\Models\plan;
use App\Models\week;
use App\Models\workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;
use function PHPUnit\Framework\throwException;

// Do not run directly. Run by WorkoutSeeder
class planSeeder
{
    public function seeder($fileArray)
    {
        $plan = new plan;
        $plan->weeks = [];
        var_dump($fileArray);
        foreach ($fileArray as $key => $files) {
            $plan->plan_name = $key; //preg_replace('/\.csv$/', '', $key);
            echo $plan->plan_name . "\n";
            $weekCounter = 0;
            foreach ($files as $file) {
                var_dump($file);
                $weeks_row = 0;
                $weeks_col = [];
                $workout_row = [];
                $workout_col = [];
                $twoDarray = array();
                $workouts_indexs_array = [null, null, null, null, null, null, null];
                $cardio_indexs_array = [null, null, null, null, null, null, null];


                // Read file to 2D array
                if (($handle = fopen($file, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $twoDarray[] = $data;
                    }
                    fclose($handle);
                }

                $row = 0;
                // Setup stuff

                $cardio_ids = []; // Store our cardio string.
                // Find weeks and workout location
                foreach ($twoDarray as $rowItem) {
                    $cell = 0;
                    foreach ($rowItem as $cellItem) {
                        if (preg_match('/Week [\d]/', $cellItem)) {
                            echo "Row: $row Column: $cell $cellItem\n";
                            $weeks_row = $row;
                            array_push($weeks_col, $cell);
                        }
                        if (preg_match('/Workout [1-5]/', $cellItem)) {
                            if (preg_match('/Order/', $twoDarray[$row][$cell + 1])) {
                                echo "Row: $row Column: $cell $cellItem\n";
                                array_push($workout_row, $row + 1);
                                array_push($workout_col, $cell + 1);
                            }
                        }
                        if (preg_match('/Training timetable/', $cellItem)) {
                            echo "Training table: \n";
                            $mon_col = $cell + 1;
                            $mon_row = $row + 1;
                            $workout_indexer = 0;
                            $cardio_indexer = 0;

                            for ($i = 0; $i < 7; $i++) {
                                echo $twoDarray[$mon_row + $i][$mon_col] . "\n";
                                if (preg_match('/Workout [1-5]/', $twoDarray[$mon_row + $i][$mon_col])) {
                                    $workouts_indexs_array[$i] = $workout_indexer;
                                    $workout_indexer++;
                                } elseif ((preg_match('/cardio/', $twoDarray[$mon_row + $i][$mon_col]))) {
                                    echo("Found cardio: " . $twoDarray[$mon_row + $i][$mon_col]);
                                    $cardio_indexs_array[$i] = $cardio_indexer;
                                    array_push($cardio_ids, $twoDarray[$mon_row + $i][$mon_col]);
                                    $cardio_indexer++;
                                } else {
                                    //array_push($workouts_indexs_array, 0);
                                    //array_push($cardio_indexs_array, 0);
                                }
                            }
                        }
                        $cell++;
                    }
                    $row++;
                }
                // Find our workouts
                $weeks = [];
                for ($c = 0; $c < sizeof($weeks_col); $c++) {
                    $workout_ids = [];
                    //Save each workout
                    for ($i = 0; $i < sizeof($workout_col); $i++) {
                        $col = $workout_col[$i];
                        $row = $workout_row[$i];
                        $rowIterator = $row;
                        $workout_reps = [];
                        $workout_sets = [];
                        $workout_muscles = [];
                        $workout_exercises = [];
                        $circuit_exercises = [];
                        $hasCircuit = false;
                        while (preg_match('/[A-M]/', $twoDarray[$rowIterator][$col])) {
                            echo "$c:$i " . $twoDarray[$rowIterator][$col + 2] . " Reps" . ": " . $twoDarray[$rowIterator][$weeks_col[$c]] . "  Sets: " . $twoDarray[$rowIterator][$weeks_col[$c] + 1] . "\n";
                            // Circuit
                            if (preg_match('/D1/', $twoDarray[$rowIterator][$col]) && $twoDarray[$rowIterator][$col + 1]) {
                                //throw \Exception("circuit");
                                $hasCircuit = true;
                                $circuit_name = $twoDarray[$rowIterator][$col + 1];
                                $circuit_id = circuit::where('circuit_name', 'like', $circuit_name)->first();
                                if ($circuit_id == null) {
                                    $count = circuit::all()->count();
                                    error_log("Finding a number between 1 and $count");
                                    $circuit_id = circuit::find(rand(1, $count));
                                    error_log("Found random circuit: $circuit_id->circuit_id");
                                }
                                if ($circuit_id == null) {
                                    echo "Error can't find exercise $circuit_name of type $circuit_name\n";
                                    echo "$file: $rowIterator:$col!";
                                    throw Exception("Shit the bed.");
                                } else {
                                    echo "Adding circuit $circuit_id->circuit_id";
                                    array_push($circuit_exercises, $circuit_id->circuit_id);

                                }

                            } elseif (!preg_match('/[D][2-6]/', $twoDarray[$rowIterator][$col])) {
                                $exercise_name = $twoDarray[$rowIterator][$col + 2]; // Skull crusher, BB Row etc
                                $muscle_name = $twoDarray[$rowIterator][$col + 1]; // Core, Quads etc

                                // Added following code to make sure exercise_reps and exercise_sets are numeric...
                                $repsRange = 0;
                                if (preg_match("/-/", $twoDarray[$rowIterator][$weeks_col[$c] + 1])) {
                                    error_log("Matched -");
                                    $range = explode("-", $twoDarray[$rowIterator][$weeks_col[$c] + 1]);
                                    error_log("Min: $range[0] Max: $range[1]");

                                    if (intval($range[1])) {
                                        $repsRange = intval($range[1]);
                                    }
                                } elseif (is_numeric($twoDarray[$rowIterator][$weeks_col[$c] + 1])) {
                                    $repsRange = intval($twoDarray[$rowIterator][$weeks_col[$c] + 1]);
                                }
                                $exercise_sets = intval($twoDarray[$rowIterator][$weeks_col[$c]]); // 3
                                $exercise_reps = $repsRange; // 10
                                $exercise_id = exercise::where('exercise_name', 'like', $exercise_name)->first();
                                $muscle_id = muscle::where('muscle_name', 'like', $muscle_name)->first();
                                if ($exercise_id == null || $muscle_id == null) {
                                    echo "Error can't find exercise $exercise_name of type $muscle_name\n";
                                    echo "$file: $rowIterator:$col!";
                                    throw \Exception("Shit the bed.");
                                } else {
                                    array_push($workout_muscles, $muscle_id->muscle_id);
                                    array_push($workout_exercises, $exercise_id->exercise_id);
                                    array_push($workout_reps, $exercise_reps);
                                    array_push($workout_sets, $exercise_sets);
                                }
                            }
                            $rowIterator++;
                        }
                        $workout = new workout;
                        $workout->workout_name = $plan->plan_name . " Week " . ($weekCounter + 1) . " Workout: " . ($i + 1);
                        $workout->number_of_exercises = sizeof($workout_exercises);
                        $workout->muscles = $workout_muscles; //serialize($workout_exercises);
                        $workout->exercises = $workout_exercises; //serialize($workout_exercises);
                        $workout->exercises_reps = $workout_reps; //serialize($workout_reps);
                        $workout->exercises_sets = $workout_sets; //serialize($workout_sets);
                        if ($hasCircuit) {
                            echo "Adding circuits.\n ";
                            $workout->circuits = $circuit_exercises;
                            $workout->circuit_sets = ["3", "3", "3", "3"];
                            $workout->circuit_reps = ["30 sec", "30 sec", "30 sec", "30 sec"];
                            $workout->number_of_circuits = 1;

                        } else {
                            $workout->circuits = [];
                            $workout->number_of_circuits = 0;
                            $workout->circuit_sets = [];
                            $workout->circuit_reps = [];
                        }
                        $workout->adapter_array = [];
                        $workout->adapter = 0;
                        $workout->save();
                        array_push($workout_ids, $workout->workout_id);
                    }
                    //echo var_dump($workout_ids);
                    echo "Week $weekCounter:\n";
                    $week = new week;
                    $week->week_name = $plan->plan_name . " Week " . ($weekCounter + 1);
                    $week->number_of_days = 7;
                    $week->number_of_workouts = sizeof($workout_ids);
                    $week->workouts = $workout_ids;
                    $week->workouts_indexs = $workouts_indexs_array;
                    $week->cardio = $cardio_ids;
                    $week->cardio_indexs = $cardio_indexs_array;
                    $week->number_of_cardio = sizeof($cardio_ids);

                    $week->save();
                    array_push($weeks, $week->week_id);
                    foreach ($workout_ids as $workout_id) {
                        echo "Workout: " . $workout_id . "\n";
                        $id = workout::Find($workout_id);
                        for ($i = 0; $i < $id->number_of_exercises; $i++) {
                            $exercise_reps = $id->exercises_reps[$i];
                            $exercise_sets = $id->exercises_sets[$i];
                            $exercise_name = exercise::Find($id->exercises[$i])->exercise_name;
                            echo "$exercise_name for $exercise_sets at $exercise_reps\n";
                        }
                    }
                    $weekCounter++;
                }
                $plan->weeks = array_merge($plan->weeks, $weeks);
                //$plan->weeks += $weeks;
                $plan->number_of_weeks += sizeof($weeks);
            }
        }
        $plan->owner = 0;
        $plan->price = 0;
        $plan->description = "";
        $plan->user_id = 0;
        $plan->save();
    }
}
