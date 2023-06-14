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
            <div class="col d-none d-md-block">
                {{--                <div class="card">--}}
                <img class="figure-img img-fluid rounded" src="{{url('/img/login.webp')}}" alt="">
                {{--                </div>--}}
            </div>

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

                                <form id="formAuthentication" class="mb-3" action="{{route('login')}}" method="POST">
                                    @csrf
                                    <p class="card-text text-center">من فضلك، أدخل بيانات الدخول</p>
                                    <div class="mb-3">
                                        <label for="id" class="form-label">رقم الهوية</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="id"
                                            name="id"
                                            placeholder="رقم الهوية"
                                            autofocus
                                        />
                                    </div>
                                    <div class="mb-3 form-password-toggle">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="password">كلمة المرور</label>
                                            <a href="{{ route('password.request') }}">
                                                <small>نسيت كلمة المرور</small>
                                            </a>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <input
                                                type="password"
                                                id="password"
                                                class="form-control"
                                                name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password"
                                            />
                                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                        </div>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember-me" />
                                            <label class="form-check-label" for="remember-me"> تذكرني </label>
                                        </div>
                                    </div> --}}
                                    <div class="mb-3">
                                        <button class="btn btn-primary d-grid w-100" type="submit">تسجيل الدخول</button>
                                    </div>
                                </form>


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
