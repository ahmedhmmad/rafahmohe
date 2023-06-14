@extends('layouts.template')

@section('content')
    <div class="container-sm">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Forgot Password -->
                <div class="row align-items-center">
                    <div class="col-lg-3 col-sm-1"></div>
                    <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{route('home')}}" class="app-brand-link gap-2">
                                    <span class="app-brand-logo">
                                        <img src="{{url('/img/logo.webp')}}" class="card-img-top">
                                    </span>
                                {{-- <span class="app-brand-text demo text-body fw-bolder">مديرية التربية والتعليم - رفح</span> --}}
                            </a>
                        </div>
                        <!-- /Logo -->

                        <h5 class="card-title text-center">مديرية التربية والتعليم رفح</h5>
                        <!-- /Logo -->
                        <h4 class="mb-2 text-center">نسيت كلمة المرور 🔒</h4>
                        <p class="mb-4 text-center">من فضلك، أدخل ايميلك لاستعادة كلمة المرور</p>
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-3" action="{{route('password.request')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">الايميل</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="أدخل ايميلك"
                                    autofocus
                                />
                            </div>
                            @error('email')
                            <span class="invalid-feedback is-invalid" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <button class="btn btn-primary d-grid w-100">أرسل رابط الاستعادة</button>
                        </form>
                        <div class="text-center">
                            <a href="{{route('home')}}" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                               العودة لصفحة الدخول
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
                    <div class="col-lg-3 col-sm-1"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
