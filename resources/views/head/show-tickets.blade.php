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
                    <table class="table">
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
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary">
التعيين لنفسي                                    </button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                                        التعيين لموظف آخر

                                    </button>
                                    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel1">تعيين موظف للمهمة</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="userSelect" class="form-label">اختر موظفاً</label>
                                                            <select id="userSelect" class="form-select">
                                                               @foreach($users as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                               @endforeach
                                                                <!-- Add the list of users here -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">الغاء</button>
                                                    <button type="button" class="btn btn-primary">حفظ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    {{--                                    <div class="assign-modal d-none" data-ticket-id="{{ $ticket->id }}">--}}
{{--                                        <select class="form-select">--}}
{{--                                            <option value="">اختر موظفًا آخر</option>--}}
{{--                                            <!-- Add the list of department employees here -->--}}
{{--                                        </select>--}}
{{--                                        <button class="btn btn-primary assign-submit">تعيين</button>--}}
{{--                                    </div>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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

@php
    function getStatusStyle($status) {
        switch ($status) {
            case 'open':
                return 'bg-label-primary';
            case 'assigned':
                return 'bg-label-success';
            case 'on-progress':
                return 'bg-label-warning';
            case 'closed':
                return 'bg-label-danger';
            default:
                return '';
        }
    }

    function getStatusName($status) {
        switch ($status) {
            case 'open':
                return 'قيد الانتظار';
            case 'assigned':
                return 'تم التعيين';
            case 'on-progress':
                return 'قيد التنفيذ';
            case 'closed':
                return 'مغلقة';
            default:
                return '';
        }
    }
@endphp
