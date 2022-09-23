@extends('layouts.app')

@section('content')
<h3>プロフィール</h3>
<form action="" method="POST">
    <label for="name">ユーザー名</label><br>
    <input id="name" type="text" name="name" value="aaa">
    <br>
    <label for="email">Eメール</label><br>
    <input id="email" type="email" name="email" value="aaa">
    <br><br>
    <button type="submit">変更する</button>
</form>
@endsection
