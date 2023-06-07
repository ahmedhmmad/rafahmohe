@extends('layouts.master')

@section('content')

    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <h3>عرض الخطة الشهرية</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المدارس</th>
                        <th>عمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $previousDate = null;
                        $currentDate = null;
                    @endphp
                    @foreach ($plans as $plan)
                        @php
                            $currentDate = $plan->start;
                        @endphp

                        {{-- Display a row for working days with no schools --}}
                        @foreach ($workingDays as $workingDay)
                            @if ($workingDay > $previousDate && $workingDay < $currentDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)
                                <tr>
                                    <td><strong>{{ $workingDay }}</strong></td>
                                    <td>لا توجد مدارس</td>
                                    <td>
                                        {{-- Add the appropriate action for adding schools --}}
                                        <a href="" class="btn btn-success">إضافة مدرسة</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        @if ($plan->school)
                            <tr>
                                <td>
                                    @if ($plan->start !== $previousDate)
                                        <strong>{{ $plan->start }}</strong>
                                    @endif
                                </td>
                                <td>{{ $plan->school->name }}</td>
                                <td>
                                    <a href="{{ route('employee.edit-plan', $plan->id) }}" class="btn btn-primary">تعديل</a>
                                    <a href="{{ route('employee.delete-plan', $plan->id) }}" class="btn btn-danger">حذف</a>
                                </td>
                            </tr>
                        @endif

                        @php
                            $previousDate = $currentDate;
                        @endphp
                    @endforeach

                    {{-- Add a row for working days after the last plan --}}
                    @foreach ($workingDays as $workingDay)
                        @if ($workingDay > $previousDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)
                            <tr>
                                <td><strong>{{ $workingDay }}</strong></td>
                                <td>لا توجد مدارس</td>
                                <td>
                                    {{-- Add the appropriate action for adding schools --}}
                                    <a href="" class="btn btn-success">إضافة مدرسة</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
