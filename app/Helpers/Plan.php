<?php


namespace App\Helpers;


use App\Models\active_plan;

class Plan
{
    public function ActivePlan($user_id)
    {
        $active_plan = active_plan::where('user', $user_id)->latest('updated_at')->first();

        return $active_plan;
    }

    public function lastPlanById($user_id, $plan_id)
    {
        $active_plan = active_plan::where('user', $user_id)
            ->where("plan", $plan_id)
            ->latest('updated_at')->first();

        return $active_plan;
    }
}
