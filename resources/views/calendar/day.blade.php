@extends('layouts.app')
@section('content')
<!-- day-table -->
<form action="#" method="POST">
    @csrf
    {!! $calendar->render() !!}
</form>
@endsection
