<?php

namespace Database\Seeders;

use App\Models\circuit;
use App\Models\equipment;
use App\Models\exercise;
use App\Models\exercise_type;
use App\Models\muscle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\throwException;

class CircuitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercise_type = exercise_type::where('exercise_type_id', 1)->first();
        DB::table('circuit')->truncate();
        $row = 1;
        if (($handle = fopen("data/circuit.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($data[0]) {

                    $num = count($data);
                    $exercise = new circuit;
                    // Default exercise type if not specified....
                    $exercises = [];
                    $exercise_types = [];
                    $row++;
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $exercise->circuit_name = $data[$c];
                                break;
                            case 1:
                                $equipment_id = equipment::where('equipment_name', 'like', $data[$c])->first();
                                if (empty($equipment_id)) {
                                    throw new \Exception("Invalid equipment id. $data[$c]");
                                } else {
                                    $exercise->equipment_id = $equipment_id->equipment_id;
                                }
                                break;
                            case 2:
                                if ($data[$c] == "1") {
                                    array_push($exercise_types, 1);
                                } else {
                                    array_push($exercise_types, 0);
                                }
                                break;
                            case 3:
                                if ($data[$c] == "1") {
                                    array_push($exercise_types, 2);
                                }else {
                                    array_push($exercise_types, 0);
                                }
                                break;
                            case 4:
                                if ($data[$c] == "1") {
                                    array_push($exercise_types, 3);
                                }else {
                                    array_push($exercise_types, 0);
                                }
                                break;
                            case 5:
                                if ($data[$c] == "1") {
                                    array_push($exercise_types, 4);
                                }else {
                                    array_push($exercise_types, 0);
                                }
                                break;
                            case 6:
                                if ($data[$c] == "1") {
                                    array_push($exercise_types, 5);
                                }else {
                                    array_push($exercise_types, 0);
                                }
                                break;
                            case 11:
                                $exercise->circuit_link = $data[$c];
                            default:
                                if ($c >= 7 && $c <= 10) {
                                    array_push($exercises, $data[$c]);
                                }
                                break;
                        }
                    }
                    echo "Adding: $exercise->circuit_name\n";
                    $exercise->exercise_list = $exercises;
                    $exercise->exercise_types = $exercise_types;
                    $exercise->number_of_exercises = sizeof($exercises);
                    $exercise->save();
                }
            }
            fclose($handle);
        }
    }
}
