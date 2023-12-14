<?php

namespace App\Http\Livewire;

use App\Helpers\BodyFatHelper;
use App\Models\bodyMeasurement;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SkinFoldTest extends Component
{
    public $sqlFields = [
        'chest_skinfold' => 'Chest (mm)',
        'abdominal_skinfold' => 'Abdominal (mm)',
        'thigh_skinfold' => 'Thigh (mm)',
        'suprailiac_skinfold' => 'Suprailiac (mm)',
        'axillary_skinfold' => 'Axillary (mm)',
        'lower_back_skinfold' => 'Lower Back (mm)',
        'tricep_skinfold' => 'Tricep (mm)',
        'subscapular_skinfold' => 'Subscapular (mm)',
        'calf_skinfold' => 'Calf (mm)',
        'bicep_skinfold' => 'Bicep (mm)',
        'body_weight' => 'Weight (kgs)',
        'age' => 'Age (years)',
        'parillo' => 'Parillo Test',
        'jp7' => 'Jackson Pollock 7 Site Test',
        'jp3' => 'Jackson Pollock 3 Site Test',
        'durnin' => 'Durnin Test'
    ];
    public $manJp3Keys = [
        'chest_skinfold',
        'abdominal_skinfold',
        'thigh_skinfold',
        'age'
    ];
    public $womanJp3Keys = [
        'tricep_skinfold',
        'suprailiac_skinfold',
        'thigh_skinfold',
        'age'
    ];
    public $jp7Keys = [
        'chest_skinfold',
        'abdominal_skinfold',
        'thigh_skinfold',
        'suprailiac_skinfold',
        'axillary_skinfold',
        'tricep_skinfold',
        'subscapular_skinfold',
        'age'
    ];

    public $parilloKeys = [
        'chest_skinfold',
        'abdominal_skinfold',
        'thigh_skinfold',
        'suprailiac_skinfold',
        'lower_back_skinfold',
        'tricep_skinfold',
        'subscapular_skinfold',
        'calf_skinfold',
        'bicep_skinfold',
        'body_weight'
    ];

    public $durninKeys = [
        'tricep_skinfold',
        'bicep_skinfold',
        'subscapular_skinfold',
        'suprailiac_skinfold',
        'age'
    ];

    public $form;
    public $sex;
    public $user = null;

    public $parilloArray;
    public $jp7Array;
    public $manJp3Array;
    public $womanJp3Array;
    public $durninArray;

    public $measurementId = null;
    public $editMode = false;

    public $measurementType = 'jp7';

    protected $manJP3Rules = [
        'form.age' => 'required|numeric|between:16,99',
        'form.chest_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.abdominal_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.thigh_skinfold' => 'required|numeric|between:0.00,60.00',
    ];
    protected $womanJP3Rules = [
        'form.age' => 'required|numeric|between:16,99',
        'form.thigh_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.suprailiac_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.tricep_skinfold' => 'required|numeric|between:0.00,60.00',
    ];
    protected $jp7Rules = [
        'form.age' => 'required|numeric|between:16,99',
        'form.axillary_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.chest_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.abdominal_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.thigh_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.suprailiac_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.tricep_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.subscapular_skinfold' => 'required|numeric|between:0.00,60.00',
    ];

    protected $durninRules = [
        'form.age' => 'required|numeric|between:16,99',
        'form.suprailiac_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.tricep_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.subscapular_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.bicep_skinfold' => 'required|numeric|between:0.00,60.00',
    ];

    protected $parilloRules = [
        'form.body_weight' => 'required|numeric|between:0.00,300.00',
        'form.chest_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.abdominal_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.thigh_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.suprailiac_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.lower_back_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.tricep_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.subscapular_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.calf_skinfold' => 'required|numeric|between:0.00,60.00',
        'form.bicep_skinfold' => 'required|numeric|between:0.00,60.00',
    ];

    protected $rules = [
        'form.date' => 'date|required',
    ];

    public function saveAll($saveKey, $keyArray)
    {
        if (!$this->user) {
            session()->flash($saveKey, 'FAILED! Not logged in.');
            return;
        }
        if ($this->editMode) {
            $newMeasurement = bodyMeasurement::find($this->measurementId);
            if (!$newMeasurement) {
                session()->flash($saveKey, 'FAILED! Can\'t find measurement in database.');
                return;
            }
            if ($newMeasurement['user_id'] != $this->user->id) {
                session()->flash($saveKey, 'FAILED! Permission denied.');
                return;
            }
        } else {
            $newMeasurement = new bodyMeasurement;
        }
        foreach ($keyArray as $key) {
            $newMeasurement[$key] = $this->form[$key];
        }
        $newMeasurement['date'] = $this->form['date'];
        $newMeasurement[$saveKey] = $this->form[$saveKey];
        $newMeasurement['user_id'] = $this->user->id;
        if ($this->sex == "male") {
            $newMeasurement['sex'] = true;
        } else {
            $newMeasurement['sex'] = false;
        }
        $newMeasurement->save();
        if (isset($newMeasurement->body_measurement_id)) {
            session()->flash($saveKey, 'SUCCESS! Measurement ID: ' . $newMeasurement['body_measurement_id']);
        } else {
            session()->flash($saveKey, 'FAILED!');
        }
    }
    public function calculateJP3()
    {
        if ($this->sex == "male") {
            $this->validate($this->manJP3Rules);
        } else {
            $this->validate($this->womanJP3Rules);
            $this->form['jp3'] = "woman";
        }

        if ($this->sex == "male") {
            $jp3calculator = new BodyFatHelper;
            $float = $jp3calculator->manJP3(
                $this->form['chest_skinfold'],
                $this->form['abdominal_skinfold'],
                $this->form['thigh_skinfold'],
                $this->form['age'],
            );
            $this->form['jp3'] = number_format($float, 2, '.');
        } elseif ($this->sex == "female") {
            $jp3calculator = new BodyFatHelper;
            $float = $jp3calculator->womanJP3(
                $this->form['tricep_skinfold'],
                $this->form['suprailiac_skinfold'],
                $this->form['thigh_skinfold'],
                $this->form['age'],
            );
            $this->form['jp3'] = number_format($float, 2, '.');
        } else {
            session()->flash('jp3', "error");
        }
    }

    public function saveJP3()
    {
        $this->calculateJP3();
        if ($this->sex == "male") {
            $this->saveAll('jp3', $this->manJp3Keys);
        } else {
            $this->saveAll('jp3', $this->womanJp3Keys);
        }
    }

    public function calculateJP7()
    {
        $this->validate($this->jp7Rules);
        if ($this->sex == "male") {
            $jp7calculator = new BodyFatHelper;
            $float = $jp7calculator->manJP7(
                $this->form['chest_skinfold'],
                $this->form['abdominal_skinfold'],
                $this->form['thigh_skinfold'],
                $this->form['suprailiac_skinfold'],
                $this->form['axillary_skinfold'],
                $this->form['tricep_skinfold'],
                $this->form['subscapular_skinfold'],
                $this->form['age'],
            );
            $this->form['jp7'] = number_format($float, 2, '.');
        } elseif ($this->sex == "female") {
            $jp7calculator = new BodyFatHelper;
            $float = $jp7calculator->womanJP7(
                $this->form['chest_skinfold'],
                $this->form['abdominal_skinfold'],
                $this->form['thigh_skinfold'],
                $this->form['suprailiac_skinfold'],
                $this->form['axillary_skinfold'],
                $this->form['tricep_skinfold'],
                $this->form['subscapular_skinfold'],
                $this->form['age'],
            );
            $this->form['jp7'] = number_format($float, 2, '.');
        }
    }

    public function saveJP7()
    {
        $this->calculateJP7();
        $this->saveAll('jp7', $this->jp7Keys);
    }

    public function calculateParillo()
    {
        $this->validate($this->parilloRules);
        $parilloCalculator = new BodyFatHelper;
        $float = $parilloCalculator->Parillo(
            $this->form['chest_skinfold'],
            $this->form['abdominal_skinfold'],
            $this->form['thigh_skinfold'],
            $this->form['suprailiac_skinfold'],
            $this->form['lower_back_skinfold'],
            $this->form['tricep_skinfold'],
            $this->form['subscapular_skinfold'],
            $this->form['calf_skinfold'],
            $this->form['bicep_skinfold'],
            $this->form['body_weight'],

        );
        $this->form['parillo'] = number_format($float, 2, '.');
    }

    public function saveParillo()
    {
        $this->calculateParillo();
        $this->saveAll('parillo', $this->parilloKeys);
    }

    public function calculateDurnin()
    {
        $this->validate($this->durninRules);
        if ($this->sex == "male") {
            $parilloCalculator = new BodyFatHelper;
            $float = $parilloCalculator->manDurnin(
                $this->form['tricep_skinfold'],
                $this->form['bicep_skinfold'],
                $this->form['subscapular_skinfold'],
                $this->form['suprailiac_skinfold'],
                $this->form['age'],
            );
            $this->form['durnin'] = number_format($float, 2, '.');
        } elseif ($this->sex == "female") {
            $parilloCalculator = new BodyFatHelper;
            $float = $parilloCalculator->femaleDurnin(
                $this->form['tricep_skinfold'],
                $this->form['bicep_skinfold'],
                $this->form['subscapular_skinfold'],
                $this->form['suprailiac_skinfold'],
                $this->form['age'],
            );
            $this->form['durnin'] = number_format($float, 2, '.');
        }
    }

    public function saveDurnin()
    {
        $this->calculateDurnin();
        $this->saveAll('durnin', $this->durninKeys);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        //$this->validate($this->rules);
    }


    public function mount()
    {
        for ($i = 0; $i < sizeof($this->manJp3Keys); $i++) {
            $this->manJp3Array[$this->sqlFields[$this->manJp3Keys[$i]]] = $this->manJp3Keys[$i];
        }
        for ($i = 0; $i < sizeof($this->womanJp3Keys); $i++) {
            $this->womanJp3Array[$this->sqlFields[$this->womanJp3Keys[$i]]] = $this->womanJp3Keys[$i];
        }
        for ($i = 0; $i < sizeof($this->jp7Keys); $i++) {
            $this->jp7Array[$this->sqlFields[$this->jp7Keys[$i]]] = $this->jp7Keys[$i];
        }
        for ($i = 0; $i < sizeof($this->parilloKeys); $i++) {
            $this->parilloArray[$this->sqlFields[$this->parilloKeys[$i]]] = $this->parilloKeys[$i];
        }
        for ($i = 0; $i < sizeof($this->durninKeys); $i++) {
            $this->durninArray[$this->sqlFields[$this->durninKeys[$i]]] = $this->durninKeys[$i];
        }
        foreach ($this->sqlFields as $key => $value) {
            $this->form[$key] = null;
        }
        $this->form['male'] = "on";
        $this->form['female'] = "off";

        $this->sex = "male";
        $this->user = Auth::user();
        if ($this->user) {
            $this->form['date'] = date_format((new DateTime(now(), $this->user->time_zone)), 'Y-m-d');;
        } else {
            $this->form['date'] = date_format((new DateTime(now())), 'Y-m-d');;
        }

        /*
         * route parameters
         */
        $measurementId = request()->id;

        if ($measurementId) {
            $measurement = bodyMeasurement::find($measurementId);
            if ($measurement) {
                if ($measurement['user_id'] == $this->user->id) {
                    $this->measurementId = $measurementId;
                    $this->editMode = true;

                    foreach ($this->sqlFields as $key => $ignored) {
                        if (isset($measurement[$key])) {
                            $this->form[$key] = $measurement[$key];
                        }
                    }
                    foreach (['jp3', 'jp7', 'parillo', 'durnin'] as $key) {
                        if (isset($measurement[$key])) {
                            $this->measurementType = $key;
                        }
                    }
                    if (isset($measurement['sex'])) {
                        if ($measurement['sex'] == 1) {
                            $this->sex = 'male';
                            $this->form['male'] = 'on';
                            $this->form['female'] = 'off';
                        } else {
                            $this->sex = 'female';
                            $this->form['male'] = 'off';
                            $this->form['female'] = 'on';
                        }
                    }
                    $this->form['date'] = date_format((new DateTime($measurement['date'], $this->user->time_zone)), 'Y-m-d');
                }
            }

        }
    }

    public function render()
    {
        if ($this->form['male'] == "on" && $this->form['female'] == "on") {
            foreach ($this->form as $key =>  $value) {
                if ($key !== "male" && $key !== "female" && $key !== "date" && $key !== "jp3") {
                    $this->form[$key] = null;
                }
            }
            if ($this->sex == "male") {
                $this->form['male'] = "off";
                $this->form['female'] = "on";
                $this->sex = "female";
            } else {
                $this->form['male'] = "on";
                $this->form['female'] = "off";
                $this->sex = "male";
            }
        }
        if ($this->user) {
            activity()
                ->inLog("render")
                ->causedBy(Auth::user())
                ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

            return view('livewire.skin-fold-test');
        } else {
            activity()
                ->inLog("render")
                ->log(Request::ip() . " on " . request()->path());
            return view('livewire.skin-fold-test')
                ->layout('livewire.public.layouts.guest');
        }
    }
}
