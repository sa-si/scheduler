<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class CalendarDayController extends Controller
{
    public function index(Request $request, $year, $month, $day){
        $date = $year . $month . $day;
        $carbon = new CarbonImmutable($date);
        $days = [$carbon];

        $calendar = new CalendarView($carbon, $days);

        return view('calendar.day', compact('calendar'));
    }
}
