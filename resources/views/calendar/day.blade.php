@extends('layouts.app')

@section('content')
    <input type="hidden" value="{{ $form_path }}" id="form-path">
    <div id="js_form-display" class="form"></div>
    {!! $calendar->render() !!}
    <div class="modal micromodal-slide" id="modal-3" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1">
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-3-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-3-title">

                    </h2>
                    <button class="modal__close" aria-label="Close modal" type="button"
                        data-close-confirm="close-confirm"></button>
                </header>
                <main class="modal__content" id="modal-3-content">
                </main>
                <footer class="modal__footer">

                </footer>
            </div>
        </div>
    </div>
@endsection
