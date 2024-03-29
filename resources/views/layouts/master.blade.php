<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="rtl"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
>

@include('dashboard.head')


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('dashboard.menu')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('dashboard.navbar')
            @if (session()->has('success') )
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="direction: rtl; background-color: lightgreen;"  data-bs-delay="1500" data-bs-animation="true" data-bs-autohide="true">

                <div class="toast-body">
                    {{ session('success')}}

                </div>
            </div>
                @endif

            @if (session()->has('error') )

             <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="direction: rtl; background-color: lightgreen;"  data-bs-delay="1500" data-bs-animation="true" data-bs-autohide="true">

                <div class="toast-body">
                    {{ session('error')}}

                </div>

            </div>
                @endif

            @foreach($errors as $error)
                <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="direction: rtl; background-color: lightgreen;"  data-bs-delay="1500" data-bs-animation="true" data-bs-autohide="true">

                    <div class="toast-body">
                        {{ $error}}

                    </div>
            @endforeach


                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

{{--                    @if($errors->any())--}}
{{--                        <div class="alert alert-danger">--}}
{{--                            <ul>--}}
{{--                                @foreach($errors->all() as $error)--}}
{{--                                    <li>{{ $error }}</li>--}}
{{--                                @endforeach--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    @endif--}}

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                @yield('content')
                <!-- / Content -->

                <!-- Footer -->
                @include('dashboard.footer')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->



<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
@include('dashboard.scripts')

</body>
</html>
