@extends('layouts.app')

@section('content')
<h3>プロフィール</h3>
<form action="{{ route('user.update') }}" method="POST">
    @csrf

    <label for="name">ユーザー名</label><br>
    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}">
    <br>
    <label for="email">Eメール</label><br>
    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
    <br><br>
    <button type="submit">変更する</button>
</form>
@endsection
