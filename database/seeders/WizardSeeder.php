<?php

namespace Database\Seeders;

use App\Models\day;
use App\Models\exercise;
use App\Models\plan;
use App\Models\week;
use App\Models\wizards;
use App\Models\workout;
use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Integer;
use stdClass;

class WizardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $harry = new wizards();
        $harry->is_wizard = true; // You're a wizard Harry!
        $harry->user_id = 1;
        $harry->save();
    }
}
