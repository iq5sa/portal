@extends('layouts.app')

@section('content')
    <form class="login-form" method="POST" action="{{ route('login') }}">
        <h5 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>تسجيل الدخول</h5>
        @csrf

        <div class="form-group">
            <label for="inputEmail">أسم المستخدم</label>
            <input type="email" name="email" id="inputEmail" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="أسم المستخدم"
                   required autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="inputPassword">كلمة المرور</label>
            <input type="password" name="password" id="inputPassword" class="form-control @error('password') is-invalid @enderror" placeholder="كلمة المرور"
                   required>
            @error('password')
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <button class="btn btn-primary btn-block" type="submit">تسجيل الدخول
        </button>
    </form>

@endsection
