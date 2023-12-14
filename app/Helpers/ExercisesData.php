<?php


namespace App\Helpers;


use App\Models\set;
use App\Models\User;
use DateInterval;
use DateTime;

class ExercisesData
{
    public function getExerciseDataForRange($userId, $start, $end)
    {
        error_log("start : $start" . " end: $end");
        $user = user::find($userId);
        $exerciseCount = set::whereBetween('date', [$start, $end])
            ->where('user_id', $userId)->count();

        $exercises = set::whereBetween('date', [$start, $end])
            ->where('user_id', $userId)
            ->join('exercise', 'set.exercise_id', '=', 'exercise.exercise_id')
            ->join('muscles', 'muscles.muscle_id', '=', 'exercise.muscle_id')
            ->select('set.*', 'exercise.exercise_name', 'muscles.muscle_name', 'muscles.muscle_id')
            ->get();

        $exerciseStats = [];
        $muscleStats = [];
        $volume = []; // muscle volume
        $exercisesMuscles = [];
        $dailyVolume = [];
        $totalReps = 0;
        $totalVolume = 0;
        $dailyMuscleVolume = [];

        $startDate = new DateTime($start, $user->time_zone);
        $endDate = new DateTime($end, $user->time_zone);
        $numberOfDays = $startDate->diff($endDate)->d;

        for ($i = 0; $i < $numberOfDays; $i++) {
            $newDate = new DateTime($start, $user->time_zone);
            $newDate->add(new DateInterval('P' . $i . 'D'));
            $dateString = date_format($newDate, 'd M y');
            if (!isset($dailyVolume[$dateString])) {
                $dailyVolume[$dateString] = 0;
            }
            foreach ($exercises as $key => $value) {
                if (!isset($dailyMuscleVolume[$value['muscle_name']][$dateString])) {
                    $dailyMuscleVolume[$value['muscle_name']][$dateString] = 0;
                }
            }
        }

        foreach ($exercises as $key => $value) {
            // Make sure its not empty
            if ($value['reps'] && $value['weight']) {
                if (!isset($exerciseStats[$value['muscle_id']])) {
                    $exerciseStats[$value['muscle_id']]['totalVolume'] = 0;
                    $exerciseStats[$value['muscle_id']]['volume'] = [];
                    $exerciseStats[$value['muscle_id']]['setCount'] = 0;
                    $exerciseStats[$value['muscle_id']]['reps'] = 0;
                    $exerciseStats[$value['muscle_id']]['one_rep_max'] = [];
                    $exerciseStats[$value['muscle_id']]['exercises'] = [];
                    $exerciseStats[$value['muscle_id']]['exercise'] = [];
                    $exerciseStats[$value['muscle_id']]['muscle_name'] = $value['muscle_name'];

                    //var_dump("adding muscle_id: " . $value['muscle_id']);
                }
                if (!isset($muscleStats[$value['muscle_id']])) {
                    $muscleStats[$value['muscle_id']] = [];
                    $muscleStats[$value['muscle_id']]['totalVolume'] = 0;
                    $muscleStats[$value['muscle_id']]['volume'] = [];
                    $muscleStats[$value['muscle_id']]['setCount'] = 0;
                    $muscleStats[$value['muscle_id']]['reps'] = 0;
                    $muscleStats[$value['muscle_id']]['one_rep_max'] = [];
                    $muscleStats[$value['muscle_id']]['exercises'] = [];
                }
                if (!isset($volume[$value['muscle_name']])) {
                    $volume[$value['muscle_name']] = 0;
                }
                if (!isset($dailyMuscleVolume[$value['muscle_name']])) {
                    $dailyMuscleVolume[$value['muscle_name']] = [];
                }


                /*
                 * Exercise stats by exercise_id
                 */
                $exerciseStats[$value['muscle_id']]['totalVolume'] += $value['weight'] * $value['reps'];
                array_push($exerciseStats[$value['muscle_id']]['volume'], $value['weight'] * $value['reps']);
                $exerciseStats[$value['muscle_id']]['setCount'] += 1;
                $exerciseStats[$value['muscle_id']]['reps'] += $value['reps'];
                array_push($exerciseStats[$value['muscle_id']]['one_rep_max'], $value['one_rep_max']);
                array_push($exerciseStats[$value['muscle_id']]['exercises'], $key);


                /*
                 * Muscle -> exercise_name -> data
                 */
                if (!isset($exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']])) {
                    $exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']]['setCount'] = 0;
                    $exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']]['reps'] = 0;
                    $exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']]['volume'] = 0;
                    $exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']]['exercise_id'] = $value['exercise_id'];
                }
                $exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']]['setCount']++;
                $exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']]['reps'] += $value['reps'];
                $exerciseStats[$value['muscle_id']]['exercise'][$value['exercise_name']]['volume'] += $value['reps'] * $value['weight'];

                /*
                 * Exercise stats by muscle_id
                 */
                $muscleStats[$value['muscle_id']]['totalVolume'] += $value['weight'] * $value['reps'];
                array_push($muscleStats[$value['muscle_id']]['volume'], $value['weight'] * $value['reps']);
                $muscleStats[$value['muscle_id']]['setCount'] += 1;
                $muscleStats[$value['muscle_id']]['reps'] += $value['reps'];
                array_push($muscleStats[$value['muscle_id']]['one_rep_max'], $value['one_rep_max']);
                array_push($muscleStats[$value['muscle_id']]['exercises'], $key);

                /*
                 * Volume
                 */
                $volume[$value['muscle_name']] += $value['weight'] * $value['reps'];
                $totalReps += $value['reps'];
                $totalVolume += $value['weight'] * $value['reps'];
                array_push($exercisesMuscles, $value['muscle_name']);

                /*
                 * Daily Volume
                 */
                $dateString = date_format(new DateTime($value['date'], $user->time_zone), 'd M y');
                if (!isset($dailyVolume[$dateString])) {
                    $dailyVolume[$dateString] = ($value['reps'] * $value['weight']);
                } else {
                    $dailyVolume[$dateString] += ($value['reps'] * $value['weight']);
                }

                /*
                 * Daily Muscle Volume
                 */
                if (!isset($dailyMuscleVolume[$value['muscle_name']][$dateString])) {
                    $dailyMuscleVolume[$value['muscle_name']][$dateString] = 0;
                } else {
                    $dailyMuscleVolume[$value['muscle_name']][$dateString] += ($value['reps'] * $value['weight']);
                }
            }
        }


        return ['exerciseCount' => $exerciseCount,
            'exercises' => $exercises,
            'volume' => $volume,
            'totalReps' => $totalReps,
            'totalVolume' => $totalVolume,
            'exercisesMuscles' => $exercisesMuscles,
            'exerciseStats' => $exerciseStats,
            'dailyVolume' => $dailyVolume,
            'dailyMuscleVolume' => $dailyMuscleVolume
        ];
    }
}
