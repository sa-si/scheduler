<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use Ramsey\Uuid\Type\Integer;

class CalendarYearController extends Controller
{
    public function index(Request $request, ?int $year = null, ?int $month = null, ?int $day = null){
        $carbon = CarbonImmutable::createSafe($year, $month, $day);
        $start_month = $carbon->month(1)->day(1);
        $last_month = $carbon->month(12)->day(31);
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
        $form_path = url(route('form'));
        $previous = $carbon->subYearNoOverflow()->format('Y/n/j');
        $next = $carbon->addYearNoOverflow()->format('Y/n/j');
        $header_date = $carbon->format('Y年');


        $request_path_split = explode("/", $request->path());
        $calendar_type = array_shift($request_path_split);
        $request_date_path = implode('/', $request_path_split);

        return view('calendar.day', compact('calendar', 'form_path', 'calendar_type', 'previous', 'next', 'header_date', 'request_date_path'));
    }
}
