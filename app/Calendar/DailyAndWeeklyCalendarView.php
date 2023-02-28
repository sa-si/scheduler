<?php
namespace App\Calendar;

use Carbon\CarbonImmutable;
use App\Models\PlanningTask;

class DailyAndWeeklyCalendarView implements CalendarView {

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
        $html[] = '<td>hoge</td>';

        // 日数分($this->days)の曜日と日付を取得
        // *改善点? 曜日と日にちの取得をそれぞれ関数化するのはどう？
        foreach ($this->days as $day) {
            $html[] = '<th><div>'.$day->locale('ja')->shortDayName.'</div><p class="date" data-date="' . $day->format('Y-m-d') . '">' . $this->getDate($day) . '</p></th>';
        }

        $html[] = '</tr>';
        $html[] = '</thead>';
        $html[] = '<tbody>';

        // 3重配列で取得(年月日、時間)
        $tasks = PlanningTask::getTasks($this->days);
        // 時間を0時〜23時まで取得
        for ($i = self::FIRST_HOUR; $i <= self::FINAL_HOUR; $i++) {
            // 15分おきの時刻を変数に代入。 例⇒00:00や23:45
            $time_0 = sprintf('%02d', $i) . ':' . self::MINUTES['min_0'];
            $time_15 = sprintf('%02d', $i) . ':' . self::MINUTES['min_15'];
            $time_30 = sprintf('%02d', $i) . ':' . self::MINUTES['min_30'];
            $time_45 = sprintf('%02d', $i) . ':' . self::MINUTES['min_45'];

            $html[] = '<tr>';
            $html[] = '<td>' . $time_0 . '</td>';

            foreach ($this->days as $day) {
                $day = $day->format('Y-m-d');

                $html[] = "<td>";

                $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_0 .'">';
                if (isset($tasks[$day][$time_0])) {
                    $html[] = '<p>' . $tasks[$day][$time_0]['name'] . '</p>';
                }
                $html[] = "</div>";

                $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_15 .'">';
                if (isset($tasks[$day][$time_15])) {
                    $html[] =  '<p>' . $tasks[$day][$time_15]['name'] . '</p>';
                }
                $html[] = "</div>";

                $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_30 .'">';
                if (isset($tasks[$day][$time_30])) {
                    $html[] =  '<p>' . $tasks[$day][$time_30]['name'] . '</p>';
                }
                $html[] = "</div>";

                $html[] = '<div class="js_form" data-date="' . $day . '" data-time="' . $time_45 .'">';
                if (isset($tasks[$day][$time_45])) {
                    $html[] =  '<p>' . $tasks[$day][$time_45]['name'] . '</p>';
                }
                $html[] = "</div>";

                $html[] = '</td>';
            }

            $html[] = '</tr>';
        }
        $html[] = '</tbody>';
        $html[] = '</table>';


        return implode("", $html);
    }

    //////////////////////////////////////////////////////////////////
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
