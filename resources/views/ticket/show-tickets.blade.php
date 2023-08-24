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
                            <label for="department" class="form-label"><strong>تصنيف حسب القسم</strong></label>
                            <select class="form-select" name="department" id="department" aria-label="Default select example">
                                <option value="">الكل</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
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
                            <th scope="col">موضوع الطلب</th>
                            <th scope="col">حالة الطلب</th>
                            <th scope="col">تصدير</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>
                                    <a href="#" class="ticket-link" data-ticket-id="{{ $ticket->id }}" data-bs-toggle="modal" data-bs-target="#ticketModal-{{ $ticket->id }}">

                                        {{ $ticket->id }}
                                    </a>
                                </td>
                                <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>

                                  <span class="badge {{ getStatusStyle($ticket->status) }}">

                                            {{ getStatusName($ticket->status) }}
                                        </span>
                                </td>
                                <td>
                                    <a href="{{ route('school.export-ticket', ['ticketId' => $ticket->id]) }}">
                                       <i class="bx bx-download"></i>
                                    </a>
                                </td>

                                {{--                                    Modal for showing the ticket details--}}
                                <div class="modal fade" id="ticketModal-{{ $ticket->id }}" tabindex="-1" aria-labelledby="ticketModalLabel-{{ $ticket->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="ticketModalLabel-{{ $ticket->id }}">تفاصيل التذكرة رقم {{ $ticket->id }}</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <!-- Loading spinner -->
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <p><strong>تاريخ الطلب:</strong> {{ $ticket->created_at->format('Y-m-d') }}</p>
                                                <p><strong>المدرسة:</strong> {{ $ticket->user->name }}</p>
                                                <p><strong>موضوع الطلب:</strong> {{ $ticket->subject }}</p>
                                                <p><strong>القسم:</strong> {{ $ticket->department->name }}</p>
                                                <p><strong>حالة الطلب:</strong> <span class="badge {{ getStatusStyle($ticket->status) }}">{{ getStatusName($ticket->status) }}</span></p>
                                                <p><strong>منفذ الطلب:</strong> {{ $ticket->assignedUser->name ?? 'لم يتم التعيين بعد' }}</p>
                                                <p><strong>التعليقات</strong></p>
                                                <div id="comments-{{ $ticket->id }}"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </tr>
                        @endforeach


                        </tbody>
                    </table>

                </div>

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


        @endif
    </div>
@endsection

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
{{--        }--}}
{{--@endphp--}}


@push('scripts')
    <script>

        $(document).ready(function() {
            $('.ticket-link').click(function(e) {
                e.preventDefault();

                var ticketId = $(this).data('ticket-id');
                var spinner = $('#ticketModal-' + ticketId).find('.spinner-border');
                var commentsContainer = $('#comments-' + ticketId);

                // Show the loading spinner
                spinner.show();

                // Make an AJAX request to fetch the comments
                $.ajax({
                    url: '{{ route('school.tickets.comments') }}',
                    type: 'GET',
                    data: {
                        ticketId: ticketId
                    },
                    success: function(response) {
                        // Hide the loading spinner
                        spinner.hide();

                        commentsContainer.empty();

                        // Generate the list of comments
                        if (response.comments.length > 0) {
                            response.comments.forEach(function(comment) {
                                // Split the comment by new lines
                                var lines = comment.split('\n');

                                // Generate a list item for each line
                                lines.forEach(function(line) {
                                    if (line.trim() !== '') {
                                        commentsContainer.append('<li class="list-group-item">' + line + '</li>');
                                    }
                                });
                            });
                        }  else {
                            commentsContainer.html('<p>لا يوجد تعليقات.</p>');
                        }
                    },
                    error: function(xhr) {
                        // Hide the loading spinner
                        spinner.hide();

                        // Handle the error scenario if needed
                    }
                });
            });
        });

    </script>
@endpush

