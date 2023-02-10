<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class HeaderCalendarController extends Controller
{
    public function showPreviousMonth(Request $request) {
        $year = (int) $request->year;
        $month = (int) $request->month;
        $carbon =  CarbonImmutable::createSafe($year, $month);
        $previous = $carbon->subMonthNoOverflow()->format('Y-n');
        $calendar_view = CalendarView::renderHeaderCalendar($previous, $request->type);

        return [$calendar_view];
    }

    public function showNextMonth(Request $request) {
        $year = (int) $request->year;
        $month = (int) $request->month;
        $carbon =  CarbonImmutable::createSafe($year, $month);
        $next = $carbon->addMonthNoOverflow()->format('Y-n');
        $calendar_view = CalendarView::renderHeaderCalendar($next, $request->type);

        return [$calendar_view];
    }

    public function initialize(Request $request) {
        $calendar_view = CalendarView::renderHeaderCalendar($request->date, $request->type);

        return [$calendar_view];
    }
}
