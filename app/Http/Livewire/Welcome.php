<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        if (Auth::user()) {
            activity()
                ->inLog("render")
                ->causedBy(Auth::user())
                ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());
        } else {
            activity()
                ->inLog("render")
                ->log(Request::ip() . " on " . request()->path());
        }
        return view('livewire.public.welcome')
            ->layout('livewire.public.layouts.welcome');;
    }
}
