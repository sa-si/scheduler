@extends('layouts.app')

@section('content')
    <form action="{{ route('trash-can') }}" method="POST" class="trash-can-form" name="trashCan" id="js_trash_can_form">
        @csrf
        {{-- 復元・・・deleted_atを削除
	削除・・・物理削除 --}}
        <div class="trash-can-container container-md">
            <div class="trash-can-buttons px-2 pt-3 pb-3">
                <input type="checkbox" id="js_batch-check-button" class="">
                <label for="js_batch-check-button" class="me-3">一括チェック</label>

                <button type="submit" name="restore" value="1" class="modal__btn modal__btn-primary me-1"
                    id="js_trash-can-form-restore-submit">復元</button>
                <button type="submit" name="destroy" value="1" class="modal__btn"
                    id="js_trash-can-form-destroy-submit">完全削除</button>
            </div>
            <div class="alert alert-danger display-none" id="error-form-trash-can-field"></div>
            <table class="table trash-can-table">
                <thead>
                    <tr>
                        <th class="thead-check"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                                <path
                                    d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z" />
                                <path
                                    d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                            </svg></th>
                        <th class="thead-task">タスク名</th>
                        <th class="thead-create">作成日</th>
                        <th class="thead-destroy">削除日</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($discarded_tasks) === 0)
                        <tr>
                            <td class="trash-can-no-deleted-tasks"></td>
                            <td class="trash-can-no-deleted-tasks">削除したタスクがありません。</td>
                        </tr>
                    @else
                        @foreach ($discarded_tasks as $task)
                            <tr>
                                <td><input type="checkbox" name="checked_tasks[]" value="{{ $task->id }}"
                                        class="js_batch-check-target">
                                </td>
                                <td>{{ $task->name }}</td>
                                <td>{{ substr($task->created_at, 0, 10) }}</td>
                                <td>{{ substr($task->deleted_at, 0, 10) }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            {{ $discarded_tasks->links() }}
        </div>
    </form>
@endsection
