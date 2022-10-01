<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\FacadesAuth;
use App\Models\Project;
use App\Models\ExecutionTask;
use App\Models\ExecutionTaskTag;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class ExecutionTaskController extends Controller
{
    public function create() {
        return view('execution-task-input');
    }

    public function store(Request $request) {
        // 課題：projectの値が0or1であることを保証する
        // 課題２:date_timeの値が１つであることを保証する
        // 課題２.5:date属性が配列でない場合、値は1つであることを保証する。
        // Q.name属性が配列の場合のバリデ方法は？
        // projectの同user_idの中でunique設定
        $request->validate([
            'name' => 'required|string|max:255',
            'description'  => 'required|string|max:5000',
            'date' => 'sometimes|required|date',
            'one_day_start_time' => 'sometimes|required|string',
            'one_day_end_time' => 'sometimes|required|string',
            'dates' => 'sometimes|required|array',
            'dates.*' => 'sometimes|required|date',
            'multi_day_start_time' => 'sometimes|required|string',
            'multi_day_end_time' => 'sometimes|required|string',
            'project' => 'nullable|string',
        ]);

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

            if ( $request->date_time === 'one_day' ) {
                $task = ExecutionTask::create(['user_id' => Auth::id(), 'project_id' => $project_id ?? null, 'name' => $request->name , 'date' => $request->date, 'start_time' => $request->one_day_start_time, 'end_time' => $request->one_day_end_time, 'description' => $request->description]);

                $task->id = $task->id;

                $tag_exists_if_get_id = Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->exists() ? Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->first()['id']: false;

                if ((!empty($request->new_tag) || $request->new_tag === '0') && !$tag_exists_if_get_id) {
                    $tag = Tag::create(['user_id' => Auth::id(), 'name' => $request->new_tag]);
                    $tag_id = $tag->id;
                    ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_id]);
                } elseif ($tag_exists_if_get_id && !in_array($tag_exists_if_get_id, $request->tags ?? [])) {
                    ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_exists_if_get_id]);
                }

                if (!empty($request->tags[0])){
                    foreach($request->tags as $tag){
                        ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag]);
                    }
                }
            } elseif ($request->date_time === 'multi_day') {
                $posts = $request->all();
                $has_tags = false;
                $new_tag_existing = false;
                // dd($posts);
                $task = ExecutionTask::create(['user_id' => Auth::id(), 'project_id' => $project_id ?? null, 'name' => $posts['name'] , 'date' => $posts['dates'][0], 'start_time' => $posts['multi_day_start_time'], 'end_time' => $posts['multi_day_end_time'], 'description' => $posts['description']]);

                $task->id = $task->id;

                $tag_exists_if_get_id = Tag::where('user_id', '=', Auth::id())->where('name', '=', $posts['new_tag'])->exists() ? Tag::where('user_id', '=', Auth::id())->where('name', '=', $posts['new_tag'])->first()['id']: false;

                if ((!empty($posts['new_tag']) || $posts['new_tag'] === '0') && !$tag_exists_if_get_id) {
                    $tag = Tag::create(['user_id' => Auth::id(), 'name' => $posts['new_tag']]);
                    $tag_id = $tag->id;
                    ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_id]);
                } elseif ($tag_exists_if_get_id && !in_array($tag_exists_if_get_id, $posts['tags'] ?? [])) {
                    $new_tag_existing = true;
                    ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_exists_if_get_id]);
                }

                if (!empty($posts['tags'][0])){
                    $has_tags = true;
                    foreach($posts['tags'] as $tag){
                        ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag]);
                    }
                }

                $dates = $posts['dates'];
                array_shift($dates);

                foreach ($dates as $date) {
                    $task = ExecutionTask::create(['user_id' => Auth::id(), 'project_id' => $project_id ?? null, 'name' => $posts['name'] , 'date' => $date, 'start_time' => $posts['multi_day_start_time'], 'end_time' => $posts['multi_day_end_time'], 'description' => $posts['description']]);

                    $task->id = $task->id;

                    if ($tag_id) {
                        ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_id]);
                    } elseif ($new_tag_existing) {
                        ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_exists_if_get_id]);
                    }

                    if ($has_tags){
                        foreach($posts['tags'] as $tag){
                            ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag]);
                        }
                    }
                }
            }
        });

        return view('Execution-task-input');

    }

    public function edit($id)
    {
        $task = ExecutionTask::find($id);
        $registered_project = $task->project;
        $registered_project_id = $registered_project->id ?? null;
        $projects = Project::where('user_id', Auth::id())->where('id', '<>', $registered_project_id)->get();
        $tags = Tag::where('user_id', Auth::id())->get();
        $task_tags = ExecutionTaskTag::where('Execution_task_id', $task->id)->get()->toArray();
        $associated_tags = [];
        foreach($task_tags as $tag){
            array_push($associated_tags, $tag['tag_id']);
        }

        return view('Execution-task-update', compact('task', 'registered_project', 'projects', 'tags', 'associated_tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description'  => 'required|string|max:5000',
            'date' => 'sometimes|required|date',
            'one_day_start_time' => 'sometimes|required|string',
            'one_day_end_time' => 'sometimes|required|string',
            'dates' => 'sometimes|required|array',
            'dates.*' => 'sometimes|required|date',
            'multi_day_start_time' => 'sometimes|required|string',
            'multi_day_end_time' => 'sometimes|required|string',
            'project' => 'nullable|string',
        ]);

        $task = ExecutionTask::find($id);

        DB::transaction(function () use($request, $task) {
            if ( !empty($request->project) || $request->project === '0' ) {
                $projectExists = Project::where('user_id', Auth::id())->where('id', $request->project)->exists();
                if ( !$projectExists ) {
                    $project = Project::create(['user_id' => Auth::id(), 'name' => $request->project]);
                    $request->project = $project->id;
                }
            }

            if ( $request->date_time === 'one_day' ) {
                $task->update(['user_id' => Auth::id(), 'project_id' => $request->project ?? null, 'name' => $request->name , 'date' => $request->date, 'start_time' => $request->one_day_start_time, 'end_time' => $request->one_day_end_time, 'description' => $request->description]);

                $Execution_task_tags = ExecutionTaskTag::where('Execution_task_id', $task->id)->get()->toArray();

                foreach ($Execution_task_tags as $task_tag) {
                    // 過去に登録したタスクタグの組み合わせが、現在チェックが外されていれば
                    if (!in_array($task_tag['tag_id'], $request->tags ?? [])) {
                        //消す
                        ExecutionTaskTag::where('Execution_task_id', $task->id)->where('tag_id', $task_tag['tag_id'])->delete();
                    }
                }
                // チェックされたタグが、タスクタグテーブルにない場合、タスクタグテーブルに保存
                if (!empty($request->tags[0])){
                    foreach($request->tags as $tag){
                        if (!in_array($tag, array_column($Execution_task_tags, 'tag_id'))) {
                            //登録
                            // dd($task->id, $tag);
                            ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag]);
                        }
                    }
                }

                // 課題
                //DBにタグがあるかどうか
                $tag_exists_if_get_id = Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->exists() ? Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->first()['id']: false;

                if ((!empty($request->new_tag) || $request->new_tag === '0') && !$tag_exists_if_get_id) {
                    $tag = Tag::create(['user_id' => Auth::id(), 'name' => $request->new_tag]);
                    $tag_id = $tag->id;
                    ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_id]);
                    // $request->new_tagがDBにあって、チェックはされていないとき
                } elseif ($tag_exists_if_get_id && !in_array($tag_exists_if_get_id, $request->tags ?? [])) {
                    // タスクタグテーブルにはあるかもしれない
                    $task_tag_exists_if_get_id = ExecutionTaskTag::where('Execution_task_id', $task->id)->where('tag_id',  $tag_exists_if_get_id)->exists() ? ExecutionTaskTag::where('Execution_task_id', $task->id)->where('tag_id',  $tag_exists_if_get_id)->first()['id']: false;
                    if ($task_tag_exists_if_get_id) {
                        ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $task_tag_exists_if_get_id]);
                    }
                }
                // /課題
            } elseif ($request->date_time === 'multi_day') {
                // $posts = $request->all();
                // $has_tags = false;
                // $new_tag_existing = false;
                // // dd($posts);
                // $task = ExecutionTask::create(['user_id' => Auth::id(), 'project_id' => $project_id ?? null, 'name' => $posts['name'] , 'date' => $posts['dates'][0], 'start_time' => $posts['multi_day_start_time'], 'end_time' => $posts['multi_day_end_time'], 'description' => $posts['description']]);

                // $task->id = $task->id;

                // $tag_exists_if_get_id = Tag::where('user_id', '=', Auth::id())->where('name', '=', $posts['new_tag'])->exists() ? Tag::where('user_id', '=', Auth::id())->where('name', '=', $posts['new_tag'])->first()['id']: false;

                // if ((!empty($posts['new_tag']) || $posts['new_tag'] === '0') && !$tag_exists_if_get_id) {
                //     $tag = Tag::create(['user_id' => Auth::id(), 'name' => $posts['new_tag']]);
                //     $tag_id = $tag->id;
                //     ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_id]);
                // } elseif ($tag_exists_if_get_id && !in_array($tag_exists_if_get_id, $posts['tags'] ?? [])) {
                //     $new_tag_existing = true;
                //     ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_exists_if_get_id]);
                // }

                // if (!empty($posts['tags'][0])){
                //     $has_tags = true;
                //     foreach($posts['tags'] as $tag){
                //         ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag]);
                //     }
                // }

                // $dates = $posts['dates'];
                // array_shift($dates);

                // foreach ($dates as $date) {
                //     $task = ExecutionTask::create(['user_id' => Auth::id(), 'project_id' => $project_id ?? null, 'name' => $posts['name'] , 'date' => $date, 'start_time' => $posts['multi_day_start_time'], 'end_time' => $posts['multi_day_end_time'], 'description' => $posts['description']]);

                //     $task->id = $task->id;

                //     if ($tag_id) {
                //         ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_id]);
                //     } elseif ($new_tag_existing) {
                //         ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag_exists_if_get_id]);
                //     }

                //     if ($has_tags){
                //         foreach($posts['tags'] as $tag){
                //             ExecutionTaskTag::insert(['Execution_task_id' => $task->id, 'tag_id' => $tag]);
                //         }
                //     }
                // }
            }
        });

        return redirect()->route('p-task.update', ['id' => $id ]);
    }

    public function destroy($id)
    {
        ExecutionTask::findOrFail($id)->delete();

        return redirect()
        ->route('index')
        ->with(['message' => 'タスクを削除しました。',
        'status' => 'alert']);
    }
}
