<?php

namespace App\Http\Livewire;

use App\Helpers\WeekStats;
use App\Models\plan;
use App\Models\week;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewWeek extends Component
{
    public $selectedWeek;
    public $plan;

    public $weeks;
    public $weekStats;
    public $slotVolume;
    public $dailySlotVolume;
    public $weekStartAndEnd;
    public $weekStartAndEndStr;
    public $compliance;
    public $targetCompliance;
    public $workouts;
    public $dailyVolume;
    public $activePlan;
    public $colorPalette = [
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
        '#e76f51', '#ff5a00', '#e9c46a', '#2a9d8f', '#264653', '#b5838d', '#e5989b',
    ];

    public function loadData()
    {
        $user = Auth::user();
        $weekStats = new WeekStats();
        $data = $weekStats->statsForWeeks($user, $this->plan, $this->activePlan);
        $this->weeks = $data['weeks'];
        $this->weekStats = $data['weekStats'];
        $this->dailyVolume = $data['dailyVolume'];
        $this->dailySlotVolume = $data['dailySlotVolume'];
        $this->weekStartAndEnd = $data['weekStartAndEnd'];
        $this->weekStartAndEndStr = $data['weekStartAndEndStr'];
        $this->compliance = $data['compliance'];
        $this->targetCompliance = $data['targetCompliance'];
        $this->workouts = $data['workouts'];
        $this->slotVolume = $data['slotVolume'];

    }

    public function mount($planID, $weekID)
    {
        $this->selectedWeek = week::find($weekID);

        $planFinder = new \App\Helpers\Plan;
        $this->activePlan = $planFinder->lastPlanById(Auth::user()->id, $planID);
        $this->plan = plan::find($this->activePlan->plan);

        // Plan specific stuff
        $target = new DateTime($this->activePlan->start_date, new DateTimeZone(Auth::user()->timezone));
        $this->planStartDateStr = $target->format('d F Y');

        // What week have we started?
        $this->currentDate = new DateTime('now', new DateTimeZone(Auth::user()->timezone));
        $this->loadData();
    }

    public function render()
    {
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.view-week');
    }
}
