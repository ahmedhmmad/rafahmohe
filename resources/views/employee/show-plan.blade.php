@extends('layouts.master')

@section('content')

    <div class="container py-4">
        {{-- Show errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li style="color: red;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <h3>تعديل الخطة الشهرية</h3>
                    </div>

                </div>


<div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>اليوم</th>
                        <th>التاريخ</th>
                        <th>المدارس</th>
                        <th>عمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $previousDate = null;
                        $currentDate = null;
                        $previousDayName = null;
                        $dayNames = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];
                        $currentDayName = null;
                    @endphp
                    @foreach ($plans as $index => $plan)
                        @php
                            $currentDate = $plan->start;
                            $currentDayName = $dayNames[date('N', strtotime($plan->start)) - 1];
                        @endphp

                        {{-- Display a row for working days with no schools --}}
                        @foreach ($workingDays as $workingDay)
                            @if ($workingDay > $previousDate && $workingDay < $currentDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)
                                <tr>
                                    <td>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</td>
                                    <td><strong>{{ $workingDay }}</strong></td>
                                    <td>لا توجد مدارس</td>
                                    <td>
                                        {{-- Add the appropriate action for adding schools --}}
                                        <a href="{{ route('employee.add-day', $workingDay) }}" class="btn btn-success">إضافة مدرسة</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        @if ($plan->schools)
                            <tr
                                @if ($previousDate !== $currentDate)
                                    style="border-top: 2px solid #487ebd;"
                        @elseif (isset($plans[$index + 1]) && $plans[$index + 1]->start === $currentDate)
                            style="border-top: 2px dot-dot-dash #000;"
                        @endif
                        >
                        <td>
                            @if ($currentDayName !== $previousDayName)
                                {{ $currentDayName }}
                            @endif
                        </td>
                        <td>
                            @if ($plan->start !== $previousDate)
                                <strong>{{ $plan->start }}</strong>
                            @endif
                        </td>
                        <td>{{ $plan->schools->name }}</td>
                        <td>
                            <a href="{{ route('employee.edit-plan', $plan->id) }}" class="btn btn-outline-primary">تعديل</a>
                            <a href="{{ route('employee.delete-plan', $plan->id) }}" class="btn btn-outline-danger">حذف</a>
                            <a href="{{ route('employee.add-day', $plan->start) }}" class="btn btn-outline-success">إضافة مدرسة</a>
                        </td>
                        </tr>
                        @endif

                        @php
                            $previousDate = $currentDate;
                            $previousDayName = $currentDayName; // Update the previous day name for the next iteration
                        @endphp
                    @endforeach

                    {{-- Add a row for working days after the last plan --}}
                    @foreach ($workingDays as $workingDay)
                        @if ($workingDay > $previousDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)
                            <tr>
                                <td>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</td>
                                <td><strong>{{ $workingDay }}</strong></td>
                                <td>لا توجد مدارس</td>
                                <td>
                                    {{-- Add the appropriate action for adding schools --}}
                                    <a href="{{ route('employee.add-day', $workingDay) }}" class="btn btn-success">إضافة مدرسة</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>

@endsection
