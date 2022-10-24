<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class CalendarWeekController extends Controller
{
    public function index(Request $request, $year, $month, $day){
        $date = $year . $month . $day;
        $carbon = new CarbonImmutable($date);
        $start_day = $carbon->startOfWeek();
        $last_day = $carbon->endOfWeek();
        $days = [];

        while ($start_day->lte($last_day)) {
            $days[] = $start_day;
            $start_day = $start_day->addDay(1);
        }

        $calendar = new CalendarView($carbon, $days);
        $form_path = url(route('form'));

        return view('calendar.day', compact('calendar', 'form_path'));
    }
}
