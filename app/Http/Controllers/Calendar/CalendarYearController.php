<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class CalendarYearController extends Controller
{
    public function index(Request $request, $year, $month, $day){
        $date = $year . $month . $day;
        $carbon = new CarbonImmutable($date);
        $start_month = $carbon->year($year)->month(1)->day(1);
        $last_month = $carbon->year($year)->month(12)->day(31);
        $days = [];
        $i = 0;

        while ($start_month->lte($last_month)) {
            $start_day = $start_month->startOfMonth();
            $last_day = $start_month->endOfMonth();
            while ($start_day->lte($last_day)) {
                $days[$i][] = $start_day;
                $start_day = $start_day->addDay(1);
            }
            $i++;

            $start_month = $start_month->addMonth();
        }

        $calendar = new CalendarView($carbon, $days);

        return view('calendar.day', compact('calendar'));
    }
}
