<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Calendar\CalendarView;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Models\PlanningTask;
use App\Models\Project;
use App\Models\Tag;
use App\Models\PlanningTaskTag;
use Illuminate\Support\Facades\Auth;

class CalendarDayController extends Controller
{
    public function index(Request $request, ?int $year = null, ?int $month = null, ?int $day = null) {
        $carbon = CarbonImmutable::createSafe($year, $month, $day);
        // 日・週・月・年で変わる
        $days = [$carbon];

        //一緒
        $calendar = new CalendarView($carbon, $days);
        // day.bladeに移動
        // $form_path = url(route('form'));
        // 日・週・月・年で変わる
        $previous = $carbon->subDay()->format('Y/n/j');
        $next = $carbon->addDay()->format('Y/n/j');
        $header_date = $carbon->format('Y年n月j日');
        $header_calendar_date = $carbon->format('Y年n月');
        //一緒
        $request_path_split = explode("/", $request->path());
        $calendar_type = array_shift($request_path_split);
        $request_date_path = implode('/', $request_path_split);

        return view('calendar.day', compact('calendar', 'calendar_type', 'previous', 'next', 'header_date', 'request_date_path', 'header_calendar_date'));
    }

    public function form(Request $request) {
        $task = PlanningTask::where('date', $request->date)->where('start_time', $request->time)->whereNull('deleted_at')->get()->first() ?? '';
        if (!$request->form && $task) {
            $registered_project = $task->project;
            $registered_project_id = $registered_project->id ?? null;
            $projects = Project::where('user_id', Auth::id())->where('id', '<>', $registered_project_id)->get();
            $tags = Tag::where('user_id', Auth::id())->get();
            $task_tags = PlanningTaskTag::where('planning_task_id', $task->id)->get()->toArray();
            $associated_tags = [];
            foreach($task_tags as $tag){
                array_push($associated_tags, $tag['tag_id']);
            }

            return view('components.form-task-edit', compact('task', 'registered_project', 'projects', 'tags', 'associated_tags'));
        }

        // $registered_project = $task->project ?? '';
        // $registered_project_id = $registered_project->id ?? null;
        // $projects = Project::where('user_id', Auth::id())->where('id', '<>', $registered_project_id)->get();
        $projects = Project::where('user_id', Auth::id())->get();
        $tags = Tag::where('user_id', Auth::id())->get();
        $associated_tags = [];
        $date = $request->date;
        if ($request->form) {
        $start_time = '00:00';
        $end_time = '00:15';
        }
        else {
            $start_time = $request->time;
            $end_time = date('H:i', strtotime("${start_time} +15 min"));
        }

        // return view('components.form-task-create', compact('date', 'start_time', 'end_time', 'registered_project', 'projects', 'tags', 'associated_tags'));
        return view('components.form-task-create', compact('date', 'start_time', 'end_time', 'projects', 'tags', 'associated_tags'));
    }
}
