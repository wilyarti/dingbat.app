<?php

namespace App\Http\Livewire;

use App\Helpers\BodyMeasurementsData;
use App\Helpers\ExercisesData;
use App\Helpers\TimeAndDateHelpers;
use App\Models\bodyMeasurement;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class History extends Component
{
    public $measurements;
    public $editingEnabled = true;
    public $editEntry;

    public $keyedValues;
    public $map;

    public $start;
    public $end;

    public $keys = ['Height', 'Weight',
        'Fat Percentage', 'Visceral Fat Level', 'Fat Mass',
        'BMI', 'Basal Metabolic Rate', 'Neck Circumference',
        'Chest Circumference', 'Abdomen Circumference',
        'Hip Circumference', 'Left Bicep Circumference', 'Right Bicep Circumference',
        'Left Thigh Circumference', 'Right Thigh Circumference', 'Left Calf Circumference',
        'Right Calf Circumference',
        'Chest (mm)', 'Abdominal (mm)', 'Thigh (mm)', 'Suprailiac (mm)',
        'Axillary (mm)', 'Lower Back (mm)', 'Tricep (mm)',
        'Subscapular (mm)', 'Calf (mm)', 'Bicep (mm)',
        'Weight (kgs)', 'Age (years)', 'Parillo Test',
        'Jackson Pollock 7 Site Test', 'Jackson Pollock 3 Site Test', 'Durnin Test'
    ];

    public $value = ['body_height', 'body_weight',
        'body_fat_percentage', 'visceral_fat_level', 'body_fat_mass',
        'bmi', 'bmr', 'neck_circumference',
        'chest_circumference', 'abdomen_circumference',
        'hip_circumference', 'left_bicep_circumference', 'right_bicep_circumference',
        'left_thigh_circumference', 'right_thigh_circumference', 'left_calf_circumference',
        'right_calf_circumference',
        'chest_skinfold', 'abdominal_skinfold', 'thigh_skinfold',
        'suprailiac_skinfold', 'axillary_skinfold', 'lower_back_skinfold',
        'tricep_skinfold', 'subscapular_skinfold', 'calf_skinfold',
        'bicep_skinfold', 'body_weight', 'age', 'parillo', 'jp7', 'jp3', 'durnin',
    ];

    public $dateRange = ['1 Week' => 7, '2 Weeks' => 14,'1 Month' => 30, '3 months' => 90, '6 Months' => 180, '1 Year' => 365, '3 Years' => 365 * 3];
    public $rangeSelected = 30;

    public function mount()
    {
        $this->user = Auth::user();
        //var_dump($this->keyedValues);
    }


    public function edit($id)
    {
        $this->editEntry = $id;
    }

    public function delete($id)
    {
        $entry = bodyMeasurement::find($id);
        $entry->delete();
    }


    public function render()
    {
        $this->user = Auth::user();
        $weekFinder = new TimeAndDateHelpers;
        $data = $weekFinder->getDaysFromToday($this->rangeSelected);

        //var_dump($data);
        $startDate = new DateTime($data['start'], $this->user->time_zone);
        $endDate = new DateTime($data['end'], $this->user->time_zone);

        $this->startDateSelected = date_format($startDate, 'Y-m-d');
        $this->endDateSelected = date_format($endDate, 'Y-m-d');

        $this->start = date_format($startDate, 'd M y');
        $this->end = date_format($endDate, 'd M y');

        $measurementData = new BodyMeasurementsData;
        $this->measurements = $measurementData->getMeasurementDataForRange($this->user->id, $data['start'], $data['end']);

        /*
         *
         */

        /*
         * $measurements = bodyMeasurement::where('user_id', auth()->user()->id)
            ->orderBy('date', 'ASC')
            ->get();
         */
        //$this->measurements = $measurements;

        $i = 0;
        foreach ($this->value as $val) {
            $this->map[$val] = $this->keys[$i];
            $i++;
        }
        $this->keyedValues = [];
        foreach ($this->measurements as $measurement) {
            foreach ($this->value as $value) {
                if (isset($measurement[$value])) {
                    //var_dump("$value : " . $measurement[$value]);
                    if (!isset($this->keyedValues[$value])) {
                        $this->keyedValues[$value] = [];
                    }
                    array_push($this->keyedValues[$value], $measurement);
                }
            }
        }
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.history');
    }
}
