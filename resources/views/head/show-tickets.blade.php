@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">عرض التذاكر المقدمة</h2>
        <div class="container py-2">
            <div class="card px-2">
                <form method="GET" action="">
                    <div class="row py-4">
                        <div class="col-md-3">
                            <label for="status" class="form-label"><strong>تصنيف حسب الحالة</strong></label>
                            <select class="form-select" name="status" id="status" aria-label="Default select example">
                                <option value="">الكل</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>معينة</option>
                                <option value="on-progress" {{ request('status') == 'on-progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                            </select>
                        </div>
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
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">رقم الطلب</th>
                            <th scope="col">تاريخ الطلب</th>
                            <th scope="col"> المدرسة</th>
                            <th scope="col">موضوع الطلب</th>
                            <th scope="col">حالة الطلب</th>
                            <th scope="col">التعيين</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                <td>{{ $ticket->user->name }}</td>
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
{{--                                    <div class="row gy-3">--}}

{{--                                        <form action="{{ route('head.tickets.assign', $ticket->id) }}" method="POST">--}}
{{--                                            @csrf--}}
{{--                                            <div class="demo-inline-spacing">--}}
{{--                                                <button type="submit" name="assign_to" value="self" class="btn btn-primary">--}}
{{--                                                    <span class="tf-icons bx bx-magnet"></span>&nbsp; التعيين لنفسي--}}
{{--                                                </button>--}}
{{--                                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#basicModal">--}}
{{--                                                    <span class="tf-icons bx bx-export"></span>&nbsp; التعيين لموظف آخر--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        </form>--}}

{{--                                    </div>--}}

{{--                                    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">--}}
{{--                                        <div class="modal-dialog" role="document">--}}
{{--                                            <div class="modal-content">--}}
{{--                                                <div class="modal-header">--}}
{{--                                                    <h5 class="modal-title" id="exampleModalLabel1">تعيين موظف للمهمة</h5>--}}
{{--                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                                                </div>--}}
{{--                                                <form id="assignForm" method="POST" action="{{ route('head.tickets.assign', $ticket->id) }}">--}}
{{--                                                @csrf--}}
{{--                                                    <div class="modal-body">--}}
{{--                                                        <div class="row">--}}
{{--                                                            <div class="col mb-3">--}}
{{--                                                                <label for="userSelect" class="form-label">اختر موظفاً</label>--}}
{{--                                                                <select id="userSelect" name="user_id" class="form-select">--}}
{{--                                                                    @foreach($users as $user)--}}
{{--                                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>--}}
{{--                                                                    @endforeach--}}
{{--                                                                </select>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="modal-footer">--}}
{{--                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">الغاء</button>--}}
{{--                                                        <button type="submit" class="btn btn-primary">حفظ</button>--}}
{{--                                                    </div>--}}
{{--                                                </form>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <!-- Form for assigning the ticket to yourself -->
                                    <form action="{{ route('head.tickets.assign', $ticket->id) }}" method="POST" id="selfAssignForm">
                                        @csrf
                                        <div class="demo-inline-spacing">
                                            <button type="submit" name="assign_to" value="self" class="btn btn-primary">
                                                <span class="tf-icons bx bx-magnet"></span>&nbsp; التعيين لنفسي
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignModal-{{ $ticket->id }}">
                                                <span class="tf-icons bx bx-export"></span>&nbsp; التعيين لموظف آخر
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Modal for assigning the ticket to another user -->
                                    <div class="modal fade" id="assignModal-{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="assignModalLabel-{{ $ticket->id }}">تعيين موظف للمهمة</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('head.tickets.assign', $ticket->id) }}" method="POST" id="assignModalForm-{{ $ticket->id }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3">
                                                                <label for="userSelect-{{ $ticket->id }}" class="form-label">اختر موظفاً</label>
                                                                <select id="userSelect-{{ $ticket->id }}" name="user_id" class="form-select">
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">الغاء</button>
                                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


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
{{--                return 'جديدة';--}}
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
