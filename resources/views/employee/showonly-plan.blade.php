@extends('layouts.master')

@section('content')

    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <h3>عرض الخطة الشهرية</h3>
                    </div>

                </div>

                <div class="card-body">
                <form method="GET" action="{{ route('employee.monthly-plan') }}">
                    <div class="row py-4">
                        <div class="col-md-4">
                            <label for="month" class="form-label"><strong>الشهر</strong></label>
                            <select class="form-select" name="month" id="month" aria-label="Default select example">
                                <option value="" selected>اختر الشهر</option>
                                <option value="1">يناير</option>
                                <option value="2">فبراير</option>
                                <option value="3">مارس</option>
                                <option value="4">ابريل</option>
                                <option value="5">مايو</option>
                                <option value="6">يونيو</option>
                                <option value="7">يوليو</option>
                                <option value="8">اغسطس</option>
                                <option value="9">سبتمبر</option>
                                <option value="10">اكتوبر</option>
                                <option value="11">نوفمبر</option>
                                <option value="12">ديسمبر</option>
                                <!-- Add options for the rest of the months -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year" class="form-label"><strong>السنة</strong></label>
                            <select class="form-select" name="year" id="year" aria-label="Default select example">
                                <option value="" selected>اختر السنة</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <!-- Add options for the desired years -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4">عرض</button>
                        </div>
                    </div>
                </form>
            </div>
                <a href="{{ route('exports.exportPlan', ['month' => now()->month, 'year' => now()->year]) }}" class="btn btn-success">
                    <i class="fas fa-file-export"></i>
                    تصدير الخطة الشهرية
                </a>
                <button class="btn btn-primary" onclick="printTable()">
                    <i class="fas fa-print"></i>
                    طباعة الخطة الشهرية
                </button>
                <div class="card mt-2">
                    <div class="card-body">

                        <table class="table" id="printTable">
                    <thead>
                    <tr>
                        <th>اليوم</th>
                        <th>التاريخ</th>
                        <th>المدارس</th>
                        {{-- <th>عمليات</th> --}}
                    </tr>
                    </thead>
                    <tbody>
{{--                    @php--}}
{{--                        $previousDate = null;--}}
{{--                        $currentDate = null;--}}
{{--                        $dayNames = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];--}}
{{--                    @endphp--}}
{{--                    @foreach ($plans as $plan)--}}
{{--                        @php--}}
{{--                            $currentDate = $plan->start;--}}
{{--                        @endphp--}}

{{--                        --}}{{-- Display a row for working days with no schools --}}
{{--                        @foreach ($workingDays as $workingDay)--}}
{{--                            @if ($workingDay > $previousDate && $workingDay < $currentDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)--}}
{{--                                <tr>--}}
{{--                                    <td>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</td>--}}
{{--                                    <td><strong>{{ $workingDay }}</strong></td>--}}
{{--                                    <td>لا توجد مدارس</td>--}}
{{--                                    <td>--}}
{{--                                        --}}{{-- Add the appropriate action for adding schools --}}
{{--                                        --}}{{-- <a href="{{ route('employee.add-day', $workingDay) }}" class="btn btn-success">إضافة مدرسة</a> --}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}

{{--                        @if ($plan->schools)--}}
{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    @if ($plan->start !== $previousDate)--}}
{{--                                        {{ $dayNames[date('N', strtotime($plan->start)) - 1] }}--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    @if ($plan->start !== $previousDate)--}}
{{--                                        <strong>{{ $plan->start }}</strong>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td>{{ $plan->schools->name }}</td>--}}
{{--                                <td>--}}
{{--                                    --}}{{-- <a href="{{ route('employee.edit-plan', $plan->id) }}" class="btn btn-primary">تعديل</a> --}}
{{--                                    --}}{{-- <a href="{{ route('employee.delete-plan', $plan->id) }}" class="btn btn-danger">حذف</a> --}}
{{--                                    --}}{{-- <a href="{{ route('employee.add-day', $plan->start) }}" class="btn btn-success">إضافة مدرسة</a> --}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}

{{--                        @php--}}
{{--                            $previousDate = $currentDate;--}}
{{--                        @endphp--}}
{{--                    @endforeach--}}
@php
    $previousDate = null;
    $currentDate = null;
    $dayNames = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];
    $schoolsString = '';
@endphp

@foreach ($plans as $plan)
    @php
        $currentDate = $plan->start;
    @endphp

    @if ($currentDate === $previousDate)
        @php
            $schoolsString .= ', ' . $plan->schools->name;
        @endphp
    @else
        @if ($previousDate !== null)
            <tr>
                <td>{{ $dayNames[date('N', strtotime($previousDate)) - 1] }}</td>
                <td><strong>{{ $previousDate }}</strong></td>
                <td>{{ $schoolsString }}</td>
            </tr>
        @endif

        @php
            $schoolsString = $plan->schools->name;
        @endphp
    @endif

    @php
        $previousDate = $currentDate;
    @endphp

@endforeach

@if ($previousDate !== null)
    <tr>
        <td>{{ $dayNames[date('N', strtotime($previousDate)) - 1] }}</td>
        <td><strong>{{ $previousDate }}</strong></td>
        <td>{{ $schoolsString }}</td>
    </tr>
@endif



{{-- Add a row for working days after the last plan --}}
                    @foreach ($workingDays as $workingDay)
                        @if ($workingDay > $previousDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)
                            <tr>
                                <td>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</td>
                                <td><strong>{{ $workingDay }}</strong></td>
                                <td>لا توجد مدارس</td>
                                <td>
                                    {{-- Add the appropriate action for adding schools --}}
                                    {{-- <a href="{{ route('employee.add-day', $workingDay) }}" class="btn btn-success">إضافة مدرسة</a> --}}
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

    </div>
    </div>


@endsection
