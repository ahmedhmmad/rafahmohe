@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <img src="{{url('/img/login.jpg')}}"  alt="login" class="login-card-img">
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <div class="brand-wrapper">
                            <img src="{{url('/img/logo1.png')}}" alt="logo" class="logo">
                        </div>
                        <p class="login-card-description">استعادة كلمة المرور</p>
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.request') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="sr-only">الايميل</label>
                                <input type="email" name="email" id="" class="form-control is-invalid"
                                       placeholder="Email address">
                                @error('email')
                                <span class="invalid-feedback is-invalid" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <input name="reset" id="reset" class="btn btn-block login-btn mb-4" type="submit"
                                   value="استعادة">
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
