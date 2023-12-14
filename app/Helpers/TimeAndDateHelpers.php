<?php


namespace App\Helpers;


use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class TimeAndDateHelpers
{
    public function getLastWeek()
    {
        $monday = strtotime("last monday");
        $monday = date('W', $monday) == date('W') ? $monday - 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        return (['start' => $this_week_sd, 'end' => $this_week_ed]);
    }

    public function getCurrentWeek()
    {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        return (['start' => $this_week_sd, 'end' => $this_week_ed]);

    }

    public function getOneMonthFromToday()
    {
        $today = new DateTime('now', new DateTimeZone(Auth::user()->timezone));
        $oneMonth = new DateTime('now', new DateTimeZone(Auth::user()->timezone));
        $oneMonth->sub(new DateInterval('P' . 30 . 'D'));

        $this_week_sd = date_format($oneMonth, 'Y-m-d');
        $this_week_ed = date_format($today, 'Y-m-d');

        return (['start' => $this_week_sd, 'end' => $this_week_ed]);

    }

    public function getDaysFromToday($days)
    {
        $today = new DateTime('now', new DateTimeZone(Auth::user()->timezone));
        $today->add(new DateInterval('P' . 1 . 'D'));

        $oneMonth = new DateTime('now', new DateTimeZone(Auth::user()->timezone));
        $oneMonth->sub(new DateInterval('P' . $days . 'D'));

        $this_week_sd = date_format($oneMonth, 'Y-m-d');
        $this_week_ed = date_format($today, 'Y-m-d');

        return (['start' => $this_week_sd, 'end' => $this_week_ed]);

    }

}
