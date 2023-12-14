<?php

namespace Database\Seeders;

use App\Models\cardio;
use App\Models\circuit;
use App\Models\equipment;
use App\Models\exercise;
use App\Models\exercise_type;
use App\Models\muscle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\throwException;

class CardioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 1;
        if (($handle = fopen("data/cardio.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($data[0]) {
                    $num = count($data);
                    $exercise = new cardio;
                    // Default exercise type if not specified....
                    $cardio_text = [];
                    $row++;
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $exercise->cardio_name = $data[$c];
                                break;
                            case 2:
                                $cardio_text = explode("\n", $data[$c]);;
                                break;
                            default:
                                break;
                        }
                    }
                    echo "Adding: $exercise->cardio_name\n";
                    $exercise->cardio_text = $cardio_text;
                    $exercise->save();
                }
            }
            fclose($handle);
        }
    }
}
