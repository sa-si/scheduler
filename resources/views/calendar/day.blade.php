@extends('layouts.app')

@section('content')
<input type="hidden" value="{{ $form_path }}" id="form-path">
<div id="js_form-display" class="form"></div>
{!! $calendar->render() !!}
@endsection
