@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">عرض التذاكر</h2>
        <div class="container py-2">

            <div class="card px-2">
                @if($tickets->isEmpty())
                    <div class="p-4">لا توجد تذاكر متاحة.</div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                        <tr>
                            <th scope="col">رقم التذكرة</th>
                            <th scope="col">تاريخ الإنشاء</th>
                            <th scope="col">المدرسة</th>
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
                                            <button class="btn btn-warning delegate-ticket" data-bs-toggle="modal" data-bs-target="#delegateModal" data-ticket-id="{{ $ticket->ticket?->id }}">
                                                تخصيص لموظف مؤقت
                                            </button>
                                        <form action="{{ route('employee.tickets.changeStatus', $ticket->ticket?->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" name="status" value="closed">تغيير الحالة إلى مغلقة</button>
                                        </form>
                                    @endif


                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Delegate Modal -->
    <div class="modal fade" id="delegateModal" tabindex="-1" role="dialog" aria-labelledby="delegateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Remove the form tag -->
                <div class="modal-header">
                    <h5 class="modal-title" id="delegateModalLabel">تخصيص التذكرة لموظف مؤقت</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ticket_id" id="delegateTicketId">
                    <div class="form-group">
                        <label for="temp_employee">اختر موظف مؤقت من نفس القسم</label>
                        <select class="form-control" name="temp_employee_id" id="tempEmployee">
                            <!-- Populate this dropdown with temp employees from the same department -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <!-- Add an id attribute to the submit button -->
                    <button type="button" class="btn btn-primary" id="submitDelegateForm">تخصيص</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
            // Add the following JavaScript to initialize Bootstrap modal
            $(document).ready(function() {
            // Initialize Bootstrap modal
            var delegateModal = new bootstrap.Modal(document.getElementById('delegateModal'), {
            backdrop: 'static', // Prevents closing on backdrop click
            keyboard: false // Prevents closing when pressing Esc key
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
                            var tempEmployeeDropdown = $('#tempEmployee');

                            tempEmployeeDropdown.empty();

                            tempEmployees.forEach(function(tempEmployee) {
                                tempEmployeeDropdown.append('<option value="' + tempEmployee.id + '">' + tempEmployee.name + '</option>');
                            });

                            // Show the modal after loading content
                            delegateModal.show();
                        }
                    });
                });

                // Add a click event listener to the submit button
                $('#submitDelegateForm').click(function() {
                    // Get the form data
                    var formData = {
                        ticket_id: $('#delegateTicketId').val(),
                        temp_employee_id: $('#tempEmployee').val(),
                        _token: '{{ csrf_token() }}'
                    };

                    // Send an AJAX request to submit the form
                    $.ajax({
                        url: '{{ route('employee.delegate-ticket') }}',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            console.log(response);
                            // Handle the response here, for example, close the modal
                            delegateModal.hide();

                            // You can also handle other actions based on the response
                        }
                    });
                });
            });



    </script>
@endpush
