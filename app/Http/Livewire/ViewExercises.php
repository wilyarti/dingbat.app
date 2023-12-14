<?php

namespace App\Http\Livewire;

use App\Helpers\ColorPalette;
use App\Helpers\ExercisesData;
use App\Helpers\TimeAndDateHelpers;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewExercises extends Component
{
    public $exerciseData;
    public $exerciseCount;
    public $exercises;
    public $volume;
    public $totalReps; // Actually totalvolume
    public $exercisesMuscles;
    public $exerciseStats;
    public $dailyVolume;
    public $dailyMuscleVolume;
    public $chartTitle = "Volume vs Muscle Group";
    public $volumeColorPalette = [];
    public $exercisesColorPalette = [];

    public $start;
    public $end;

    public $startDateSelected;
    public $endDateSelected;

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->user = Auth::user();
        $data = [];
        $exercisesData = new ExercisesData;
        if (isset(request()->dateRange)) {
            $range = request()->dateRange;
            $dates = explode(':', $range);
            // var_dump($dates);
            if (sizeof($dates) == 2) {
                $data['start'] = $dates[0];
                $data['end'] = $dates[1];
            }
        } else {
            $weekFinder = new TimeAndDateHelpers;
            $data = $weekFinder->getCurrentWeek();

        }
        $startDate = new DateTime($data['start'], $this->user->time_zone);
        $endDate = new DateTime($data['end'], $this->user->time_zone);

        $this->startDateSelected = date_format($startDate, 'Y-m-d');
        $this->endDateSelected = date_format($endDate, 'Y-m-d');

        $this->start = date_format($startDate, 'd M y');
        $this->end = date_format($endDate, 'd M y');

        $this->exerciseData = $exercisesData->getExerciseDataForRange($this->user->id, $data['start'], $data['end']);

    }

    public function submit()
    {


        $this->dispatchBrowserEvent('dateRange', ['start' => $this->startDateSelected, 'end' => $this->endDateSelected]);


    }


    public function render()
    {
        $this->user = Auth::user();


        $this->volume = $this->exerciseData['volume'];
        //$this->totalReps = $this->exerciseData['totalReps']; Variable name changed to make compatible
        $this->totalReps = $this->exerciseData['totalVolume'];
        $this->exercisesMuscles = $this->exerciseData['exercisesMuscles'];
        $this->exerciseStats = $this->exerciseData['exerciseStats'];
        $this->dailyVolume = $this->exerciseData['dailyVolume'];
        $this->dailyMuscleVolume = $this->exerciseData['dailyMuscleVolume'];
        $this->exercises = $this->exerciseData['exercises'];

        // var_dump($this->dailyVolume);
        /*
         *  Change our palette
         */
        $this->volumeColorPalette = ColorPalette::getColors(sizeof($this->volume));
        $this->exercisesColorPalette = ColorPalette::getColors(sizeof($this->dailyMuscleVolume));

        //return ['exerciseCount' => $exerciseCount, 'exercises' => $exercises, 'volume' => $volume];
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.view-exercises');
    }
}
