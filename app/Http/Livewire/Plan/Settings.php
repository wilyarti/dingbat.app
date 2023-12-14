<?php

namespace App\Http\Livewire\Plan;

use App\Helpers\Cloner;
use App\Models\active_plan;
use App\Models\plan;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Settings extends Component
{
    public $plan;
    public $start_date;
    protected $rules = [
        'plan' => 'required',
        'start_date' => 'required|date',
    ];
    public $current_active_plan;
    public $planList;
    public $user;
    public $showGlobalPlanBoolean = false;

    public function mount()
    {
        $this->current_active_plan = active_plan::where('user', auth()->user()->id)->latest('updated_at')->first();
        if (!$this->current_active_plan) {
            //$this->plan = null;
            return;
        }
        $newDate = date_format(new DateTime($this->current_active_plan->start_date), 'Y-m-d');
        $this->start_date = $newDate;
        $this->plan = 0;
    }

    public function submit()
    {
        $this->validate();

        $planId = $this->planList[$this->plan]->plan_id;
        error_log($planId);
        $cloner = new Cloner;
        $newPlanId = $cloner->clonePlan($planId, $this->user->id);
        if (!$newPlanId) {
            session()->flash('message', 'Failed to clone plan: ' . $planId);
        } else {
            $newPlan = active_plan::create([
                'plan' => $newPlanId,
                'start_date' => $this->start_date,
                'end_date' => $this->start_date,
                'user' => $this->user->id,
            ]);
            session()->flash('message', 'Plan successfully cloned to your profile: ' . $newPlanId);
            activity()
                ->causedBy($this->user)
                ->inLog("update_plan_settings")
                ->withProperties(['current_active_plan' => $newPlan->plan])
                ->log($this->user->name . " is update plan settings. New plan_id is:  " . $newPlan->plan);
        }

    }

    public function render()
    {
        $this->user = Auth::user();
        if ($this->showGlobalPlanBoolean) {
            $this->planList = plan::where('is_clone', false)->whereIn('owner', [0, -1])->get();
        } else {
            $this->planList = plan::where('is_clone', false)->whereIn('user_id', [$this->user->id, 0])->get();
        }
        //var_dump($planList);
        $this->current_active_plan = null;
        $this->current_active_plan = active_plan::where('user', auth()->user()->id)->latest('updated_at')->first();
        /*
         * Log Activity
         */
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.plan.settings');
    }
}
