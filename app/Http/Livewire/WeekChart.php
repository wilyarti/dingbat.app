<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WeekChart extends Component
{
    public $dailyVolume;
    public $dailySlotVolume;
    public $weekChartSelected;
    public $colorPalette;

    public function mount($dailyVolume, $dailySlotVolume, $weekChartSelected, $colorPalette)
    {
        $this->dailyVolume = $dailyVolume;
        $this->dailySlotVolume = $dailySlotVolume;
        $this->weekChartSelected = $weekChartSelected;
        $this->colorPalette = $colorPalette;
    }

    public function render()
    {
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.week-chart');
    }
}
