<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Tag;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PlanningTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'project_id', 'name', 'date', 'start_time', 'end_time', 'description', 'completion_check'];

    protected $dates = [
        'start_time',
        'end_time'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

//    public static function getTask(string $day = null, string $start_time = '', $end_time = '') {
//        //$dayのformat(Ymi)版とモデルのdateが一致かつ$timeとモデルのstart_timeが〇〇:00 or 〇〇:30で、orderByでstart_timeが若い方を先頭に.
//        if ($day) {
//          $task = optional(PlanningTask::where('user_id', Auth::id())->where('date', $day)->where('start_time', $start_time)->first());
//          return $task;
//        }
//    }

    public function getStartTimeAttribute($value)
    {
        return date('H:i', strtotime($value));
    }

    public static function getTasks(array $days) {
        // $daysの日付の数分planning_taskテーブルのレコードを取得
        $all_tasks = [];
        // $days→carbonオブジェクトが1〜31つはいった配列
        foreach ($days as $day) {
            $day = $day->format('Y-m-d');
            $tasks = PlanningTask::where('user_id', Auth::id())->where('date', $day)->orderBy('start_time', 'asc')->get()->toArray();

            if (!$tasks) {
                continue;
            }

            $day_tasks = [];

            foreach ($tasks as $task) {
                $time = $task['start_time'];
                $day_tasks[$time] = $task;
            }

            $key = $tasks[0]['date'];
            $all_tasks[$key] = $day_tasks;
        }

        return $all_tasks;
    }

    public static function getDailyTasks(string $day) {
        $tasks = PlanningTask::where('user_id', Auth::id())->where('date', $day)->orderBy('start_time', 'asc')->get()->toArray();

        return $tasks;
    }

    public static function getDatesOfTasksForOneYear(string $year) {
        $array_type_task_dates = PlanningTask::distinct()->select('date')->where('user_id', Auth::id())->where('date', 'like', $year . '%')->orderBy('date', 'ASC')->get()->toArray();

        $task_dates = [];
        foreach ($array_type_task_dates as $date) {
            foreach ($date as $value) {
                $task_dates[] = $value;
            }
        }

        return $task_dates;
    }

    public static function determineIfDateTaskExists(string $day) {
        if (DB::table('planning_tasks')->where('user_id', Auth::id())->where('date', $day)->whereNull('deleted_at')->exists()) {
            return true;
        } else {
            return false;
        }
    }

    // public static function returnClassNameIfToday(string $day) {
    //     if (DB::table('planning_tasks')->where('user_id', Auth::id())->where('date', $day)->whereNull('deleted_at')->exists()) {
    //         return ' tasks-include';
    //     } else {
    //         return '';
    //     }
    // }
}
