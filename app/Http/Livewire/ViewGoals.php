<?php

namespace App\Http\Livewire;

use App\Models\goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewGoals extends Component
{
    public $goals;
    public $user;
    public $goalSelected;
    public $goalId;
    public $currentGoal;
    public $tableData;



    public function mount()
    {
        $this->user = Auth::user();
        if (isset(request()->goalId)) {
            $this->goalId = request()->goalId;
        }
        $this->goals = goal::where('user_id', $this->user->id)->get();
        foreach ($this->goals as $key => $value) {
            if ($value['goal_id'] == $this->goalId) {
                $this->goalSelected = $key;
            }
        }
    }

    public function render()
    {
        $this->goals = goal::where('user_id', $this->user->id)->get();

        //var_dump(request()->goalId);
        if ($this->goalId) {
            $this->currentGoal = goal::where('user_id', $this->user->id)
                ->where('goal_id', $this->goalId)->first();
            //var_export($this->currentGoal);
            if ($this->currentGoal) {
                if ($this->currentGoal['table_primary_name'] != null && $this->currentGoal['table_primary_key'] != null && $this->currentGoal['table_primary_value'] != null) {
                    $this->tableData = DB::table($this->currentGoal->table_name)->whereBetween('date', [$this->currentGoal['start_date'],$this->currentGoal['date']] )->where('user_id', '=', $this->currentGoal['user_id'])->where($this->currentGoal['table_primary_key'], '=', $this->currentGoal['table_primary_target'])->orderBy('date', 'DESC')->get();
                } else {
                    $this->tableData = DB::table($this->currentGoal->table_name)->whereBetween('date', [$this->currentGoal['start_date'],$this->currentGoal['date']] )->where('user_id', '=', $this->currentGoal['user_id'])->orderBy('date', 'DESC')->get();
                }
            }
        }

        //var_dump($this->goals);
        //var_dump($this->tableData);
        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.view-goals');
    }
}
