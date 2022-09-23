@extends('layouts.app')
@section('content')
<form action="" method="POST">
    <input type="text" placeholder="タスク名を入力">
    <br><br>
    <textarea name="description" id="" cols="30" rows="10" placeholder="説明を入力"></textarea>
    <br><br>
    <label for="one_shot">日時(通常)</label>
    <input id="one_shot" type="radio" name="date_time" value="one_shot">
        <input type="date" name="date" value="2020-09-23">
        <input type="time" name="start_time" value="00:00">~
        <input type="time" name="end_time" value="00:00">
    <br><br>
    {{-- now --}}
    <label for="regular">日時(定期)</label>
    <input id="regular" type="radio" name="date_time" value="regular">
    <br>
    &nbsp;&nbsp;&nbsp;
    <input id="every_day" type="radio" name="interval" value="every_day">
    <label for="every_day">毎日</label><br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="date" name="date" value="2020-09-23">~<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="time" name="start_time" value="00:00">~
    <input type="time" name="end_time" value="00:00">
    <br><br>
    &nbsp;&nbsp;&nbsp;
    <input id="every_week" type="radio" name="interval" value="every_week">
    <label for="every_week">毎週</label>
    <br>
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
    <br><br>
    &nbsp;&nbsp;&nbsp;
    <input id="choice" type="radio" name="interval" value="choice">
    <label for="choice">選択</label>
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
    {{-- /now --}}
    <br><br>
    <select name="" id="">
        <option value="">プロジェクトを選択</option>
    </select>
    <br><br>
    <select name="" id="">
        <option value="">タグを選択</option>
    </select>
    <br><br>
    <button>キャンセル</button>
    <button>保存</button>
    <button>計画タスクとしてコピー</button>
    <button>削除</button>
</form>
@endsection
