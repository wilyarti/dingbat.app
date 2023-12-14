<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 1;
        if (($handle = fopen("data/equipment.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo "Inserting..." . $data[$c] . "\n";
                    DB::table('equipment')->insert([
                        'equipment_name' => $data[$c],
                    ]);
                }
            }
            fclose($handle);
        }
    }
}
