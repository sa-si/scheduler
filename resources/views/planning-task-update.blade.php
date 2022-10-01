@extends('layouts.app')

@section('javascript')
<script src="/js/index.js"></script>
@endsection
@section('content')
<form action="{{ route('p-task.update', ['id' => $task->id]) }}" method="POST">
    @csrf
    <input type="text" name="name" value="{{ old('name', $task->name) }}" placeholder="タスク名を入力">
    <br><br>
    <textarea name="description" id="" cols="30" rows="10" placeholder="説明を入力">{{ old('description', $task->description) }}</textarea>
    <br><br>
    <div class="js_initial-disable-wrapper">
        <input id="one_day" type="radio" name="date_time" value="one_day" onclick="toggleEnableAndDisable(['one_day_inputs'], ['multi_day_inputs', 'every_day_inputs', 'every_week_inputs'])" {{ old('date_time', 'one_day') === 'one_day' ? 'checked' : '' }}>
        <label for="one_day">1日</label>
        <div id="one_day_inputs" class="js_initial-disable">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="date" name="date" value="{{ old('date', $task->date) }}">
            <input type="time" name="one_day_start_time" value="{{ old('one_day_start_time', substr($task->start_time, 11, 5)) }}">~
            <input type="time" name="one_day_end_time" value="{{ old('one_day_end_time', substr($task->end_time, 11, 5)) }}">
        </div>
    </div>
    <br><br>
    <div class="js_initial-disable-wrapper">
        <input id="multi_day" type="radio" name="date_time" value="multi_day" onclick="toggleEnableAndDisable(['multi_day_inputs'], ['one_day_inputs', 'every_day_inputs', 'every_week_inputs'])" {{ old('date_time') == 'multi_day' ? 'checked' : '' }}>
        <label for="multi_day">複数日</label>
        <div id="multi_day_inputs" class="js_initial-disable">
            <div class="calendar">
                <table class="table">
                    <thead>
                        <tr>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th>土</th>
                            <th>日</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="monthly-task">
                                <input id="date_check" type="checkbox" name="" value="" class="display-none">
                                <label for="date_check" class="check-day">1</label>
                            </td>
                            <td class="monthly-task">
                            <input type="checkbox" name="dates[]" value="2022-10-01">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                                <input type="checkbox" name="dates[]" value="2022-10-02">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                            <td class="monthly-task">
                            <p>1</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="time" name="multi_day_start_time" value="{{ old('multi_day_start_time', substr($task->start_time, 11, 5)) }}">~
            <input type="time" name="multi_day_end_time" value="{{ old('multi_day_end_time', substr($task->end_time, 11, 5)) }}">
        </div>
    </div>
    <br><br>
    <div class="js_initial-disable-wrapper">
        <input id="old_project" type="radio" name="project_choice" value="old" onclick="toggleEnableAndDisable(['select_project'], ['create_project'])" {{ old('project_choice', 'old') === 'old' ? 'checked' : '' }}>
        <label for="old_project">既存プロジェクト選択</label>
        <div id="select_project" class="js_initial-disable">
            <select name="project">
                <option value="{{ $registered_project->id ?? '' }}">{{ $registered_project->name ?? '-------------' }}</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br><br>
    <div class="js_initial-disable-wrapper">
        <input id="new_project" type="radio" name="project_choice" value="new" onclick="toggleEnableAndDisable(['create_project'], ['select_project'])" {{ old('project_choice') == 'new' ? 'checked' : '' }}>
        <label for="new_project">新規プロジェクト作成</label>
        <div id="create_project" class="js_initial-disable">
            <input type="text" name="project" value="{{ old('project') }}" placeholder="プロジェクト名を入力">
        </div>
    </div>
    <br><br>
    @foreach ($tags as $tag)
        <input id="{{ $tag->id }}" type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, $associated_tags) ? 'checked' : '' }}>
        <label for="{{ $tag->id }}">{{ $tag->name }}</label>
    @endforeach
    <input type="text" name="new_tag" value="" placeholder="タグ名を入力">
    <br><br>
    <a href="{{ route('p-task.destroy', ['id' => $task->id ])}}">削除</a>
    <button>キャンセル</button>
    <button type="submit">登録</button>
    <button>実績タスクとしてコピー</button>
</form>
@endsection
