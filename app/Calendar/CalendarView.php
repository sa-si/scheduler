<?php
namespace App\Calendar;

use Carbon\CarbonImmutable;
use App\Models\PlanningTask;

class CalendarView {

    public $carbon;
    public $days;

    const FIRST_HOUR = 0;
    const FINAL_HOUR = 23;
    // const AFTER_15_MIN_NUM = 15;
    // const AFTER_30_MIN_NUM = 30;
    // const AFTER_45_MIN_NUM = 45;
    // const TIMES = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
    const MINUTES = ['min_0' => '00', 'min_15' => '15', 'min_30' => '30', 'min_45' => '45'];

    public function __construct(CarbonImmutable $carbon, array $days = [])
    {
        $this->carbon = $carbon;
        $this->days = $days;
    }

    public function render() {
        $html = [];
        // 日・週カレンダービュー
        if (count($this->days) === 1 || count($this->days) === 7) {
            // head要素を取得
            $html[] = '<table class="table-day">';
            $html[] = '<thead class="thead-day-sticky-top">';
            $html[] = '<tr class="row-height-day text-center">';
            $html[] = '<td class="calendar-time">hoge</td>';
            // 日数分($this->days)の曜日と日付を取得
            // *改善点? 曜日と日にちの取得をそれぞれ関数化するのはどう？
            foreach ($this->days as $day) {
                $html[] = '<th class="p-2"><span>' . $day->locale('ja')->shortDayName . '</span><p class="date mb-0" data-date="' . $day->format('Y-m-d') . '">' . $this->getDate($day) . '</p></th>';
            }
            $html[] = '</tr>';
            $html[] = '</thead>';
            $html[] = '<tbody>';
            // 3重配列で取得(年月日、時間)
            $tasks = PlanningTask::getTasks($this->days);
            // $first_time = $this->carbon->hour(0)->minute(0)->second(0);
            // $last_time = $first_time->addHours(self::LAST_TIME_NUM);
            // 00:00~23:45までの行を取得
            // while ($first_time->lte($last_time)) {
            //     $html[] = '<tr>';
            //     $start_time_obj = $first_time;
            //     $start_time = $start_time_obj->format('H:i');
            //     $html[] = '<td>' . $start_time . '</td>';
            //     // 同時間の7日分を取得
            //     // *ここも関数化できそう
            //     foreach ($this->days as $day) {
            //         $day = $day->format('Y-m-d');

            //         $html[] = "<td>";

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $start_time .'">';
            //         if (isset($tasks[$day][$start_time])) {
            //             $html[] = '<p>' . $tasks[$day][$start_time]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $after_15_min_obj = $start_time_obj->addMinutes(self::AFTER_15_MIN_NUM);
            //         $after_15_min = $after_15_min_obj->format('H:i');

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $after_15_min .'">';
            //         if (isset($tasks[$day][$after_15_min])) {
            //             $html[] =  '<p>' . $tasks[$day][$after_15_min]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $after_30_min_obj = $start_time_obj->addMinutes(self::AFTER_30_MIN_NUM);
            //         $after_30_min = $after_30_min_obj->format('H:i');

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $after_30_min .'">';
            //         if (isset($tasks[$day][$after_30_min])) {
            //             $html[] =  '<p>' . $tasks[$day][$after_30_min]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $after_45_min_obj = $start_time_obj->addMinutes(self::AFTER_45_MIN_NUM);
            //         $after_45_min = $after_45_min_obj->format('H:i');

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $after_45_min .'">';
            //         if (isset($tasks[$day][$after_45_min])) {
            //             $html[] =  '<p>' . $tasks[$day][$after_45_min]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $html[] = '</div>';
            //     }

            //     $html[] = '</tr>';
            //     $first_time = $first_time->addHour();
            // }
            // foreach (self::TIMES as $time) {
            //     $time_0 = $time . self::MINUTES['min_0'];
            //     $time_15 = $time . self::MINUTES['min_15'];
            //     $time_30 = $time . self::MINUTES['min_30'];
            //     $time_45 = $time . self::MINUTES['min_45'];
            //     $html[] = '<tr>';
            //     $html[] = '<td>' . $time_0 . '</td>';
            //     // dd($time_0);
            //     // 同時間の7日分を取得
            //     // *ここも関数化できそう
            //     foreach ($this->days as $day) {
            //         $day = $day->format('Y-m-d');

            //         $html[] = "<td>";

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_0 .'">';
            //         if (isset($tasks[$day][$time_0])) {
            //             $html[] = '<p>' . $tasks[$day][$time_0]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_15 .'">';
            //         if (isset($tasks[$day][$time_15])) {
            //             $html[] =  '<p>' . $tasks[$day][$time_15]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_30 .'">';
            //         if (isset($tasks[$day][$time_30])) {
            //             $html[] =  '<p>' . $tasks[$day][$time_30]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_45 .'">';
            //         if (isset($tasks[$day][$time_45])) {
            //             $html[] =  '<p>' . $tasks[$day][$time_45]['name'] . '</p>';
            //         }
            //         $html[] = "</div>";

            //         $html[] = '</div>';
            //     }

            //     $html[] = '</tr>';
            // }

            for ($i = self::FIRST_HOUR; $i <= self::FINAL_HOUR; $i++) {
                $time_0 = sprintf('%02d', $i) . ':' . self::MINUTES['min_0'];
                $time_15 = sprintf('%02d', $i) . ':' .  self::MINUTES['min_15'];
                $time_30 = sprintf('%02d', $i) . ':' . self::MINUTES['min_30'];
                $time_45 = sprintf('%02d', $i) . ':' . self::MINUTES['min_45'];

                $html[] = '<tr class="row-height-day text-center">';
                $html[] = '<td>' . $time_0 . '</td>';
                foreach ($this->days as $day) {
                    $day = $day->format('Y-m-d');

                    $html[] = "<td>";

                    $html[] = '<div class="js_form d-flex align-items-center" data-date="' . $day . '" data-time="' . $time_0 .'">';
                    if (isset($tasks[$day][$time_0])) {
                        $html[] = '<p class="task badge w-100 mb-0 text-start text-truncate align-middle me-2 py-2">' . $tasks[$day][$time_0]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $html[] = '</td>';
                }
                $html[] = '</tr>';

                $html[] = '<tr class="row-height-day text-center">';
                $html[] = '<td>' . $time_15 . '</td>';
                foreach ($this->days as $day) {
                    $day = $day->format('Y-m-d');

                    $html[] = "<td>";

                    $html[] = '<div class="js_form d-flex align-items-center" data-date="' . $day . '" data-time="' . $time_15 .'">';
                    if (isset($tasks[$day][$time_15])) {
                        $html[] =  '<p class="task badge w-100 mb-0 text-start text-truncate align-middle me-2 py-2">' . $tasks[$day][$time_15]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $html[] = '</td>';
                }
                $html[] = '</tr>';

                $html[] = '<tr class="row-height-day text-center">';
                $html[] = '<td>' . $time_30 . '</td>';
                foreach ($this->days as $day) {
                    $day = $day->format('Y-m-d');

                    $html[] = "<td>";

                    $html[] = '<div class="js_form d-flex align-items-center" data-date="' . $day . '" data-time="' . $time_30 .'">';
                    if (isset($tasks[$day][$time_30])) {
                        $html[] =  '<p class="task badge w-100 mb-0 text-start text-truncate align-middle me-2 py-2">' . $tasks[$day][$time_30]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $html[] = '</td>';
                }
                $html[] = '</tr>';

                $html[] = '<tr class="row-height-day text-center">';
                $html[] = '<td>' . $time_45 . '</td>';
                foreach ($this->days as $day) {
                    $day = $day->format('Y-m-d');

                    $html[] = "<td>";

                    $html[] = '<div class="js_form d-flex align-items-center" data-date="' . $day . '" data-time="' . $time_45 .'">';
                    if (isset($tasks[$day][$time_45])) {
                        $html[] =  '<p class="task badge w-100 mb-0 text-start text-truncate align-middle me-2 py-2">' . $tasks[$day][$time_45]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $html[] = '</td>';
                }
                $html[] = '</tr>';

            }
            $html[] = '</tbody>';
            $html[] = '</table>';
        } elseif (count($this->days) === 12) {
            // 年カレンダービュー
            $year = $this->carbon->year;
            $task_dates = PlanningTask::getDatesOfTasksForOneYear($year);
            $html[] = '<div class="year-calendar-container mt-3">';
            // 一ヶ月ごとに処理
            foreach ($this->days as $days) {
                $html[] = '<div class="year-calendar-item d-flex justify-content-center ' . $days[0]->year . "-" . $days[0]->month . '">';
                $html[] = '<div class="year-calendar-item__inner">';
                $html[] = '<p class="fs-5 ps-2 mb-2">' . $days[0]->month . '月</p>';
                $html[] = '<table class="table w-auto mb-5">';
                $html[] = '<thead>';
                $html[] = '<tr>';
                $html[] = '<th class="text-center border-0">月</th>';
                $html[] = '<th class="text-center border-0">火</th>';
                $html[] = '<th class="text-center border-0">水</th>';
                $html[] = '<th class="text-center border-0">木</th>';
                $html[] = '<th class="text-center border-0">金</th>';
                $html[] = '<th class="text-center border-0">土</th>';
                $html[] = '<th class="text-center border-0">日</th>';
                $html[] = '</tr>';
                $html[] = '</thead>';
                $html[] = '<tbody>';

                $start_day = $days[0]->startOfWeek();
                $last_day = $days[0]->endOfWeek();
                $current_month_day = $last_day;
                $fifth_weekend = $start_day->addDays(34)->toDateString();
                $end_of_month = $days[0]->endOfMonth()->toDateString();

                if ($fifth_weekend > $end_of_month || $fifth_weekend === $end_of_month) {
                    $num_weeks_to_display = 5;
                } elseif ($fifth_weekend < $end_of_month) {
                    $num_weeks_to_display = 6;
                }

                for ($i = 0; $i < $num_weeks_to_display; $i++) {
                    // 1週間分の日付レンダリング要素
                    $html[] = '<tr>';
                    while ($start_day->lte($last_day)) {
                        $start_day->year;
                        $date = $start_day->format('Y-m-d');
                        // if ($start_day->year !== $year && PlanningTask::determineIfDateTaskExists($date)) {
                        //     $task_dates[] = $date;
                        // }
                        $html[] = '<td class="text-center px-1 py-1 border-0">';
                        // $html[] = '<a href="/replaced-task-display/' . $date . '" class="date text-decoration-none px-2 js_task-list' . $this->getClassNameIfDateTaskExists($date, $task_dates) . '" data-date="' . $date . '">';
                        $html[] = '<a href="/replaced-task-display/' . $date . '" class="date' . $this->getClassNameIfOtherMonth($start_day, $current_month_day) . ' text-decoration-none js_task-list py-0" data-date="' . $date . '">';
                        $html[] = $this->getDate($start_day);
                        $html[] = '</a>';
                        $html[] = '</td>';
                        $start_day = $start_day->addDay();
                    }
                    $html[] = '</tr>';
                    $last_day = $last_day->addDays(7);
                }
                $html[] = '</tbody>';
                $html[] = '</table>';
                $html[] = '</div>';
                $html[] = '</div>';
            }
            $html[] = '</div>';
        } else {
            // 月カレンダービュー
            $html[] = '<table class="table mt-3">';
            $html[] = '<thead>';
            $html[] = '<tr class="">';
            $html[] = '<th>月</th>';
            $html[] = '<th>火</th>';
            $html[] = '<th>水</th>';
            $html[] = '<th>木</th>';
            $html[] = '<th>金</th>';
            $html[] = '<th>土</th>';
            $html[] = '<th>日</th>';
            $html[] = '</tr>';
            $html[] = '</thead>';
        $html[] = '<tbody class="">';
            // dd($this->days);
            $tasks = PlanningTask::getTasks($this->days);
            $first_day = $this->days[0];
            $last_day = $this->days[count($this->days) - 1];

            while($first_day->lte($last_day)){
                // 1週間分の日付レンダリング要素
                $start_day = $first_day;
                $end_day = $first_day->endOfWeek();
                $html[] = '<tr class="row-height-test">';
                while ($start_day->lte($end_day)) {
                    $html[] = '<td class="' . $this->getDayClassName($start_day) . '">';
                    $date_key =  $start_day->format('Y-m-d');
                    $html[] = '<div class="js_form" data-date="' . $date_key . '" data-time="00:00" data-new-form>';
                    $html[] = '<p class="date' . $this->getClassNameIfOtherMonth($start_day, $end_day) . ' mb-1 pt-1 text-center" data-date="' . $start_day->format('Y-m-d') . '">' . $this->getDate($start_day) . '</p>';
                    $html[] = '<div class="tasks_container" data-date="' . $date_key . '">';
                    if (isset($tasks[$date_key])) {
                        $display_tasks = array_slice($tasks[$date_key], 0, 2);
                        foreach ($display_tasks as $task) {
                            $html[] = '<div class="js_form pe-1" data-date="' . $task['date'] . '" data-time="' . $task['start_time'] .'">';
                            $html[] = '<p class="task badge w-100 mb-0 text-start text-truncate align-middle">' . $task['name'] . '</p>';
                            $html[] = '</div>';
                        }
                        if (count($display_tasks) === 1) {
                            $html[] = '<div class="js_form pe-1" data-date="' . $date_key . '" data-time="00:00"></div>';
                        }
                        $html[] = "</div>";
                        $other_tasks = array_slice($tasks[$date_key], 2);
                        $other_tasks_length = count($other_tasks);
                        if ($other_tasks_length !== 0){
                            $html[] = '<a href="/replaced-task-display/' . $date_key . '" class="js_task-list fw-bold text-decoration-none text-reset d-block px-1 mt-1" data-date="' . $date_key . '">他' . $other_tasks_length . '件</a>';
                        }
                    } else {
                        $html[] = '<div class="js_form pe-1" data-date="' . $date_key . '" data-time="00:00"></div>';
                        $html[] = '<div class="js_form pe-1" data-date="' . $date_key . '" data-time="00:00"></div>';
                        $html[] = '</div>';
                    }
                    $html[] = "</div>";
                    $html[] = '</td>';
                    $start_day = $start_day->addDay();
                }
                $html[] = '</tr>';
                $first_day = $first_day->addDays(7);
            }

            $html[] = '</tbody>';
            $html[] = '</table>';
        }

        return implode("", $html);
    }

    public function getHeaderCalendarYearAndMonth() {
        return $this->carbon->format('Y-n');
    }

    public static function renderHeaderCalendar(string $calendar_date, string $calendar_type, string $screen_width) {
        $carbon = new CarbonImmutable($calendar_date);
        $date = $carbon->format('Y年n月');
        $year = $carbon->year;
        $month = $carbon->month;
        $html = [];
        $html[] = '<input type="hidden" id="js_header_calendar_width_' . $screen_width . '" value="' . $screen_width . '">';
        $html[] = '<input type="hidden" id="js_header_calendar_year_' . $screen_width . '" value="' . $year . '">';
        $html[] = '<input type="hidden" id="js_header_calendar_month_' . $screen_width . '" value="' . $month . '">';
        $html[] = '<div id="js_header_calendar_' . $screen_width . '" class="d-flex justify-content-between px-2">';
        $html[] = '<p>' . $date . '</p>';
        $html[] = '<div class="d-flex align-self-start">';
        $html[] = '<a class="link-mark text-reset text-decoration-none fw-bold px-2" id="js_header_calendar_previous_' . $screen_width . '" href="' . route('header-calendar-previous') . '">&lt;</a>';
        $html[] = '<a class="link-mark text-reset text-decoration-none fw-bold px-2" id="js_header_calendar_next_' . $screen_width . '" href="' . route('header-calendar-next') . '">&gt;</a>';
        $html[] = '</div>';
        $html[] = '</div>';
        // ヘッダー月カレンダービュー
        $html[] = '<table class="table mb-0">';
        $html[] = '<thead>';
        $html[] = '<tr>';
        $html[] = '<th class="text-center border-0">月</th>';
        $html[] = '<th class="text-center border-0">火</th>';
        $html[] = '<th class="text-center border-0">水</th>';
        $html[] = '<th class="text-center border-0">木</th>';
        $html[] = '<th class="text-center border-0">金</th>';
        $html[] = '<th class="text-center border-0">土</th>';
        $html[] = '<th class="text-center border-0">日</th>';
        $html[] = '</tr>';
        $html[] = '</thead>';
        $html[] = '<tbody>';

        $current_month = $carbon->firstOfMonth();
        $first_day = $carbon->firstOfMonth();
        $last_day = $carbon->lastOfMonth()->endOfWeek();

        while($first_day->lte($last_day)){
            // 1週間分の日付レンダリング要素
            $start_day = $first_day->startOfWeek();
            $end_day = $first_day->endOfWeek();
            $html[] = '<tr>';
            while ($start_day->lte($end_day)) {
                $html[] = '<td class="text-center px-1 py-1 border-0">';
                $html[] = '<a class="date text-decoration-none px-2' . self::getHeaderCalendarDayClassName($start_day, $current_month) . '"href="' . route($calendar_type) . '/' . self::getHeaderCalendarLink($start_day) . '">' . self::getHeaderCalendarDate($start_day) . '</a>';
                $html[] = '</td>';
                $start_day = $start_day->addDay();
            }
            $html[] = '</tr>';
            $first_day = $first_day->addDays(7);
        }

        $html[] = '</tbody>';
        $html[] = '</table>';

        return implode("", $html);
    }

    private static function getHeaderCalendarDate(CarbonImmutable $day) {
        return $day->day;
    }

    private static function getHeaderCalendarDayClassName(CarbonImmutable $check_target_month, CarbonImmutable $current_month) {
        if(!$check_target_month->isSameMonth($current_month)) {
            return ' ' . 'other-month' . ' ' . strtolower($check_target_month->format('D'));
        }

        return ' ' . strtolower($check_target_month->format('D'));
    }

    private static function getHeaderCalendarLink(CarbonImmutable $day) {
        return $day->format('Y/n/j');
    }

    private function getDate(CarbonImmutable $day) {
        if ($day->day === 1) {
            return $day->format('n/j');
        }

        return $day->day;
    }

    private function getDayClassName(CarbonImmutable $day) {
        return strtolower($day->format('D'));
    }

    private function getClassNameIfOtherMonth(CarbonImmutable $check_target_month, CarbonImmutable $current_month) {
        // if ($day->month !== $this->days[0]->endOfWeek()->month) {
        //     return ' ' . 'other-month';
        // } else {
        //     return;
        // }
        if(!$check_target_month->isSameMonth($current_month)) {
            return ' ' . 'other-month';
        }

        return ;
    }

    private function getClassNameIfDateTaskExists(string $date, array $task_dates) {
        if (in_array($date, $task_dates)) {
            return ' tasks-include';
        }
    }

    private function getClassNameIfToday(string $date) {
        if ($date === $this->carbon::today()->format('Y-m-d')) {
            return ' todays-date';
        }
    }
}
