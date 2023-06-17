<html class="light-style layout-menu-fixed mt-5" dir="rtl" lang="ar">
<head>


    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>مديرية التربية والتعليم رفح</title>

    <link rel="icon" type="image/x-icon" href="{{url('/img/favicon.ico')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    {{--    <link rel="stylesheet" href="{{url('/assets/vendor/fonts/boxicons.css')}}" />--}}

    <!-- RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{url('/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />


</head>


<body>
<div class="container-sm">
    <div class="row align-items-center">
            <div class="col">
            <div class="authentication-wrapper authentication-basic container-p-y">
                <div class="authentication-inner">
                    <!-- Register -->
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

                            <h4 class="card-title text-center">مديرية التربية والتعليم رفح</h4>

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <div class="form-group">
                                    <label for="email" class="sr-only">الايميل</label>
                                    <input type="email" name="email" id="" class="form-control is-invalid"
                                           value="{{ $request->email }}">
                                    @error('email')
                                    <span class="invalid-feedback is-invalid" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">كلمة المرور</label>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="new-password" placeholder="Password">
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
                                           placeholder="Confirm password">
                                </div>
                                <input name="reset" id="reset" class="btn btn-primary d-grid w-100" type="submit"
                                       value="استعادة كلمة المرور">
                            </form>
                            <a href="{{ route('password.request') }}" class="forgot-password-link">نسيت كلمة المرور؟</a>


                        </div>
                    </div>
                    <!-- /Register -->
                </div>
            </div>
        </div>



    </div>
</div>
<script src="{{url('/assets/vendor/js/bootstrap.js')}}"></script>

</body>
</html>




