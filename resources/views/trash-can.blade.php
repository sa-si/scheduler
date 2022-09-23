@extends('layouts.app')

@section('content')
<form action="" method="POST">
  {{-- 復元・・・deleted_atを削除
       削除・・・物理削除 --}}
    <button>一括チェック</button>
    <button>復元</button>
    <button>削除</button>
    <table>
      <thead>
        <tr>
          <th>チェック</th>
          <th>タスク名</th>
          <th>作成日</th>
          <th>削除日</th>
          <th>計画or実績</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
          <input type="checkbox" name="" value="1">
          </td>
          <td>掃除</td>
          <td>2022/10/02(日)</td>
          <td>2022/11/7(月)</td>
          <td>計画</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
</form>
@endsection
