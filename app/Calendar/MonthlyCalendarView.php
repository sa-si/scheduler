<?php
namespace App\Calendar;

use Carbon\CarbonImmutable;
use App\Models\PlanningTask;

class CalendarView {

    public $carbon;
    public $days;
    public $tasks;

    const FIRST_HOUR = 0;
    const FINAL_HOUR = 23;
    const MINUTES = ['min_0' => '00', 'min_15' => '15', 'min_30' => '30', 'min_45' => '45'];

    public function __construct(CarbonImmutable $carbon, array $days = [])
    {
        $this->carbon = $carbon;
        $this->days = $days;
    }

    public function render() {
        $html = [];
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
        dd($tasks);
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
            // 7日分繰り返す
            while ($start_day->lte($last_day)) {
                // 1曜日(1日)分のtd要素
                // クラス名を取得
                $html[] = '<td class="' . $this->getDayClassName($start_day) . '">';
                // 日付数字
                $html[] = '<p class="date" data-date="' . $start_day->format('Y-m-d') . '">' . $this->getDate($start_day) . '</p>';
                // task配列検索用日付フォーマットキー
                $key =  $start_day->format('Y-m-d');
                // task配列の該当キーが有るか
                if (isset($tasks[$key])) {
                    //表示用タスク最大2件
                    $display_tasks = array_slice($tasks[$key], 0, 2);
                    // 表示用タスクが１つだった場合 elseは、2つの場合
                    if (count($display_tasks) === 1) {
                        foreach ($display_tasks as $task) {
                            // task名を囲むレンダリングdiv要素
                            $html[] = '<div class="js_form" data-date="' . $task['date'] . '" data-time="' . $task['start_time'] .'">';
                            // task名
                            $html[] = '<p>' . $task['name'] . '</p>';
                            $html[] = "</div>";
                        }
                        // タスクなし枠
                        $html[] = '<div class="js_form" data-date="' . $key . '" data-time="00:00" data-new-form></div>';
                    } else {
                        // 2つともタスクあり
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


        return implode("", $html);
    }

    private function getDate(CarbonImmutable $day) {
        if ($day->day === 1 || $day->isLastOfMonth()) {
            return $day->format('n月j日');
        }

        return $day->day;
    }

    private function getDayClassName(CarbonImmutable $day) {
        if ($day->month !== $this->days[0]->month) {
            return 'other-month' . ' ' . strtolower($day->format('D'));
        }

        return strtolower($day->format('D'));
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
