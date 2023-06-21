@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">عرض التذكرة</h2>
        <div class="container py-2">
            <div class="card px-2">
                <h4 class="p-2">تفاصيل التذكرة</h4>
                <table class="table">
                    <tr>
                        <th>رقم التذكرة:</th>
                        <td>{{ $ticket->id }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء:</th>
                        <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>الموضوع:</th>
                        <td>{{ $ticket->subject }}</td>
                    </tr>
                    <tr>
                        <th>الوصف:</th>
                        <td>{{ $ticket->description }}</td>
                    </tr>
                    <tr>
                        <th>المرفقات:</th>
                        <td>

                            @if (empty($ticket->attachment))

                            لا توجد مرفقات.
                            @else

                                <a href="{{ Storage::disk('local')->url('attachments/' . $ticket->attachment) }}" download>{{ $ticket->attachment }}</a>


                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الحالة:</th>
                        <td>
                            <span class="badge {{ getStatusStyle($ticket->status) }}">
                                {{ getStatusName($ticket->status) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <h4 class="p-2">تغيير حالة التذكرة</h4>
                <form action="{{ route('employee.tickets.changeStatus', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="status">تغيير الحالة:</label>
                        <select class="form-control" name="status" id="status">
                            <option value="on-progress">بدء التنفيذ</option>
                            <option value="closed">إغلاق</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </form>
            </div>
        </div>
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

