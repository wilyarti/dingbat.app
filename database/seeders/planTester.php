<?php

namespace Database\Seeders;

use App\Helpers\Cloner;
use App\Helpers\PlanTesterHelper;
use App\Models\active_plan;
use App\Models\plan;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class planTester extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = 1;
        $cloner = new Cloner;


        $clonedPlans = [];
        $plans = plan::where("is_clone", false)->get();//plan::findMany(3);
        $planCount = plan::where("is_clone", false)->count();//plan::findMany(3)->count();
        //DB::table('set')->truncate();
        //DB::table('active_plan')->truncate();

        // Test our cloneing as well.
        foreach ($plans as $plan) {
            echo "Cloning : " . $plan->plan_id . ": " . $plan->plan_name . "\n";
            array_push($clonedPlans, $cloner->clonePlan($plan->plan_id, $user_id));
        }

        if (sizeof($clonedPlans) != $planCount) {
            error_log("Error plans not cloned successfully...");
        }

        foreach ($clonedPlans as $planId) {
            $plan = plan::find($planId);
            $plan_id = $plan->plan_id;
            $timeZone = new \DateTimeZone("Australia/Brisbane");
            $tester = new PlanTesterHelper;
            $errors = $tester->testPlan($plan_id, $user_id, $timeZone, false);
            if ($errors) {
                echo "$errors errors\n";
            }
        }
    }
}
