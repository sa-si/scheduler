<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanningTask;
use App\Models\ExecutionTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class TrashCanController extends Controller
{
    public function index(Request $request) {
        $url = parse_url(url()->previous(), PHP_URL_PATH);
        $new_url = substr($url, 1);

        if ($request->path() === $new_url || is_null($request->headers->get('referer'))) {
            $previous_url = route('month');
        } else {
            $previous_url = $request->headers->get('referer');
        }

        $discarded_tasks = PlanningTask::select(['id', 'name', 'created_at', 'deleted_at', DB::raw('"plans" as type')])->onlyTrashed()->where('user_id', Auth::id())->orderBy('deleted_at', 'DESC')->paginate(50);

        return view('trash-can', compact('discarded_tasks', 'previous_url'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'checked_tasks' => 'required|array',
            'checked_tasks.*' => 'required|integer',
        ]);

        if (isset($request->restore)) {
            DB::transaction(function () use($request) {
                if (isset($request->checked_tasks)) {
                     PlanningTask::whereIn('id', $request->checked_tasks)->restore();
                }
            });

            return redirect()
            ->route('trash-can')
            ->with(['message' => 'タスクを復元しました。',
            'status' => 'alert']);
        } elseif (isset($request->destroy)) {
            DB::transaction(function () use($request) {
                if (isset($request->checked_tasks)) {
                    PlanningTask::whereIn('id', $request->checked_tasks)->forceDelete();
                }
            });

            return redirect()
            ->route('trash-can')
            ->with(['message' => 'タスクを完全削除しました。',
            'status' => 'alert']);
        }
    }
}
