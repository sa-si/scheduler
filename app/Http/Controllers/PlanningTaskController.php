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
    public function create() {
        return view('planning-task-input');
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
            'date' => 'required|date',
            'one_day_start_time' => 'required|string',
            'one_day_end_time' => 'required|string',
            'project' => 'nullable|string',
        ]);

        DB::transaction(function () use($request) {
            // name="project"の値があれば、新規の場合テーブルに登録し変数に代入or既存の場合テーブルからid取得し変数に代入
            // $project_id = null;
            if ( !empty($request->project) || $request->project === '0' ) {
                $projectExists = Project::where('user_id', Auth::id())->where('name', $request->project)->exists();
                if ( !$projectExists ) {
                    $project_id = Project::insertGetId(['user_id' => Auth::id(), 'name' => $request->project]);
                } else {
                    $project_id = Project::where('user_id', Auth::id())->where('name', $request->project)->select('id')->get();
                }
            }

            //続きここから。１日の場合と複数日の場合で処理を変える。
            if ( $request->date_time === 'one_day' ) {
                $task = PlanningTask::create(['user_id' => Auth::id(), 'project_id' => $project_id ?? null, 'name' => $request->name , 'date' => $request->date, 'start_time' => $request->one_day_start_time, 'end_time' => $request->one_day_end_time, 'description' => $request->description]);
                // dd($task_id);
                // 既存タグにチェックがあれば、その数ぶんだけDBからid取得しplanning_task_tagsテーブルにインサート
                // new_tagが空でなければ、既存タグに同じnameカラムの値がないかチェックし、あればそのidを既存テーブルから取得し、planning_task_tagsテーブルに登録・既存タグnameカラムになければ、tagsテーブルにuser_id,nameをインサートし、planning_task_tagsテーブルにインサート
                $task_id = $task->id;
                $tag_exists = Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->exists();
                if( ( !empty($request->new_tag) || $request->new_tag === '0' ) && !$tag_exists ) {
                    // 新規タグが既に存在しなければ、tagsテーブルにインサートIDを取得
                    $tag_id = Tag::insertGetId(['user_id' => Auth::id(), 'name' => $request->new_tag]);
                    // memo_tagにインサートして、メモとタグを紐付ける
                    PlanningTaskTag::insert(['planning_task_id' => $task_id, 'tag_id' => $tag_id]);
                }

                if ( $tag_exists ) {
                    $existing_tag = Tag::where('user_id', '=', Auth::id())->where('name', '=', $request->new_tag)->get();
                    $tag_id = $existing_tag[0]['id'];
                    if ( !in_array($tag_id, $request->tags ?? []) ) {
                        PlanningTaskTag::insert(['planning_task_id' => $task_id, 'tag_id' => $tag_id]);
                    }
                }

                if( !empty($request->tags[0]) ){
                    foreach($request->tags as $tag){
                        PlanningTaskTag::insert(['planning_task_id' => $task_id, 'tag_id' => $tag]);
                    }
                }
            } elseif ($request->date_time === 'multi_day') {

            }
            // タスク登録レコードが複数の場合のタグのインサートの仕方を考案する必要あり
        });

        return view('planning-task-input');

    }


}
