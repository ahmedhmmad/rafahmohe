@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <img src="/img/login.jpg" alt="login" class="login-card-img">
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <div class="brand-wrapper">
                            <img src="/img/logo.png" alt="logo" class="logo">
                        </div>
                        <p class="login-card-description">تسجيل الدخول</p>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="id" class="sr-only">رقم الهوية</label>
                                <input type="number" name="id" id="id" class="form-control is-invalid"
                                       placeholder="رقم الهوية">
                                @error('id')
                                <span class="invalid-feedback is-invalid" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="sr-only">كلمة المرور</label>
                                <input type="password" name="password" id="password" class="form-control"
                                       placeholder="***********">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit"
                                   value="تسجيل الدخول">
                        </form>
                        <a href="{{ route('password.request') }}" class="forgot-password-link">نسيت كلمة المرور؟</a>
                        <p class="login-card-footer-text">لا يوجد لدي حساب؟ <a href="{{ route('register') }}" class="text-reset">سجل الآن</a>
                        </p>

                        <nav class="login-card-footer-nav ">
                            <a href="#!">مديرية التربية والتعليم رفح</a>
                            <a href="#!">قسم الحاسوب</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
@endsection
