<?php

namespace App\Http\Livewire;

use App\Models\goal;
use App\Models\GoalObject;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateGoals extends Component
{
    public $goalName;
    public $goalDescription;
    public $goalDate;
    public $goalDateStart;
    public $goalValue;
    public $goalType;
    public $goalSubType;

    public $goalId;
    public $goalBeingEdited;

    public $confirmGoalDeletion = false;

    public $goalList = [];
    public $skinFold = [
        'Chest (mm)' => 'chest_skinfold',
        'Abdominal (mm)' => 'abdominal_skinfold',
        'Thigh (mm)' => 'thigh_skinfold',
        'Suprailiac (mm)' => 'suprailiac_skinfold',
        'Axillary (mm)' => 'axillary_skinfold',
        'Lower Back (mm)' => 'lower_back_skinfold',
        'Tricep (mm)' => 'tricep_skinfold',
        'Subscapular (mm)' => 'subscapular_skinfold',
        'Calf (mm)' => 'calf_skinfold',
        'Bicep (mm)' => 'bicep_skinfold',
        'Weight (kgs)' => 'body_weight',
        'Age (years)' => 'age',
        'Parillo Test' => 'parillo',
        'Jackson Pollock 7 Site Test' => 'jp7',
        'Durnin Test' => 'durnin'
    ];
    public $measurement = [
        'Height' => 'body_height',
        'Weight' => 'body_weight',
        'Visceral Fat Level' => 'visceral_fat_level',
        'Fat Mass' => 'body_fat_mass',
        'BMI' => 'bmi',
        'Basal Metabolic Rate' => 'bmr',
        'Neck Circumference' => 'neck_circumference',
        'Chest Circumference' => 'chest_circumference',
        'Abdomen Circumference' => 'abdomen_circumference',
        'Hip Circumference' => 'hip_circumference',
        'Left Bicep Circumference' => 'left_bicep_circumference',
        'Right Bicep Circumference' => 'right_bicep_circumference',
        'Left Thigh Circumference' => 'left_thigh_circumference',
        'Right Thigh Circumference' => 'right_thigh_circumference',
        'Left Calf Circumference' => 'left_calf_circumference',
        'Right Calf Circumference' => 'right_calf_circumference'
    ];

    public $bodyFat = [
        'Fat Percentage' => 'body_fat_percentage',
        'Jackson Pollock 7 Site' => 'jp7',
        'Jackson Pollock 3 Site' => 'jp3',
        'Parillo' => 'parillo',
        'Durnin' => 'durnin'
    ];

    public $set = [
        'One Rep Max' => 'one_rep_max',
        'Repetitions' => 'reps',
        'Weight' => 'weight'
    ];

    public $accessArray;
    public $goalArray = [];
    public $primaryTable = false;
    public $primaryTableData = [];
    public $user;

    protected $rules = [
        'goalName' => 'required|max:25',
        'goalDescription' => 'required|max:50',
        'goalDate' => 'required|date',
        'goalDateStart' => 'required|date',
        'goalValue' => 'required|numeric|between:0.00,1000.00',
        'goalType' => 'required',
    ];

    public function submit()
    {

    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->goalId = request()->goalId;
        /* load our goal to be edited once */
        if (isset($this->goalId)) {
            $this->goalBeingEdited = goal::find($this->goalId);
            if (isset($this->goalBeingEdited)) {
                if ($this->goalBeingEdited->user_id == $this->user->id) {
                    $this->goalDate = $this->goalBeingEdited['date'];
                    $this->goalDateStart = $this->goalBeingEdited['start_date'];
                    $this->goalName = $this->goalBeingEdited['goal_name'];
                    $this->goalDescription = $this->goalBeingEdited['goal_description'];
                    $this->goalValue = $this->goalBeingEdited['goal_value'];
                    //$this->goalType = $this->goalBeingEdited['goal_type_name'];
                    $this->goalDateStart = date_format(new DateTime($this->goalBeingEdited->start_date), 'Y-m-d');
                    $this->goalDate = date_format(new DateTime($this->goalBeingEdited->date), 'Y-m-d');
                } else {
                    $this->goalBeingEdited = null;
                }
            }
        }
        //$this->ran = true;

    }
    public function deleteGoal()
    {
        $this->confirmGoalDeletion = true;
    }

    public function deleteConfirmed()
    {
        $this->confirmGoalDeletion = false;
        $goal = goal::find($this->goalId);
        if ($goal->user_id == $this->user->id) {
            session()->flash('message', 'DELETED');
            $goal->delete();
            $this->goalBeingEdited = null;
        } else {
            session()->flash('message', 'FAILED PERMISSION DENIED.');
        }
        // do something
    }
    public function saveGoal()
    {
        $this->validate($this->rules);

        if (!isset($this->goalList[$this->accessArray[0]][$this->accessArray[1]])) {
            session()->flash('message', 'FAILED!');
            return;
        }
        $currentGoal = $this->goalList[$this->accessArray[0]][$this->accessArray[1]];
        //var_export($currentGoal);
        if (isset($this->goalId)) {
            $goal = goal::find($this->goalId);
            if (isset($goal)) {
                if ($goal->user_id != $this->user->id) {
                    session()->flash('message', 'Failed permission denied.');
                    return;
                }
            } else {
                session()->flash('message', 'Failed goal not found.');
                return;
            }

        } else {
            $goal = new goal;
        }
        $goal['user_id'] = $this->user->id;
        $goal['goal_name'] = $this->goalName;
        $goal['goal_description'] = "foo";
        $goal['date'] = $this->goalDate;
        $goal['start_date'] = $this->goalDateStart;
        $goal['goal_value'] = $this->goalValue;

        foreach ($currentGoal as $key => $value) {
            $goal[$key] = $value;
        }
        $goal->save();
        if (isset($goal->goal_id)) {
            session()->flash('message', 'Saved as ID: ' . $goal->goal_id);
        } else {
            session()->flash('message', 'Failed to save to database.');
        }
    }

    public function render()
    {
        /*
        * Create our list of goals.
       */
        $this->goalList = [];
        $this->goalArray['Strength and Reps'] = ['set', 'exercise', 'exercise_id', 'exercise_name', $this->set];
        $this->goalArray['Body Fat Percentage'] = ['body_measurement', null, null, null, $this->bodyFat];
        $this->goalArray['Body'] = ['body_measurement', null, null, null, $this->measurement];
        $this->goalArray['Skin Fold'] = ['body_measurement', null, null, null, $this->skinFold];

        foreach ($this->goalArray as $goal_category_name => $goalData) {
            foreach ($goalData[4] as $key => $value) {
                $item = new GoalObject();
                $item->goal_type_name = $key;

                $item->table_key = $value;
                $item->table_name = $goalData[0];

                $item->table_primary_name = $goalData[1];
                $item->table_primary_key = $goalData[2];
                $item->table_primary_value = $goalData[3];
                $item->table_primary_target = $this->goalSubType;

                if (!isset($this->goalList[$goal_category_name])) {
                    $this->goalList[$goal_category_name] = [];
                }
                array_push($this->goalList[$goal_category_name], $item);
            }
        }
        $array = explode(":", $this->goalType);
        $this->accessArray = $array;
        if (isset($this->goalType)) {
            //var_export();
            if (isset($this->goalList[$array[0]][$array[1]]->table_primary_name)) {
                if ($this->goalList[$array[0]][$array[1]]->table_primary_name) {
                    $this->primaryTableData = DB::table($this->goalList[$array[0]][$array[1]]->table_primary_name)->get()->toArray();
                }
                $this->primaryTable = true;
            } else {
                $this->primaryTable = false;
            }
        } else {
            $this->primaryTable = false;
        }

        activity()
            ->inLog("render")
            ->causedBy(Auth::user())
            ->log(Auth::user()->name . " (" . Auth::user()->id . ")" . " on " . request()->path());

        return view('livewire.create-goals');
    }
}
