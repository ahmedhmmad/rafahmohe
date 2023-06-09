@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <img src="{{url('/img/login.jpg')}}" alt="login" class="login-card-img">
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <div class="brand-wrapper">
                            <img src="{{url('/img/logo1.png')}}" alt="logo" class="logo">
                        </div>
                        <p class="login-card-description">تأكيد كلمة المرور</p>
                        <form method="POST" action="{{ url('user/confirm-password') }}">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="password" class="sr-only">كلمة المرور</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <input name="confirm" id="confirm" class="btn btn-block login-btn mb-4" type="submit"
                                   value="Confirm">
                        </form>
                        <nav class="login-card-footer-nav">
                            <a href="#!">مديرية التربية والتعليم رفح</a>
                            <a href="#!">قسم الحاسوب</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
@endsection
