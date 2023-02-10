<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\FacadesAuth;
use App\Models\Project;
use App\Models\PlanningTask;
use App\Models\PlanningTaskTag;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class PlanningTaskController extends Controller
{
    // public function create() {
    //     $tags = Tag::where('user_id', Auth::id())->get();
    //     return view('planning-task-input');
    // }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description'  => 'nullable|string|max:5000',
            'date' => 'required|string|size:10',
            'one_day_start_time' => 'required|date_format:H:i|size:5',
            'one_day_end_time' => 'required|date_format:H:i|after:one_day_start_time|size:5',
            'project' => 'nullable|integer',
            'tags.*.*' => 'nullable|integer',
        ]);

        $task_time_duplication_check_result = PlanningTask::taskTimeDuplicationCheck($request->date, $request->one_day_start_time, $request->one_day_end_time);
        if ($task_time_duplication_check_result) {
            return ['time_duplicated_notification_message' => '既にその時間には別のタスクが登録されています'];
        }

        DB::transaction(function () use($request) {
            if ( !empty($request->project) || $request->project === '0' ) {
                $projectExists = Project::where('user_id', Auth::id())->where('name', $request->project)->exists();
                if ( !$projectExists ) {
                    $project = Project::create(['user_id' => Auth::id(), 'name' => $request->project]);
                    $project_id = $project->id;
                } else {
                    $project_id = Project::where('user_id', Auth::id())->where('name', $request->project)->first()['id'];
                }
            }
            $task = PlanningTask::create(['user_id' => Auth::id(), 'project_id' => $project_id ?? null, 'name' => $request->name , 'date' => $request->date, 'start_time' => $request->one_day_start_time, 'end_time' => $request->one_day_end_time, 'description' => $request->description]);

            $task->id = $task->id;

            $tag_exists_if_get_id = Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->exists() ? Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->first()['id']: false;

            if ((!empty($request->new_tag) || $request->new_tag === '0') && !$tag_exists_if_get_id) {
                $tag = Tag::create(['user_id' => Auth::id(), 'name' => $request->new_tag]);
                $tag_id = $tag->id;
                PlanningTaskTag::insert(['planning_task_id' => $task->id, 'tag_id' => $tag_id]);
            } elseif ($tag_exists_if_get_id && !in_array($tag_exists_if_get_id, $request->tags ?? [])) {
                PlanningTaskTag::insert(['planning_task_id' => $task->id, 'tag_id' => $tag_exists_if_get_id]);
            }

            if (!empty($request->tags[0])){
                foreach($request->tags as $tag){
                    PlanningTaskTag::insert(['planning_task_id' => $task->id, 'tag_id' => $tag]);
                }
            }
        });

        return ['registration_success' => 'タスク登録成功'];

    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description'  => 'nullable|string|max:5000',
            'date' => 'required|string|size:10',
            'one_day_start_time' => 'required|date_format:H:i|size:5',
            'one_day_end_time' => 'required|date_format:H:i|after:one_day_start_time|size:5',
            'project' => 'nullable|integer',
            'tags.*.*' => 'nullable|integer',
        ]);

        $task_time_duplication_check_result = PlanningTask::taskTimeDuplicationCheck($request->date, $request->one_day_start_time, $request->one_day_end_time, $request->task_id);
        if ($task_time_duplication_check_result) {
            return ['time_duplicated_notification_message' => '既にその時間には別のタスクが登録されています'];
        }

        $task = PlanningTask::find($request->task_id);

        DB::transaction(function () use($request, $task) {
            if ( !empty($request->project) || $request->project === '0' ) {
                $projectExists = Project::where('user_id', Auth::id())->where('id', $request->project)->exists();
                if ( !$projectExists ) {
                    $project = Project::create(['user_id' => Auth::id(), 'name' => $request->project]);
                    $request->project = $project->id;
                }
            }

            $task->update(['user_id' => Auth::id(), 'project_id' => $request->project ?? null, 'name' => $request->name , 'date' => $request->date, 'start_time' => $request->one_day_start_time, 'end_time' => $request->one_day_end_time, 'description' => $request->description]);

            $planning_task_tags = PlanningTaskTag::where('planning_task_id', $task->id)->get()->toArray();

            foreach ($planning_task_tags as $task_tag) {
                // 過去に登録したタスクタグの組み合わせが、現在チェックが外されていれば
                if (!in_array($task_tag['tag_id'], $request->tags ?? [])) {
                    //消す
                    PlanningTaskTag::where('planning_task_id', $task->id)->where('tag_id', $task_tag['tag_id'])->delete();
                }
            }
            // チェックされたタグが、タスクタグテーブルにない場合、タスクタグテーブルに保存
            if (!empty($request->tags[0])){
                foreach($request->tags as $tag){
                    if (!in_array($tag, array_column($planning_task_tags, 'tag_id'))) {
                        //登録
                        // dd($task->id, $tag);
                        PlanningTaskTag::insert(['planning_task_id' => $task->id, 'tag_id' => $tag]);
                    }
                }
            }

            $tag_exists_if_get_id = Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->exists() ? Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->first()['id']: false;

            if ((!empty($request->new_tag) || $request->new_tag === '0') && !$tag_exists_if_get_id) {
                $tag = Tag::create(['user_id' => Auth::id(), 'name' => $request->new_tag]);
                $tag_id = $tag->id;
                PlanningTaskTag::insert(['planning_task_id' => $task->id, 'tag_id' => $tag_id]);
                // $request->new_tagがDBにあって、チェックはされていないとき
            } elseif ($tag_exists_if_get_id && !in_array($tag_exists_if_get_id, $request->tags ?? [])) {
                // タスクタグテーブルにはあるかもしれない
                $task_tag_exists_if_get_id = PlanningTaskTag::where('planning_task_id', $task->id)->where('tag_id',  $tag_exists_if_get_id)->exists() ? PlanningTaskTag::where('planning_task_id', $task->id)->where('tag_id',  $tag_exists_if_get_id)->first()['id']: false;
                if ($task_tag_exists_if_get_id) {
                    PlanningTaskTag::insert(['planning_task_id' => $task->id, 'tag_id' => $task_tag_exists_if_get_id]);
                }
            }
        });

        return ['update_success' => 'タスク更新成功'];
    }

    public function destroy($id)
    {
        PlanningTask::findOrFail($id)->delete();

        // return redirect()
        // ->route('index')
        // ->with(['message' => 'タスクを削除しました。',
        // 'status' => 'alert']);
    }

    public function getDailyTasksInJson($day)
    {
        $tasks = PlanningTask::getDailyTasks($day);

        return $tasks;
    }

    public function toggleCompletionChecks($id)
    {
        $task = PlanningTask::findOrFail($id);

        if ($task->completion_check === 0) {
            $task->update(['completion_check' => 1]);
        } elseif ($task->completion_check === 1) {
            $task->update(['completion_check' => 0]);
        }

        return $task;
    }
}
