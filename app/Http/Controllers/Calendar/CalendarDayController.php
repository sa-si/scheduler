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
    public function index(Request $request, $year, $month, $day){
        $date = $year . $month . $day;
        $carbon = new CarbonImmutable($date);
        $days = [$carbon];

        $calendar = new CalendarView($carbon, $days);

        return view('calendar.day', compact('calendar'));
    }

    public function form(Request $request) {
        $task = PlanningTask::where('date', $request->date)->where('start_time', $request->time)->get()->first() ?? '';
        if ($task) {
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

        $registered_project = $task->project ?? '';
        $registered_project_id = $registered_project->id ?? null;
        $projects = Project::where('user_id', Auth::id())->where('id', '<>', $registered_project_id)->get();
        $tags = Tag::where('user_id', Auth::id())->get();
        $associated_tags = [];
        // dd($request->date, $request->time);
        $date = $request->date;
        $start_time = $request->time;
        $end_time = date('H:i', strtotime("${start_time} +15 min"));
        // dd($date, $end_time);

        return view('components.form-task-create', compact('date', 'start_time', 'end_time', 'registered_project', 'projects', 'tags', 'associated_tags'));
    }
}
