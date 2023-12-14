<?php

namespace App\Http\Livewire;

use App\Helpers\DateFinder;
use App\Helpers\RepMax;
use App\Helpers\RepsSetsSlot;
use App\Models\goal;
use App\Models\set;
use App\Models\workout;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Log extends Component
{
    public $currentDate;
    public $dateStr;
    public $nextDay;
    public $prevDay;
    public $sets;
    public $setSlots = [];
    public $editSet = null;
    public $oneRepMaxDB;
    public $user;
    public $editMode = false;
    public $restDay = false;
    public $oneRepMaxSlots;

    public $toBeDeleted = null;
    public $confirmGoalDeletion = null;

    public function delete($id)
    {
        $this->toBeDeleted = $id;
        $this->confirmGoalDeletion = true;
    }

    public function deleteConfirmed()
    {
        $this->confirmGoalDeletion = false;
        $exercise = set::find($this->toBeDeleted);
        if ($exercise->user_id == $this->user->id) {
            session()->flash('deleteButtonMessage', 'DELETED - ID:' . $exercise->set_id);
            $exercise->delete();
        } else {
            session()->flash('deleteButtonMessage', 'FAILED PERMISSION DENIED.');
        }
    }

    public function toggleEditing()
    {
        $this->editMode = !$this->editMode;
    }

    public function mount()
    {
        $this->user = Auth::user();
        $date = request()->date;
        if ($date) {
            //var_dump("date");
            $this->currentDate = new DateTime($date, new DateTimeZone($this->user->timezone));
        } else {
            //var_dump("no date");
            // For some reason the date having a time makes the function below not work....
            $tmpDate = new DateTime('now', new DateTimeZone($this->user->timezone));
            $date = new DateTime($tmpDate->format('d-m-Y'), new DateTimeZone($this->user->timezone));
            $this->currentDate = $date;
        }
        $nextDay = new DateTime(request()->date, new DateTimeZone($this->user->timezone));
        $nextDay->add(new DateInterval('P1D'));

        $prevDay = new DateTime(request()->date, new DateTimeZone($this->user->timezone));
        $prevDay->sub(new DateInterval('P1D'));

        $this->nextDay = date_format($nextDay, 'd-m-Y');
        $this->prevDay = date_format($prevDay, 'd-m-Y');


    }

    public function render()
    {
        $getDB = new RepsSetsSlot();
        $data = $getDB->getWorkoutsForDay($this->currentDate);
        $this->oneRepMaxDB = $data['oneRepMaxDB'];
        $this->oneRepMaxSlots = $data['oneRepMaxSlots'];
        $this->setSlots = $data['setSlots'];

        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.log');
    }
}
