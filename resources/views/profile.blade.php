@extends('layouts.app')

@section('content')
    <div class="container-md mt-5">
        <form action="{{ route('user.update') }}" method="POST">
            @csrf

            <div class="d-flex justify-content-center">
                <div class="profile-input form-floating mb-3 flex-fill">
                    <input id="floatingName" type="text" name="name" value="{{ old('name', $user_profile->name) }}"
                        class="form-control" placeholder="ユーザー名を入力">
                    <label for="floatingName">ユーザー名</label>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <div class="profile-input form-floating mb-4 flex-fill">
                    <input type="email" name="email" value="{{ old('email', $user_profile->email) }}"
                        class="form-control" id="floatingEmail" placeholder="name@example.com">
                    <label for="floatingEmail">Eメール</label>
                </div>
            </div>

            <div class="d-flex justify-content-center"><button type="submit" class="btn btn-dark">変更する</button></div>
        </form>
    </div>
@endsection
