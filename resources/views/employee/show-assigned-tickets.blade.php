@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">عرض التذاكر</h2>
        <div class="container py-2">

            <div class="card px-2">
                @if($tickets->isEmpty())
                    <div class="p-4">لا توجد تذاكر متاحة.</div>
                @else
                    <table id="ticketsTable" class="table table-bordered table-hover">                        <thead class="table-primary">
                        <tr>
                            <th scope="col">رقم التذكرة</th>
                            <th scope="col">تاريخ الإنشاء</th>
                            <th scope="col">المدرسة</th>
                            @if (auth()->user()->department_id == 16)
                                <th scope="col">النوع</th>
                            @endif
                            <th scope="col">الموضوع</th>
                            <th scope="col">الحالة</th>
                            <th scope="col">الإجراء</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket?->id }}</td>
                                <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                <td>{{ $ticket->ticket?->user?->name }}</td>
                                @if (auth()->user()->department_id == 16)
                                    <td>{{ $ticket->ticket?->work_type ?? 'غير محدد' }}</td>
                                @endif
                                <td>{{ $ticket->ticket?->subject }}</td>
                                <td>
                                        <span class="badge {{ getStatusStyle($ticket->ticket?->status) }}">
                                            {{ getStatusName($ticket->ticket?->status) }}
                                        </span>
                                </td>
                                <td>
                                    @if($ticket->status === 'closed')
                                        <a href="{{ route('employee.view-ticket', $ticket->ticket?->id) }}" >
                                            <i class="bx bx-show me-2"></i>
                                        </a>
                                    @endif

                                    @if($ticket->status === 'open')
                                        <a href="{{ route('employee.view-ticket', $ticket->ticket?->id) }}" class="btn btn-primary">فتح التذكرة</a>
                                    @elseif($ticket->status === 'assigned')
                                        <a href="{{ route('employee.view-ticket', $ticket->ticket?->id) }}" class="btn btn-primary">فتح التذكرة</a>
                                        <form action="{{ route('employee.tickets.changeStatus', $ticket->ticket?->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" name="status" value="on-progress">تغيير الحالة إلى قيد التنفيذ</button>
                                        </form>
                                    @elseif($ticket->status === 'on-progress')
                                        <a href="{{ route('employee.view-ticket', $ticket->ticket?->id) }}" class="btn btn-primary">فتح التذكرة</a>
                                            <button class="btn btn-info delegate-ticket" data-bs-toggle="modal" data-bs-target="#delegateModal" data-ticket-id="{{ $ticket->ticket?->id }}">
                                                تخصيص لموظف مؤقت
                                            </button>
{{--                                        <form action="{{ route('employee.tickets.changeStatus', $ticket->ticket?->id) }}" method="POST" style="display: inline-block;">--}}
{{--                                            @csrf--}}
{{--                                            <button type="submit" class="btn btn-danger" name="status" value="closed">تغيير الحالة إلى مغلقة</button>--}}
{{--                                        </form>--}}
                                    @endif


                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
        </div>
    </div>
    <!-- Delegate Modal -->
    <div class="modal fade" id="delegateModal" tabindex="-1" role="dialog" aria-labelledby="delegateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="delegateForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="delegateModalLabel">تخصيص التذكرة لموظف مؤقت</h5>

                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="ticket_id" id="delegateTicketId">
                        <div class="form-group">
                            <label for="temp_employee">اختر موظف للقيام بمهمة</label>
                            <div id="tempEmployeeCheckboxes">
                                <!-- Populate this dropdown with temp employees from the same department -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">تخصيص</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('#ticketsTable').DataTable({
                "searching": true, // Enable search functionality
                "lengthChange": false, // Hide the "Show X entries" dropdown
                "info": false, // Hide the table footer containing the table summary and pagination
                "paging": false, // Disable table pagination
                "language": {
                    "search": "البحث:",
                    "searchPlaceholder": "ابحث هنا..."
                }
            });


            var delegateModal = new bootstrap.Modal(document.getElementById('delegateModal'), {
                backdrop: 'static',
                keyboard: false
            });

            $('.delegate-ticket').click(function() {
                var ticketId = $(this).data('ticket-id');
                $('#delegateTicketId').val(ticketId);

                $.ajax({
                    url: '{{ route('employee.get-temp-employees') }}',
                    method: 'GET',
                    data: {
                        ticket_id: ticketId
                    },
                    success: function(response) {
                        var tempEmployees = response.tempEmployees;
                        var checkboxesContainer = $('#tempEmployeeCheckboxes');
                        checkboxesContainer.empty();

                        tempEmployees.forEach(function(tempEmployee) {
                            var checkboxHtml = `
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="temp_employee_ids[]" value="${tempEmployee.id}" id="tempEmployee${tempEmployee.id}">
            <label class="form-check-label" for="tempEmployee${tempEmployee.id}">
                ${tempEmployee.name}
            </label>
        </div>`;
                            checkboxesContainer.append(checkboxHtml);
                        });
                        $('#delegateModal').modal('show');
                    }
                });
            });
            $('#delegateModal').on('hidden.bs.modal', function (e) {
                console.log('Modal hidden event triggered.');
                $('#delegateModal').modal('hide');
            });

            $('#delegateForm').submit(function(e) {
                e.preventDefault();

                var ticketId = $('#delegateTicketId').val();
                var selectedTempEmployees = [];
                $("input[name='temp_employee_ids[]']:checked").each(function() {
                    selectedTempEmployees.push($(this).val());
                });

                var formData = {
                    ticket_id: ticketId,
                    temp_employee_ids: selectedTempEmployees,
                    _token: '{{ csrf_token() }}'
                };
                console.log("Delegate Ticket Form Data:", formData);

                $.ajax({
                    url: '{{ route('employee.delegate-ticket') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log("Delegate Ticket Response:", response);
                        $('#delegateModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error delegating ticket:", error);
                    }
                });
            });
        });
    </script>
@endpush
