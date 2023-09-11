@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">عرض التذاكر المقدمة</h2>
        <div class="container py-2">
            <div class="row">
                <div class="col-md-3">
                    <div class="card px-2 text-center">
                        <div class="card-body">
                            <h5 class="card-title">الطلبات الجديدة</h5>
                            <p class="card-text">عدد الطلبات الجديدة:
                            <h1>
                            <span class="badge badge-center rounded-pill bg-label-success">
                            {{ $openTicketsCount }}
                            </span></h1>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card px-2 text-center">
                        <div class="card-body">
                            <h5 class="card-title">الطلبات التي تم تعيينها</h5>
                            <p class="card-text">عدد الطلبات قيد الانتظار:
                            <h1>
                            <span class="badge badge-center rounded-pill bg-label-info">

                            {{ $assignedTicketsCount }}
                            </span></h1>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card px-2 text-center">
                        <div class="card-body">
                            <h5 class="card-title">الطلبات قيد التنفيذ</h5>
                            <p class="card-text">عدد الطلبات قيد التنفيذ:
                            <h1>
                            <span class="badge badge-center rounded-pill bg-label-warning">

                            {{ $onProgressTicketsCount }}
                            </span></h1>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card px-2 text-center">
                        <div class="card-body">
                            <h5 class="card-title">الطلبات المغلقة</h5>
                            <p class="card-text">عدد الطلبات المغلقة:
                            <h1>
                            <span class="badge badge-center rounded-pill bg-label-danger">

                            {{ $closedTicketsCount }}
                            </span></h1>



                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container py-2">
            <div class="card px-2">
                <form method="GET" action="{{ route('head.tickets.filter') }}">
                    <div class="row py-4">
                        <div class="col-md-3">
                            <label for="status" class="form-label"><strong>تصنيف حسب الحالة</strong></label>
                            <select class="form-select" name="status" id="status" aria-label="Default select example">
                                <option value="">الكل</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>جديدة</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>معينة</option>
                                <option value="on-progress" {{ request('status') == 'on-progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                            </select>
                        </div>
                        @if (auth()->user()->department_id==16)
                        <div class="col-md-3">
                            <label for="work_type" class="form-label"><strong>تصنيف حسب نوع العمل</strong></label>
                            <select class="form-select" name="work_type" id="work_type" aria-label="Default select example">
                                <option value="">الكل</option>
                                <option value="سباكة" {{ request('work_type') == 'سباكة' ? 'selected' : '' }}>سباكة</option>
                                <option value="أعمال بناء" {{ request('work_type') == 'أعمال بناء' ? 'selected' : '' }}>أعمال بناء</option>
                                <option value="حدادة" {{ request('work_type') == 'حدادة' ? 'selected' : '' }}>حدادة</option>
                                <option value="كهرباء" {{ request('work_type') == 'كهرباء' ? 'selected' : '' }}>كهرباء</option>
                                <option value="ألمنيوم" {{ request('work_type') == 'ألمنيوم' ? 'selected' : '' }}>ألمنيوم</option>
                                <option value="أخرى" {{ request('work_type') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <label for="date" class="form-label"><strong>تصنيف حسب التاريخ</strong></label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);">تصفية</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($tickets->isEmpty())
            <div class="container py-2">
                <div class="card px-2">
                    <div class="p-4">لا توجد تذاكر مقدمة.</div>
                </div>
            </div>
        @else
            <div class="container py-2">
                <div class="card px-2">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                        <tr>
                            <th scope="col">رقم الطلب</th>
                            <th scope="col">تاريخ الطلب</th>
                            <th scope="col"> المدرسة</th>
                            @if (auth()->user()->department_id == 16)
                                <th scope="col">النوع</th>
                            @endif
                            <th scope="col">موضوع الطلب</th>

                            <th scope="col">حالة الطلب</th>
                            <th scope="col">الاجراء</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                <td>{{ $ticket->user->name }}</td>
                                @if (auth()->user()->department_id == 16)
                                    <td>{{ $ticket->work_type }}</td>
                                @endif
                                <td>{{ $ticket->subject }}</td>

                                <td>
                                    <span class="badge {{ getStatusStyle($ticket->status) }}">
                                        {{ getStatusName($ticket->status) }}
                                    </span>
                                    @if ($ticket->assigned_to && ($ticket->status == 'assigned'|| $ticket->status == 'on-progress'))
                                        <div class="assigned-employee mt-2">
                                            {{--                                            <span class="badge bg-primary">معينة لـ</span>--}}
                                            <span class="badge bg-secondary">{{ $assignedUserNames[$ticket->id] }}</span>

                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('employee.view-ticket', $ticket->id) }}" >
                                        <i class="bx bx-show me-2"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col">

                        <div class="demo-inline-spacing">
                            <!-- Basic Pagination -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item {{ $tickets->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $tickets->previousPageUrl() }}" aria-label="Previous">
                                            <i class="tf-icon bx bx-chevrons-right">السابق</i>
                                        </a>
                                    </li>
                                    @for($i = 1; $i <= $tickets->lastPage(); $i++)
                                        <li class="page-item {{ $tickets->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $tickets->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ $tickets->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $tickets->nextPageUrl() }}" aria-label="Next">
                                            <i class="tf-icon bx bx-chevron-left">التالي</i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <!--/ Basic Pagination -->
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.assign-select').change(function() {
                var selectedValue = $(this).val();
                var ticketId = $(this).data('ticket-id');
                var assignModal = $('.assign-modal[data-ticket-id="' + ticketId + '"]');

                if (selectedValue === 'self') {
                    assignModal.addClass('d-none');
                    // Perform self-assignment logic here
                } else if (selectedValue === 'others') {
                    assignModal.removeClass('d-none');
                }
            });

            $('.assign-submit').click(function() {
                var ticketId = $(this).closest('.assign-modal').data('ticket-id');
                var selectedEmployee = $(this).closest('.assign-modal').find('select').val();

                // Perform assignment logic here using the ticketId and selectedEmployee

                $(this).closest('.assign-modal').addClass('d-none');
            });
        });
    </script>
@endpush

{{--@php--}}
{{--    function getStatusStyle($status) {--}}
{{--        switch ($status) {--}}
{{--            case 'open':--}}
{{--                return 'bg-label-primary';--}}
{{--            case 'assigned':--}}
{{--                return 'bg-label-success';--}}
{{--            case 'on-progress':--}}
{{--                return 'bg-label-warning';--}}
{{--            case 'closed':--}}
{{--                return 'bg-label-danger';--}}
{{--            default:--}}
{{--                return '';--}}
{{--        }--}}
{{--    }--}}

{{--    function getStatusName($status) {--}}
{{--        switch ($status) {--}}
{{--            case 'open':--}}
{{--                return 'قيد الانتظار';--}}
{{--            case 'assigned':--}}
{{--                return 'تم التعيين';--}}
{{--            case 'on-progress':--}}
{{--                return 'قيد التنفيذ';--}}
{{--            case 'closed':--}}
{{--                return 'مغلقة';--}}
{{--            default:--}}
{{--                return '';--}}
{{--        }--}}
{{--    }--}}
{{--@endphp--}}
