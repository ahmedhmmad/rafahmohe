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
                            <th scope="col">القسم</th>
                            <th scope="col">حالة الطلب</th>
                            <th scope="col">التعيين</th>
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
                                <td>{{ $ticket->user->name }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ $ticket->department->name }}</td>
                                <td>
                                    <span class="badge {{ getStatusStyle($ticket->status) }}">
                                        {{ getStatusName($ticket->status) }}
                                    </span>
                                </td>
                                <td>

                                    <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST" id="selfAssignForm">
                                        @csrf
                                        <div class="demo-inline-spacing">
                                            {{--                                            <button type="submit" name="assign_to" value="self" class="btn btn-primary">--}}
                                            {{--                                                <span class="tf-icons bx bx-magnet"></span>&nbsp; التعيين لنفسي--}}
                                            {{--                                            </button>--}}
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignModal-{{ $ticket->id }}">
                                                <span class="tf-icons bx bx-export"></span>&nbsp; التعيين لقسم
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Modal for assigning the ticket to another user -->
                                    <div class="modal fade" id="assignModal-{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="assignModalLabel-{{ $ticket->id }}">تعيين قسم للمهمة</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST" id="assignModalForm-{{ $ticket->id }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3">
                                                                <label for="userSelect-{{ $ticket->id }}" class="form-label">اختر قسماً</label>
                                                                <select id="userSelect-{{ $ticket->id }}" name="department_id" class="form-select">
                                                                    @foreach($departments as $department)
                                                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
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
                    url: '{{route('admin.tickets.comments')}}',
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

