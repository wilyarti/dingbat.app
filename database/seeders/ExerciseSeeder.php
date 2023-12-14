<?php

namespace Database\Seeders;

use App\Models\equipment;
use App\Models\exercise;
use App\Models\exercise_type;
use App\Models\muscle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\throwException;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercise_type = exercise_type::where('exercise_type_id', 1)->first();

        echo $exercise_type;
        $row = 1;
        if (($handle = fopen("data/exercise.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $exercise = new exercise;
                // Default exercise type if not specified....
                $exercise->exercise_type = 0;
                $row++;
                for ($c=0; $c < $num; $c++) {
                    switch ($c) {
                        case 0:
                            $muscle_id = muscle::where('muscle_name','like', $data[$c])->first();
                            if (empty($muscle_id)) {
                                echo "Error can't be null";
                            } else {
                                $exercise->muscle_id = $muscle_id->muscle_id;
                            }
                            break;
                        case 1:
                            $equipment_id = equipment::where('equipment_name','like', $data[$c])->first();
                            if (empty($equipment_id)) {
                                echo "Error can't be null";
                            } else {
                                $exercise->equipment_id = $equipment_id->equipment_id;
                            }
                            break;
                        case 2:
                            if ($data[$c] == "1") {
                                $exercise->exercise_type = 1;
                            }
                            break;
                        case 3:
                            if ($data[$c] == "1") {
                                $exercise->exercise_type = 2;
                            }
                            break;
                        case 4:
                            if ($data[$c] == "1") {
                                $exercise->exercise_type = 3;
                            }
                            break;
                        case 5:
                            if ($data[$c] == "1") {
                                $exercise->exercise_type = 4;
                            }
                            break;
                        case 6:
                            if ($data[$c] == "1") {
                                $exercise->exercise_type = 5;
                            }
                            break;
                        case 7:
                            $exercise->exercise_name = $data[$c];
                        case 8:
                            $exercise->exercise_link = $data[$c];
                        default:
                            break;
                    }
                }
                echo "Adding: $exercise->exercise_name  : $exercise->muscle_id : $exercise->equipment_id : $exercise->exercise_type\n";
                $exercise->save();
            }
            fclose($handle);
        }
    }
}
