@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header header-auth bg-dark text-white">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="d-flex justify-content-center mt-3">
                                <div class="profile-input form-floating mb-3 flex-fill">
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" id="floatingName" required
                                        autocomplete="name" autofocus>
                                    <label for="floatingName">名前</label>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="profile-input form-floating mb-3 flex-fill">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror" id="floatingEmail"
                                        required autocomplete="email">
                                    <label for="floatingEmail">Eメール</label>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="profile-input form-floating mb-3 flex-fill">
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="floatingPassword"
                                        required autocomplete="new-password">
                                    <label for="floatingPassword">パスワード</label>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="profile-input form-floating mb-3 flex-fill">
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="floatingPasswordConfirm" required autocomplete="new-password">
                                    <label for="floatingPasswordConfirm">パスワード(確認)</label>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-auth bg-dark text-white">
                                        {{ __('Register') }}
                                    </button>
                                    @if (Route::has('login'))
                                        <a href="{{ route('login') }}"
                                            class="text-sm text-gray-700 dark:text-gray-500 underline px-3 text-reset">ログイン画面はこちら</a>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
