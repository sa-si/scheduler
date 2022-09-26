@extends('layouts.app')

@section('javascript')
<script src="/js/index.js"></script>
@endsection
@section('content')
<form action="{{ route('p-task.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="タスク名を入力">
    <br><br>
    <textarea name="description" id="" cols="30" rows="10" placeholder="説明を入力"></textarea>
    <br><br>
    <label for="one_shot">日時(通常)</label>
    <input id="one_shot" type="radio" name="date_time" value="one_shot" onclick="toggleEnableAndDisable(['one_shots'], ['regulars'])">
    <div id="one_shots" class="js_initial-disable">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="date" name="date" value="2020-09-23">
        <input type="time" name="start_time" value="00:00">~
        <input type="time" name="end_time" value="00:00">
    </div>
    <br><br>
    {{-- now --}}
    <label for="regular">日時(定期)</label>
    <input id="regular" type="radio" name="date_time" value="regular" onclick="toggleEnableAndDisable(['every_day', 'every_week', 'choice'], ['one_shots'])">
    <br>
    &nbsp;&nbsp;&nbsp;
    <div id="regulars" class="js_initial-disable">
    &nbsp;&nbsp;&nbsp;
    <input id="every_day" type="radio" name="interval" value="every_day" onclick="toggleEnableAndDisable(['every_day_input'], ['every_week_input', 'choice_input'])">
    <label for="every_day">毎日</label><br>
    <div id="every_day_input" class="js_initial-disable">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="date" name="date" value="2020-09-23">~<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="time" name="start_time" value="00:00">~
        <input type="time" name="end_time" value="00:00">
    </div>
    <br><br>
    &nbsp;&nbsp;&nbsp;
    <input id="every_week" type="radio" name="interval" value="every_week" onclick="toggleEnableAndDisable(['every_week_input'], ['every_day_input', 'choice_input'])">
    <label for="every_week">毎週</label>
    <br>
    <div id="every_week_input" class="js_initial-disable">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="mon" type="checkbox"><label for="mon">月</label>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="tue" type="checkbox"><label for="tue">火</label>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="wed" type="checkbox"><label for="wed">水</label>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="thu" type="checkbox"><label for="thu">木</label>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="fri" type="checkbox"><label for="fri">金</label>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="sat" type="checkbox"><label for="sat">土</label>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="sun" type="checkbox" class="inputTest"><label for="sun" class="labelTest">日</label>
        <br><br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="date" name="date" value="2020-09-23">~<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="time" name="start_time" value="00:00">~
        <input type="time" name="end_time" value="00:00">
    </div>
    <br><br>
    &nbsp;&nbsp;&nbsp;
    <input id="choice" type="radio" name="interval" value="choice" onclick="toggleEnableAndDisable(['choice_input'], ['every_week_input', 'every_day_input'])">
    <label for="choice">選択</label>
    <div id="choice_input" class="js_initial-disable">
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
                        <input type="text">
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
        <input type="time" name="start_time" value="00:00">~
        <input type="time" name="end_time" value="00:00">
    </div>
    </div>
    {{-- /now --}}
    <br><br>
    <input id="old_project" type="radio" name="project_choice" onclick="toggleEnableAndDisable(['select_project'], ['create_project'])">
    <label for="old_project">既存プロジェクト選択</label>
    <div id="select_project" class="js_initial-disable">
        <select name="project">
            <option value="">-------</option>
            <option value="">あああ</option>
        </select>
    </div>
    <br><br>
    <input id="new_project" type="radio" name="project_choice" onclick="toggleEnableAndDisable(['create_project'], ['select_project'])">
    <label for="new_project">新規プロジェクト作成</label>
    <div id="create_project" class="js_initial-disable">
        <input type="text" name="project" value="" placeholder="プロジェクト名を入力">
    </div>
    <br><br>
    <input type="text" name="tags[]" value="" placeholder="タグを入力">
    <br><br>
    <button>キャンセル</button>
    <button type="submit">登録</button>
    <button>実績タスクとしてコピー</button>
</form>
@endsection
