<?php

namespace App\Http\Livewire;

use App\Models\active_plan;
use App\Models\bodyMeasurement;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Track extends Component
{
    public $values;
    public $basic;
    public $advanced;
    public $measurement;
    public $keys;
    public $value;
    public $form;

    public $editMode;
    public $measurementId;

    protected $rules = [
        'form.date' => 'date|required',
        'form.body_height' => 'nullable|numeric|between:0.00,400.40',
        'form.body_weight' => 'nullable|numeric|between:0,400.40',
        'form.body_fat_percentage' => 'nullable|numeric|between:0,400.40',
        'form.visceral_fat_level' => 'nullable|numeric|between:0,400.40',
        'form.body_fat_mass' => 'nullable|numeric|between:0,400.40',
        'form.bmi' => 'nullable|numeric|between:0,400.40',
        'form.bmr' => 'nullable|numeric|between:0,8000.40',
        'form.neck_circumference' => 'nullable|numeric|between:0,400.40',
        'form.chest_circumference' => 'nullable|numeric|between:0,400.40',
        'form.abdomen_circumference' => 'nullable|numeric|between:0,400.40',
        'form.hip_circumference' => 'nullable|numeric|between:0,400.40',
        'form.left_bicep_circumference' => 'nullable|numeric|between:0,400.40',
        'form.right_bicep_circumference' => 'nullable|numeric|between:0,400.40',
        'form.left_thigh_circumference' => 'nullable|numeric|between:0,400.40',
        'form.right_thigh_circumference' => 'nullable|numeric|between:0,400.40',
        'form.left_calf_circumference' => 'nullable|numeric|between:0,400.40',
        'form.right_calf_circumference' => 'nullable|numeric|between:0,400.40',
    ];

    public function mount()
    {
        $keys = ['Height', 'Weight',
            'Fat Percentage', 'Visceral Fat Level', 'Fat Mass',
            'BMI', 'Basal Metabolic Rate', 'Neck Circumference',
            'Chest Circumference', 'Abdomen Circumference',
            'Hip Circumference', 'Left Bicep Circumference', 'Right Bicep Circumference',
            'Left Thigh Circumference', 'Right Thigh Circumference', 'Left Calf Circumference',
            'Right Calf Circumference'];

        $value = ['body_height', 'body_weight',
            'body_fat_percentage', 'visceral_fat_level', 'body_fat_mass',
            'bmi', 'bmr', 'neck_circumference',
            'chest_circumference', 'abdomen_circumference',
            'hip_circumference', 'left_bicep_circumference', 'right_bicep_circumference',
            'left_thigh_circumference', 'right_thigh_circumference', 'left_calf_circumference',
            'right_calf_circumference'];

        $measurementKeys = ['Neck Circumference',
            'Chest Circumference', 'Abdomen Circumference',
            'Hip Circumference', 'Left Bicep Circumference', 'Right Bicep Circumference',
            'Left Thigh Circumference', 'Right Thigh Circumference', 'Left Calf Circumference',
            'Right Calf Circumference'];
        $measurementValues = ['neck_circumference',
            'chest_circumference', 'abdomen_circumference',
            'hip_circumference', 'left_bicep_circumference', 'right_bicep_circumference',
            'left_thigh_circumference', 'right_thigh_circumference', 'left_calf_circumference',
            'right_calf_circumference'];

        $basicMeasurementKeys = ['Height', 'Weight',];
        $basicMeasurementValues = ['body_height', 'body_weight',];

        $advancedMeasurementKeys = ['Fat Percentage', 'Visceral Fat Level', 'Fat Mass',
            'BMI', 'Basal Metabolic Rate'];
        $advancedMeasurementValues = ['body_fat_percentage', 'visceral_fat_level', 'body_fat_mass',
            'bmi', 'bmr',];


        for ($i = 0; $i < sizeof($keys); $i++) {
            $this->form[$value[$i]] = null;
        }

        for ($i = 0; $i < sizeof($measurementKeys); $i++) {
            $this->measurement[$measurementKeys[$i]] = $measurementValues[$i];
        }
        for ($i = 0; $i < sizeof($basicMeasurementKeys); $i++) {
            $this->basic[$basicMeasurementKeys[$i]] = $basicMeasurementValues[$i];
        }
        for ($i = 0; $i < sizeof($advancedMeasurementKeys); $i++) {
            $this->advanced[$advancedMeasurementKeys[$i]] = $advancedMeasurementValues[$i];
        }

        $this->keys = $keys;
        $this->value = $value;
        $measurementId = request()->id;

        if ($measurementId) {
            $this->measurementId = $measurementId;
            $this->editMode = true;
        }
    }

    public function submit()
    {
        session()->flash('message', '');

        $this->validate();
        $current_active_plan = active_plan::where('user', auth()->user()->id)->latest('updated_at')->first();
        $plan = 0;
        if ($current_active_plan) {
            $plan = $current_active_plan->plan;
        }
        if ($this->editMode) {
            $new = bodyMeasurement::find($this->measurementId);
            $new->user_id = Auth::user()->id;
            $new->plan_id = $plan;
            for ($i = 0; $i < sizeof($this->value); $i++) {
                $new[$this->value[$i]] = $this->form[$this->value[$i]];
            }
            $new->date = $this->form['date'];
            $new->save();
            session()->flash('message', 'Updated.');
        }else {
            $new = new bodyMeasurement;
            $new->user_id = Auth::user()->id;
            $new->plan_id = $plan;
            for ($i = 0; $i < sizeof($this->value); $i++) {
                $new[$this->value[$i]] = $this->form[$this->value[$i]];
            }
            $new->date = $this->form['date'];
            $new->save();
            session()->flash('message', 'Saved.');
            for ($i = 0; $i < sizeof($this->value); $i++) {
                $this->form[$this->value[$i]] = null;
            }
        }

    }

    public function render()
    {
        $measurementId = request()->id;
        if ($measurementId) {
            $this->measurementId = $measurementId;
            $this->editMode = true;
        }
        if ($this->editMode) {
            $thisMeasurement = bodyMeasurement::where('user_id', auth()->user()->id)->where('body_measurement_id', $this->measurementId)->first();

            if ($thisMeasurement) {
                for ($i = 0; $i < sizeof($this->value); $i++) {
                    $this->form[$this->value[$i]] = $thisMeasurement[$this->value[$i]];
                }
                $newDate = date_format(new DateTime($thisMeasurement['date'], new DateTimeZone(Auth::user()->timezone)), 'Y-m-d');
                $this->form['date'] = $newDate;
            }

        } else {
            $newDate = date_format(new DateTime('now', new DateTimeZone(Auth::user()->timezone)), 'Y-m-d');
            $this->form['date'] = $newDate;
        }
        //var_dump($this->form);


        /* Mock measurement
        $new = new bodyMeasurement;
        $new->user_id = Auth::user()->id;
        $new->plan_id = 0;
        for ($i =0; $i < sizeof($this->value); $i++) {
            var_dump($this->form[$this->value[$i]]);
            $new[$this->value[$i]] = 0;
        }
        $new->date = new DateTime('now');
        $new->save();
        */
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.track');
    }
}
