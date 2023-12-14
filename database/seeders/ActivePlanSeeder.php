<?php

namespace Database\Seeders;

use App\Models\active_plan;
use Illuminate\Database\Seeder;

class ActivePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $active_plan =  new active_plan;
        $active_plan->start_date = date_create("2021-05-17");
        $active_plan->end_date = date_create("2021-08-09");
        $active_plan->plan = 1;
        $active_plan->user = 1;
        $active_plan->save();
    }
}
