<?php

namespace App\Http\Livewire;

use App\Helpers\WilksCoefficient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use mysql_xdevapi\Exception;

class Wilks extends Component
{
    public $user;
    public $sex;
    public $form = [];
    public $wilks = [];

    protected $rules = [
        'form.bodyWeight' => 'required|numeric|between:12,500',
        'form.liftedWeight' => 'required|numeric|between:1.00,10000.00',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->form = [
            "male" => "on",
            "female" => "off",
            "bodyWeight" => 80,
            "imperial" => "on",
            "liftedWeight" => 100,
            "answer" => 0.0
        ];
        $this->sex = "male";
        /*
         * Chart plotter

        if ($this->form['imperial'] == "on") {
            $unitType = "imperial";
        } else {
            $unitType = "metric";
        }
        if ($this->sex == "male") {
            $sex = "m";
        } else {
            $sex = "f";
        }
        $calculator = new WilksCoefficient;
        foreach (range(1, 250) as $number) {
            try {
                $data = $calculator->calculateWilksScore($sex, $this->form['bodyWeight'], $number, $unitType);
                array_push($this->wilks, $data);

            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
        */
    }

    public function save()
    {

    }

    public function calculate()
    {
        $this->validate();
        $calculator = new WilksCoefficient;
        if ($this->sex == "male") {
            $sex = "m";
        } else {
            $sex = "f";
        }
        if ($this->form['imperial'] == "on") {
            $unitType = "imperial";
        } else {
            $unitType = "metric";
        }

        try {
            $this->form['answer'] = number_format($calculator->calculateWilksScore($sex, $this->form['bodyWeight'], $this->form['liftedWeight'], $unitType), 2);
                $this->form['answer'] .= " points";
        } catch (Exception $e) {
            session()->flash('message', $e->getMessage());
        }
    }

    public function render()
    {
        if ($this->form['male'] == "on" && $this->form['female'] == "on") {
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
            return view('livewire.wilks');
        } else {
            activity()
                ->inLog("render")
                ->log(Request::ip() . " on " . request()->path());
            return view('livewire.wilks')
                ->layout('livewire.public.layouts.guest');
        }
    }
}
