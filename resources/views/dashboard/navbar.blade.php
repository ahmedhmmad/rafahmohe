
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav-right d-flex align-items-center">
            <div class="nav-item d-flex align-items-center">
                <label class="form-label w-100 mb-0">
                    مديرية التربية والتعليم رفح
                </label>
            </div>
        </div>
        <!-- /Search -->
{{--        <script>--}}
{{--            const pusherAppKey = '{{ env("PUSHER_APP_KEY") }}';--}}
{{--            const pusherAppCluster = '{{ env("PUSHER_APP_CLUSTER") }}';--}}
{{--        </script>--}}

        <ul class="navbar-nav flex-row align-items-center mr-auto">

            <!-- Notifications -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bxs-bell-ring"></i>
                    @if(count(\Illuminate\Support\Facades\Auth::user()->unreadNotifications) > 0)
                        <span class="badge bg-label-danger">{{ count(\Illuminate\Support\Facades\Auth::user()->unreadNotifications) }}</span>
                    @endif
                </a>

                @if(count(\Illuminate\Support\Facades\Auth::user()->unreadNotifications) > 0)
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">

                        <!-- Your existing notification loop -->
                        @foreach(\Illuminate\Support\Facades\Auth::user()->unreadNotifications as $notification)
                            @php
                                $data = $notification->data['data'];
                            @endphp
                            <a class="dropdown-item" href="{{ $data['ticketId'] }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ $data['ticketSchoolName'] }}</span>
                                        <small class="text-muted">{{ $data['ticketSubject'] }}</small>
                                    </div>

                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item" data-bs-toggle="tooltip"
                                                title="تحديد كمقروء"
                                                data-bs-offset="0,4"
                                                data-bs-placement="top">
                                            <i class="bx bx-check me-2"></i>
                                            <span class="align-middle"></span>
                                        </button>
                                    </form>
                                </div>
                            </a>
                        @endforeach

                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bx bx-check me-2"></i>
                                <span class="align-middle">
                    <strong>
                        تعليم الكل كمقروء
                    </strong>
                </span>
                            </button>
                        </form>

                    </div>
                @endif
            </li>
            <!--/ Notifications -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{url('/assets/img/avatars/1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{url('/assets/img/avatars/1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{\Illuminate\Support\Facades\Auth::user()->name}}</span>
                                    <small class="text-muted">{{\Illuminate\Support\Facades\Auth::user()->department->name}}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('auth.change-password')}}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">حسابي</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">الاعدادت</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('auth.logout')}}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">تسجيل الخروج</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->


        </ul>
    </div>
</nav>
