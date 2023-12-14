<?php


namespace App\Helpers;


use App\Models\exercise;
use Illuminate\Support\Facades\DB;

class RepMax
{
    public function calculateOneRepMax($weight, $reps)
    {
        /*
        $oneRepMax = (
            ($weight) /
            (1.0278 - (0.0278 * $reps))
        );
        */
        $oneRepMax = $weight * pow($reps, 0.10);

        return $oneRepMax;
    }

    public function oneRepMaxDB($user_id)
    {
        $recordsBySetId = [];
/*
        $oneRepMaxDbTable = DB::table('set')
            ->select([DB::raw('MAX(set.one_rep_max) AS one_rep_max'), 'set.exercise_id','set.set_id'])
            ->groupBy('set.set_id', 'set.exercise_id')
            ->get()->toArray();
*/
        $oneRepMaxDbTable = DB::table('set')
            ->select([DB::raw('MAX(set.one_rep_max) AS one_rep_max'), 'set.set_id', 'set.exercise_id', 'set.user_id'])
            ->groupBy( 'set.set_id')
            ->get();
        $oneRepMaxHashMap = [];
        foreach ($oneRepMaxDbTable as $item) {
            if ($item->user_id == $user_id) {
                if (!isset($oneRepMaxHashMap[$item->exercise_id])) {
                    $oneRepMaxHashMap[$item->exercise_id]['exercise_id'] = $item->exercise_id;
                    $oneRepMaxHashMap[$item->exercise_id]['one_rep_max'] = $item->one_rep_max;
                    $oneRepMaxHashMap[$item->exercise_id]['user_id'] = $item->user_id;
                    $oneRepMaxHashMap[$item->exercise_id]['set_id'] = $item->set_id;

                }

                if (isset($oneRepMaxHashMap[$item->exercise_id]['one_rep_max'])) {
                    if ($item->one_rep_max > $oneRepMaxHashMap[$item->exercise_id]['one_rep_max']) {
                        $oneRepMaxHashMap[$item->exercise_id]['exercise_id'] = $item->exercise_id;
                        $oneRepMaxHashMap[$item->exercise_id]['one_rep_max'] = $item->one_rep_max;
                        $oneRepMaxHashMap[$item->exercise_id]['user_id'] = $item->user_id;
                        $oneRepMaxHashMap[$item->exercise_id]['set_id'] = $item->set_id;
                    }
                }
            }
        }
       // var_dump($oneRepMaxHashMap[0]['one_rep_max']);
        return $oneRepMaxHashMap;
    }
}
