<?php

namespace App\Http\Livewire\Admin;

use App\Models\wizards;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Activity extends Component
{
    public function render()
    {
        $user = Auth::user();
        $wizard = wizards::where('user_id', $user->id)->latest('updated_at')->first();
        if (!isset($wizard)) {
            abort(501);
        } else {
            if (!$wizard->is_wizard) {
                abort(501);
            }
        }
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.admin.activity');
    }
}
