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
                        <p class="login-card-description">تسجيل حساب جديد</p>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="sr-only">الاسم</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                       placeholder="الاسم">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="id" class="sr-only">رقم الهوية</label>
                                <input id="id" type="number" class="form-control @error('id') is-invalid @enderror"
                                       name="id" value="{{ old('id') }}" required placeholder="رقم الهوية">
                                @error('id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="sr-only">رقم الجوال</label>
                                <input id="phone" type="number" class="form-control @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone') }}" required autocomplete="phone"
                                       placeholder="رقم الجوال">
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="form-group">
{{--                                select department--}}
                                <label for="department_id" class="sr-only">القسم</label>
                                <select name="department_id" id="department_id" class="p-lg-0 form-control @error('department_id') is-invalid @enderror">

                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </select>
                                @error('department_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="sr-only">الايميل</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="الايميل">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="sr-only">كلمة المرور</label>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="new-password" placeholder="كلمة المرور">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="password" class="sr-only">تأكيد كلمة المرور</label>
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="تأكيد كلمة المرور">
                            </div>
                            <input name="register" id="register" class="btn btn-block login-btn mb-4" type="submit"
                                   value="تسجيل حساب جديد">
                        </form>
                        <a href="{{ route('password.request') }}" class="forgot-password-link">نسيت كلمة المرور؟</a>
                        <p class="login-card-footer-text">يوجد لدي حساب!<a href="{{ route('login') }}"
                                                                                     class="text-reset">تسجيل الدخول</a>
                        </p>
                        <nav class="login-card-footer-nav">
                            <a href="#!">مديرية التربية والتعليم رفح</a>
                            <a href="#!">قسم الحاسوب</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
