<div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
    {{-- <input type="hidden" value="{{ $id }}" id="modal-id"> --}}
    <form action="{{ route('task') }}" method="POST" name="task">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->id ? $task->id : '' }}">
        <div class="modal__overlay" tabindex="-1" id="testes" data-close-confirm="close-confirm">
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        Micromodal
                    </h2>
                    <button class="modal__close" aria-label="Close modal" type="button"
                        data-close-confirm="close-confirm"></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    {{-- タスク名 --}}
                    <input type="text" name="name" value="{{ old('name', $task ? $task->name : '') }}"
                        placeholder="タスク名を入力" id="first_focus">
                    <br><br>
                    {{-- 説明 --}}
                    <textarea name="description" id="" cols="30" rows="10"
                        placeholder="説明を入力">{{ old('description', $task ? $task->description : '') }}</textarea>
                    <br><br>
                    {{-- 日 --}}
                    <input type="date" name="date" value="{{ old('date', $task ? $task->date : '1800-01-01') }}">
                    {{-- 開始時間 --}}
                    <input type="time" name="one_day_start_time"
                        value="{{ old('one_day_start_time', $task ? substr($task->start_time, 11, 5) : '00:00') }}">
                    {{-- 終了時間 --}}
                    <input type="time" name="one_day_end_time"
                        value="{{ old('one_day_end_time', $task ? substr($task->end_time, 11, 5) : '00:00') }}">
                    <div class="js_initial-disable-wrapper">
                        <input id="old_project" type="radio" name="project_choice" value="old"
                            onclick="globalFunctions.toggleEnableAndDisable(['select_project'], ['create_project'])" {{
                            old('project_choice', 'old' )==='old' ? 'checked' : '' }}>
                        <label for="old_project">既存プロジェクト選択</label>
                        <div id="select_project" class="js_initial-disable">
                            <select name="project">
                                <option value="{{ $registered_project->id ?? '' }}">{{ $registered_project->name ??
                                    '-------------' }}
                                </option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <div class="js_initial-disable-wrapper">
                        <input id="new_project" type="radio" name="project_choice" value="new"
                            onclick="globalFunctions.toggleEnableAndDisable(['create_project'], ['select_project'])" {{
                            old('project_choice')=='new' ? 'checked' : '' }}>
                        <label for="new_project">新規プロジェクト作成</label>
                        <div id="create_project" class="js_initial-disable">
                            <input type="text" name="project" value="{{ old('project') }}" placeholder="プロジェクト名を入力"
                                disabled>
                        </div>
                    </div>
                    <br><br>
                    @foreach ($tags as $tag)
                    <input id="{{ $tag->id }}" type="checkbox" name="tags[]" value="{{ $tag->id }}" {{
                        in_array($tag->id,
                    $associated_tags) ? 'checked' : '' }}>
                    <label for="{{ $tag->id }}">{{ $tag->name }}</label>
                    @endforeach
                    <input type="text" name="new_tag" value="" placeholder="タグ名を入力">
                    <br><br>
                    <a href="{{ route('task.destroy', ['id' => $task->id ])}}" id="task_destroy">削除</a>
                    <button>実績タスクとしてコピー</button>
                </main>
                <footer class="modal__footer">
                    <button type="button" class="modal__btn modal__btn-primary" id="js_form-submit">保存</button>

                    <button type="button" class="modal__btn" aria-label="Close this dialog window"
                        data-close-confirm="close-confirm">キャンセル</button>
                </footer>
            </div>
        </div>
    </form>
</div>

<div class="modal micromodal-slide" id="modal-2" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1">
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-2-title">
            <header class="modal__header">
                <h2 class="modal__title" id="modal-2-title">
                    Micromodal
                </h2>
            </header>
            <main class="modal__content" id="modal-2-content">
                <p>
                    保存されていない変更を破棄しますか？
                </p>
            </main>
            <footer class="modal__footer">
                <button class="modal__btn" data-micromodal-close>キャンセル</button>
                <button class="modal__btn modal__btn-primary" aria-label="Close this dialog window"
                    id="form_destruction">破棄</button>
            </footer>
        </div>
    </div>
</div>
{{--
<div class="modal micromodal-slide" id="modal-3" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1">
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-3-title">
            <header class="modal__header">
                <h2 class="modal__title" id="modal-3-title">
                    Micromodal
                </h2>
                <button class="modal__close" aria-label="Close modal"></button>
            </header>
            <main class="modal__content" id="modal-3-content">
                <p>
                    保存されていない変更を破棄しますか？
                </p>
            </main>
            <footer class="modal__footer">

                <button type="button" class="modal__btn modal__btn-primary">キャンセル</button>
                <button type="button" class="modal__btn" data-micromodal-close
                    aria-label="Close this dialog window">破棄</button>
            </footer>
        </div>
    </div>
</div> --}}

{{-- <form action="{{ route('p-task.update') }}" method="POST">
    @csrf
    <input type="text" name="name" value="{{ old('name', $task->name ? $task->name : '') }}" placeholder="タスク名を入力">
    <br><br>
    <textarea name="description" id="" cols="30" rows="10"
        placeholder="説明を入力">{{ old('description', $task->description) }}</textarea>
    <br><br>

    <div class="js_initial-disable-wrapper">
        <input id="one_day" type="radio" name="date_time" value="one_day"
            onclick="toggleEnableAndDisable(['one_day_inputs'], ['multi_day_inputs', 'every_day_inputs', 'every_week_inputs'])"
            {{ old('date_time', 'one_day' )==='one_day' ? 'checked' : '' }}>
        <label for="one_day">1日</label>
        <div id="one_day_inputs" class="js_initial-disable">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="date" name="date" value="{{ old('date', $task->date) }}">
            <input type="time" name="one_day_start_time"
                value="{{ old('one_day_start_time', substr($task->start_time, 11, 5)) }}">~
            <input type="time" name="one_day_end_time"
                value="{{ old('one_day_end_time', substr($task->end_time, 11, 5)) }}">
        </div>
    </div>

    <br><br>
    <br><br>
    <div class="js_initial-disable-wrapper">
        <input id="old_project" type="radio" name="project_choice" value="old"
            onclick="toggleEnableAndDisable(['select_project'], ['create_project'])" {{ old('project_choice', 'old'
            )==='old' ? 'checked' : '' }}>
        <label for="old_project">既存プロジェクト選択</label>
        <div id="select_project" class="js_initial-disable">
            <select name="project">
                <option value="{{ $registered_project->id ?? '' }}">{{ $registered_project->name ?? '-------------' }}
                </option>
                @foreach ($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br><br>
    <div class="js_initial-disable-wrapper">
        <input id="new_project" type="radio" name="project_choice" value="new"
            onclick="toggleEnableAndDisable(['create_project'], ['select_project'])" {{ old('project_choice')=='new'
            ? 'checked' : '' }}>
        <label for="new_project">新規プロジェクト作成</label>
        <div id="create_project" class="js_initial-disable">
            <input type="text" name="project" value="{{ old('project') }}" placeholder="プロジェクト名を入力">
        </div>
    </div>
    <br><br>
    @foreach ($tags as $tag)
    <input id="{{ $tag->id }}" type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id,
    $associated_tags) ? 'checked' : '' }}>
    <label for="{{ $tag->id }}">{{ $tag->name }}</label>
    @endforeach
    <input type="text" name="new_tag" value="" placeholder="タグ名を入力">
    <br><br>
    <button type="button" onclick="history.back()">戻る</button>
    <a href="{{ route('p-task.destroy', ['id' => $task->id ])}}">削除</a>
    <button>キャンセル</button>
    <button type="submit">登録</button>
    <button>実績タスクとしてコピー</button>
</form> --}}
