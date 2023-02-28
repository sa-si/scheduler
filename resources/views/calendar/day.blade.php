@extends('layouts.app')

@section('content')
    <input type="hidden" value="{{ url(route('form')) }}" id="form-path">
    <input type="hidden" id="js_calendar_type" value="{{ $calendar_type }}">
    <input type="hidden" id="js_current_url" value="{{ url()->full() }}">
    @if ($calendar_type === 'month' || $calendar_type === 'year')
        <input type="hidden" id="js_day_calendar_path" value="{{ route('day') }}">
    @endif
    <div id="js_form-display" class="form"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="card border-0">
                <div class="calendar">{!! $calendar->render() !!}</div>
            </div>
        </div>
    </div>
    <div class="modal font-inherit micromodal-slide" id="modal-3" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-close-confirm="close-confirm">
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-3-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-3-title"></h2>
                    <button class="modal__close ms-5" aria-label="Close modal" type="button"
                        data-close-confirm="close-confirm"></button>
                </header>
                <main class="modal__content mt-4 mb-0" id="modal-3-content">
                </main>
                <footer class="modal__footer">

                </footer>
            </div>
        </div>
    </div>
@endsection
