<?php

namespace Database\Seeders;

use App\Models\day;
use App\Models\exercise;
use App\Models\plan;
use App\Models\week;
use App\Models\workout;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;
use stdClass;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        DB::table('plan')->truncate();
        DB::table('week')->truncate();
        DB::table('workout')->truncate();
        $dataDir = 'data/plans/';
        $files = preg_grep('/^([^.])/', scandir($dataDir));
        $seeder = new planSeeder;
        foreach ($files as $file) {
            if (is_dir($dataDir . $file)) {
                $subfiles = preg_grep('/^([^.])/', scandir($dataDir . $file));
                $fileArray = [];
                foreach ($subfiles as $sf) {
                    if (preg_match("/csv$/", $sf)) {
                        echo "Processing $dataDir$file\n";
                        if (!isset($fileArray[$file])) {
                            $fileArray[$file] = [];
                        }
                        array_push($fileArray[$file], $dataDir . $file . "/" . $sf);
                        //$seeder->seeder($dataDir . $file, $file);
                    }
                }
                if (sizeof($fileArray) >= 1) {
                    //var_dump($fileArray);
                    $seeder->seeder($fileArray);
                }
            } /*
            if (preg_match("/csv$/",$file)) {
                echo "Processing $dataDir$file\n";
                $seeder->seeder($dataDir . $file, $file);
            }
            */
        }

    }
}
