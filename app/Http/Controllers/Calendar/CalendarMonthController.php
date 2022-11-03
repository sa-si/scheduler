<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class CalendarMonthController extends Controller
{
    public function index(Request $request, ?int $year = null, int $month = 1, int $day = 1){
        // $date = $year . $month . $day;
        // $carbon = new CarbonImmutable($date);
        $carbon = CarbonImmutable::createSafe($year, $month, $day);

        $start_day = $carbon->startOfMonth();
        $last_day = $carbon->endOfMonth();
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
