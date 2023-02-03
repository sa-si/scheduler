<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class CalendarMonthController extends Controller
{
    public function index(Request $request, ?int $year = null, ?int $month = null, ?int $day = null){
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
        $previous = $carbon->subMonthNoOverflow()->format('Y/n/j');
        $next = $carbon->addMonthNoOverflow()->format('Y/n/j');
        $header_date = $carbon->format('Y年n月');

        $request_path_split = explode("/", $request->path());
        $calendar_type = array_shift($request_path_split);
        $request_date_path = implode('/', $request_path_split);

        return view('calendar.day', compact('calendar', 'form_path', 'calendar_type', 'previous', 'next', 'header_date', 'request_date_path'));
    }
}
