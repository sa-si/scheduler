<?php
namespace App\Calendar;

use Carbon\CarbonImmutable;
use App\Models\PlanningTask;

class CalendarView {

    public $carbon;
    public $days;
    public $tasks;

    const LAST_TIME_NUM = 23;
    const AFTER_15_MIN_NUM = 15;
    const AFTER_30_MIN_NUM = 30;
    const AFTER_45_MIN_NUM = 45;

    public function __construct(CarbonImmutable $carbon, array $days = [], bool $is_input = true)
    {
        $this->carbon = $carbon;
        $this->days = $days;
        // $c = new CarbonImmutable('2022-10-01');
        // dd($this->days);
    }

    public function render() {
        $html = [];
        // 日・週カレンダービュー
        if (count($this->days) === 1 || count($this->days) === 7) {
            $html[] = '<table>';
            $html[] = '<thead>';
            $html[] = '<tr>';
            $html[] = '<td>hoge</td>';
            foreach ($this->days as $day) {
                $html[] = '<th><div>'.$day->locale('ja')->shortDayName.'</div><p class="date" data-date="' . $day->format('Y-m-d') . '">'.$day->day.'</p></th>';
            }
            $html[] = '</tr>';
            $html[] = '</thead>';
            $html[] = '<tbody>';
            $tasks = PlanningTask::getTasks($this->days);
            $first_time = $this->carbon;
            $last_time = $first_time->addHours(self::LAST_TIME_NUM);

            while ($first_time->lte($last_time)) {
                $html[] = '<tr>';
                $start_time_obj = $first_time;
                $start_time = $start_time_obj->format('H:i');
                $html[] = '<td>' . $start_time . '</td>';
                foreach ($this->days as $day) {
                    $day = $day->format('Y-m-d');

                    $html[] = "<td>";

                    $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $start_time .'">';
                    if (isset($tasks[$day][$start_time])) {
                        $html[] = '<p id="task_name">' . $tasks[$day][$start_time]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $after_15_min_obj = $start_time_obj->addMinutes(self::AFTER_15_MIN_NUM);
                    $after_15_min = $after_15_min_obj->format('H:i');

                    $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $after_15_min .'">';
                    if (isset($tasks[$day][$after_15_min])) {
                        $html[] =  '<p id="task_name">' . $tasks[$day][$after_15_min]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $after_30_min_obj = $start_time_obj->addMinutes(self::AFTER_30_MIN_NUM);
                    $after_30_min = $after_30_min_obj->format('H:i');

                    $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $after_30_min .'">';
                    if (isset($tasks[$day][$after_30_min])) {
                        $html[] =  '<p id="task_name">' . $tasks[$day][$after_30_min]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $after_45_min_obj = $start_time_obj->addMinutes(self::AFTER_45_MIN_NUM);
                    $after_45_min = $after_45_min_obj->format('H:i');

                    $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $after_45_min .'">';
                    if (isset($tasks[$day][$after_45_min])) {
                        $html[] =  '<p id="task_name">' . $tasks[$day][$after_45_min]['name'] . '</p>';
                    }
                    $html[] = "</div>";

                    $html[] = '</div>';
                }

                $html[] = '</tr>';
                $first_time = $first_time->addHour();
            }
            $html[] = '</tbody>';
            $html[] = '</table>';
        }  elseif (count($this->days) === 12) {
            // 年カレンダービュー
            // 一ヶ月ごとに処理
            $task_dates = PlanningTask::getDatesOfTasksForOneYear((string) $this->carbon->year);
            // dd((string) $this->carbon->year, $task_dates);
            foreach ($this->days as $days) {
                $html[] = '<p>' . $days[0]->month . '月</p>';
                $html[] = '<table>';
                $html[] = '<thead>';
                $html[] = '<tr>';
                $html[] = '<th>月</th>';
                $html[] = '<th>火</th>';
                $html[] = '<th>水</th>';
                $html[] = '<th>木</th>';
                $html[] = '<th>金</th>';
                $html[] = '<th>土</th>';
                $html[] = '<th>日</th>';
                $html[] = '</tr>';
                $html[] = '</thead>';
                $html[] = '<tbody>';

                $start_day = $days[0]->startOfWeek();
                $last_day = $days[0]->endOfWeek();
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
                        $html[] = '<td class="">';
                        $html[] = '<a href="/replaced-task-display/' . $start_day->format('Y-m-d') . '" class="date js_task-list' . $this->getClassNameIfDateTaskExists($task_dates, $start_day->format('Y-m-d')) . $this->getClassNameIfToday($start_day->format('Y-m-d')) . '" data-date="' . $start_day->format('Y-m-d') . '">';
                        $html[] = $this->getDay($start_day);
                        $html[] = '</a>';
                        $html[] = '</td>';
                        $start_day = $start_day->addDay();
                    }
                    $html[] = '</tr>';
                    $last_day = $last_day->addDays(7);
                }
                $html[] = '</tbody>';
                $html[] = '</table>';
            }
        } else {
            // 月カレンダービュー
            $html[] = '<table>';
            $html[] = '<thead>';
            $html[] = '<tr>';
            $html[] = '<th>月</th>';
            $html[] = '<th>火</th>';
            $html[] = '<th>水</th>';
            $html[] = '<th>木</th>';
            $html[] = '<th>金</th>';
            $html[] = '<th>土</th>';
            $html[] = '<th>日</th>';
            $html[] = '</tr>';
            $html[] = '</thead>';
            $html[] = '<tbody>';

            $tasks = PlanningTask::getTasks($this->days);
            $start_day = $this->days[0]->startOfWeek();
            $last_day = $this->days[0]->endOfWeek();
            $fifth_weekend = $start_day->addDays(34)->toDateString();
            $end_of_month = $this->days[0]->endOfMonth()->toDateString();

            if ($fifth_weekend > $end_of_month || $fifth_weekend === $end_of_month) {
                $num_weeks_to_display = 5;
            } elseif ($fifth_weekend < $end_of_month) {
                $num_weeks_to_display = 6;
            }

            for ($i = 0; $i < $num_weeks_to_display; $i++) {
                // 1週間分の日付レンダリング要素
                $html[] = '<tr>';
                while ($start_day->lte($last_day)) {
                    $html[] = '<td class="' . $this->getClassName($start_day) . '">';
                    $html[] = '<p class="date" data-date="' . $start_day->format('Y-m-d') . '">' . $this->getDay($start_day) . '</p>';
                    $key =  $start_day->format('Y-m-d');
                    if (isset($tasks[$key])) {
                        $display_tasks = array_slice($tasks[$key], 0, 2);
                        if (count($display_tasks) === 1) {
                            foreach ($display_tasks as $task) {
                                $html[] = '<div class="js_form" data-date="' . $task['date'] . '" data-time="' . $task['start_time'] .'">';
                                $html[] = '<p>' . $task['name'] . '</p>';
                                $html[] = "</div>";
                            }
                            $html[] = '<div class="js_form" data-date="' . $key . '" data-time="00:00" data-new-form></div>';
                        } else {
                            foreach ($display_tasks as $task) {
                                $html[] = '<div class="js_form" data-date="' . $task['date'] . '" data-time="' . $task['start_time'] .'">';
                                $html[] = '<p>' . $task['name'] . '</p>';
                                $html[] = "</div>";
                            }
                            $other_tasks = array_slice($tasks[$key], 2);
                            $other_tasks_length = count($other_tasks);
                            if ($other_tasks_length !== 0){
                                $html[] = '<a href="/replaced-task-display/' . $key . '" class="js_task-list" data-date="' . $key . '">他' . $other_tasks_length . '件</a>';
                            }
                        }
                    } else {
                        $html[] = '<div class="js_form" data-date="' . $key . '" data-time="00:00" data-new-form></div>';
                        $html[] = '<div class="js_form" data-date="' . $key . '" data-time="00:00" data-new-form></div>';
                    }
                    $html[] = '</td>';
                    $start_day = $start_day->addDay();
                }
                $html[] = '</tr>';
                $last_day = $last_day->addDays(7);
            }

            $html[] = '</tbody>';
            $html[] = '</table>';
        }

        return implode("", $html);
    }

    private function getDay(CarbonImmutable $day) {
        if ($day->day === 1 || $day->isLastOfMonth()) {
            return $day->format('n月j日');
        }

        return $day->day;
    }

    private function getClassName(CarbonImmutable $day) {
        if ($day->month !== $this->days[0]->month) {
            return 'other-month' . ' ' . strtolower($day->format('D'));
        }

        return strtolower($day->format('D'));
    }

    private function getClassNameIfDateTaskExists(array $task_dates, string $date) {
        if (array_key_exists($date, $task_dates)) {
            return ' tasks-include';
        }
    }

    private function getClassNameIfToday(string $date) {
        if ($date === $this->carbon::today()->format('Y-m-d')) {
            return ' todays-date';
        }
    }
}
