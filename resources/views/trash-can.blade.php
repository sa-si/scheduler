@extends('layouts.app')

@section('javascript')
<script src="/js/index.js"></script>
@endsection
@section('content')
<form action="{{ route('trash-can') }}" method="POST">
  @csrf
  {{-- 復元・・・deleted_atを削除
       削除・・・物理削除 --}}
    <input type="checkbox" id="js_batch-check-button">一括チェック
    <button type="submit" name="restore" value="1">復元</button>
    <button type="submit" name="destroy" value="1">削除</button>
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
      @if (count($tasks) === 0)
          <tr><td>削除したタスクがありません。</td></tr>
      @else
          @foreach ($tasks as $task)
              <tr>
                  <td><input type="checkbox" name="{{ $task->type }}[]" value="{{ $task->id }}" class="js_batch-check-target"></td>
                  <td>{{ $task->name }}</td>
                  <td>{{ substr($task->created_at, 0, 10) }}</td>
                  <td>{{ substr($task->deleted_at, 0 , 10) }}</td>
                  <td>{{ $task->type === 'plans' ? '計画' : '実績' }}</td>
              </tr>
          @endforeach
      @endif
      </tbody>
    </table>
</form>
@endsection
