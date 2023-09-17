@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">عرض التذكرة</h2>
        <div class="container py-2">
            <div class="card px-2">
                <h4 class="p-2"><span class="text-primary">تفاصيل التذكرة</span></h4>
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
                        <th>المدرسة:</th>
                        <td>{{ $ticket->user->name }}</td>
                    </tr>
                    <tr>
                        <th>الموضوع:</th>
                        <td>{{ $ticket->subject }}</td>
                    </tr>
                    <tr>
                        <th>الوصف:</th>
                        <td class="text-wrap" style="word-break: break-all;">{{ $ticket->description }}</td>
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
                    @if ($ticket->status === 'closed')
                    <tr>
                        <th>سبب الاغلاق:</th>
                        <td>
                            <span class="badge bg-label-info">
                               {{ getCloseReason($ticket->close_reason) }}
                            </span>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @if ($ticket->status === 'on-progress' || $ticket->status === 'closed')
            <div class="container py-2">
                <div class="card">
                    <h4 class="p-2"><span class="text-primary">التعليقات</span></h4>
                    @if ($ticket->ticketComments->count() > 0)
                        <ul class="list-group">
                            @foreach ($ticket->ticketComments->sortBy('created_at') as $comment)
                                <li class="list-group-item">
                                    <strong>{{ $comment->user->name }}</strong> ({{ $comment->user->job_title }})
                                    <small class="text-muted">{{ $comment->created_at }}</small>
                                    <p>{!! nl2br(e($comment->comment)) !!}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>لا يوجد تعليقات.</p>
                    @endif
                </div>
            </div>


    @php
        $canComment = ($ticket->status === 'on-progress' || auth()->user()->role_id == 2 || $ticket->status === 'closed');
    @endphp

    @if ($canComment)
                    <div class="container py-2">
                        <div class="card px-2">
                            <h4 class="p-2"><span class="text-primary">إضافة تعليق</span></h4>
                            <form action="{{ route('employee.tickets.addComment', $ticket->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <textarea class="form-control" name="comment" rows="3" placeholder="أضف تعليقًا"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">إرسال</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif

            <div class="container py-2">
                <div class="card bg-light">
                    @php
                        $canChangeStatus = (auth()->user()->role_id == 2 || auth()->user()->role_id == 1 || auth()->user()->role_id == 3);
                    @endphp

                    @if ($canChangeStatus)

                    <h4 class="p-2">تغيير حالة التذكرة</h4>
                    <form action="{{ route('employee.tickets.changeStatus', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <select class="form-select status-select" name="status" id="status">
                                    <option value="on-progress">بدء التنفيذ</option>
                                    <option value="closed">إغلاق</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-4 close-reason" style="display: none;">
                                <select class="form-select" name="close_reason" id="close_reason" onchange="checkForCustomReason(this)">
                                    <option value="work_completed">تم انجاز العمل</option>
                                    <option value="transferred_to_general_management">تم التحويل للادارة العامة</option>
                                    <option value="out_of_scope">خارج صلاحيات القسم</option>
                                    <option value="no_money">لا يوجد ميزانية</option>
                                    <option value="no_technician">لا يوجد فني مختص في القسم</option>
                                    <option value="custom">أخرى (أدخل السبب)</option>
                                </select>

                                <!-- Text input for custom reason -->

                                <input class="input-group-text mt-2" type="text" name="custom_close_reason" id="custom_close_reason" style="display: none; width: 250px;" placeholder="أدخل السبب من فضلك...">


                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">حفظ</button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('.status-select').change(function() {
                    if ($(this).val() === 'closed') {
                        $('.close-reason').show();
                    } else {
                        $('.close-reason').hide();
                    }
                });

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

            function checkForCustomReason(selectElement) {
                const customInput = document.getElementById('custom_close_reason');

                if (selectElement.value === 'custom') {
                    customInput.style.display = 'block';
                } else {
                    customInput.style.display = 'none';
                }
            }
        </script>
    @endpush

