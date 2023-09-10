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
                @endif
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
