<?php

namespace App\Http\Livewire;

use App\Helpers\Plan;
use App\Helpers\WeekStats;
use App\Models\active_plan;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlanHistory extends Component
{
    public $activePlanList = [];
    public $planSelected;
    public $confirmPlanDeletion = false;
    public $activePlan;

    public $user;

    public function mount()
    {
        $this->user = Auth::user();
        //$this->planSelected = $this->activePlan->active_plan_id;
    }

    public function deleteConfirmed()
    {
        // do something
        $this->confirmPlanDeletion = false;
        $active_plan = active_plan::find($this->planSelected);
        $active_plan->delete();
        session()->flash("message", "Deleted! $this->planSelected");

    }

    public function deleteButton()
    {
        $this->confirmPlanDeletion = true;

    }

    public function makeActive()
    {
        // do something
        $plan = active_plan::find($this->planSelected);
        if ($plan) {
            $plan->end_date = new DateTime('now');
            $plan->save();
            session()->flash("message", "Updated!");
        } else {
            session()->flash("message", "Failed! $this->planSelected");

        }
    }

    public function render()
    {
        /*
        $user= Auth::user();
        $weekStats = new WeekStats();
        $data = $weekStats->statsForWeeks($user, $this->plan, $this->activePlan);
        $this->weeks = $data['weeks'];
        $this->weekStats = $data['weekStats'];
        $this->dailyVolume = $data['dailyVolume'];
        $this->dailySlotVolume = $data['dailySlotVolume'];
        $this->weekStartAndEnd= $data['weekStartAndEnd'];
        $this->weekStartAndEndStr = $data['weekStartAndEndStr'];
        $this->compliance = $data['compliance'];
        $this->targetCompliance= $data['targetCompliance'];
        $this->workouts= $data['workouts'];
        $this->slotVolume= $data['slotVolume'];
        */
        $this->activePlanList = active_plan::where("user", $this->user->id)
            ->join('plan', 'active_plan.plan', '=', 'plan.plan_id')
            ->select('active_plan.*', 'plan.plan_name')
            ->get();

        $activePlanFinder = new Plan;
        $this->activePlan = $activePlanFinder->ActivePlan($this->user->id);
        //$this->planSelected = $this->activePlan->active_plan_id;
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.plan-history');
    }
}
