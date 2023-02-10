<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanningTask;
use App\Models\ExecutionTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrashCanController extends Controller
{
    public function index() {
        $p_tasks = PlanningTask::select(['id', 'name', 'created_at', 'deleted_at', DB::raw('"plans" as type')])->onlyTrashed()->where('user_id', Auth::id())->orderBy('deleted_at', 'DESC');
        $discarded_tasks = ExecutionTask::select(['id', 'name', 'created_at', 'deleted_at', DB::raw('"results" as type')])->onlyTrashed()->where('user_id', Auth::id())->orderBy('deleted_at', 'DESC')->union($p_tasks)->get();

        return view('trash-can', compact('discarded_tasks'));
    }

    public function update(Request $request)
    {
        if (isset($request->restore)) {
            DB::transaction(function () use($request) {
                if (isset($request->plans)) {
                     PlanningTask::whereIn('id', $request->plans)->restore();
                }

                if (isset($request->results)) {
                    ExecutionTask::whereIn('id', $request->results)->restore();
                }
            });

            return redirect()
            ->route('trash-can')
            ->with(['message' => 'タスクを復元しました。',
            'status' => 'alert']);
        } elseif (isset($request->destroy)) {
            DB::transaction(function () use($request) {
                if (isset($request->plans)) {
                    PlanningTask::whereIn('id', $request->plans)->forceDelete();
                }

                if (isset($request->results)) {
                    ExecutionTask::whereIn('id', $request->results)->forceDelete();
                }
            });

            return redirect()
            ->route('trash-can')
            ->with(['message' => 'タスクを完全削除しました。',
            'status' => 'alert']);
        }
    }
}
