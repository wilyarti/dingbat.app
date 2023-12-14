<?php


namespace App\Helpers;


use App\Models\bodyMeasurement;
use App\Models\set;
use App\Models\User;

class BodyMeasurementsData
{
    public function getMeasurementDataForRange($userId, $start, $end)
    {
        error_log("start : $start" . " end: $end");
        $user = user::find($userId);
        $measurementCount = bodyMeasurement::whereBetween('date', [$start, $end])
            ->where('user_id', $userId)->count();

        $measurements = bodyMeasurement::whereBetween('date', [$start, $end])
            ->where('user_id', $userId)
            ->orderBy('date', 'DESC')
            //->select('*')
            ->get();

        return ($measurements);
    }
}
