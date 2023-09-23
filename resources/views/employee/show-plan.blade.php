{{--@extends('layouts.master')--}}

{{--@section('content')--}}

{{--    <div class="container py-4">--}}
{{--        --}}{{-- Show errors --}}
{{--        @if ($errors->any())--}}
{{--            <div class="alert alert-danger">--}}
{{--                <ul style="list-style: none;">--}}
{{--                    @foreach ($errors->all() as $error)--}}
{{--                        <li style="color: red;">{{ $error }}</li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="card px-4">--}}
{{--                    <div class="card-body">--}}
{{--                        <h3>تعديل الخطة الشهرية</h3>--}}
{{--                    </div>--}}

{{--                </div>--}}

{{--                <div class="card mt-2">--}}
{{--                    <div class="card-body">--}}


{{--                <table class="table table-hover">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th><strong>اليوم </strong></th>--}}
{{--                        <th><strong>التاريخ</strong></th>--}}
{{--                        <th><strong>المدارس</strong></th>--}}
{{--                        <th><strong>عمليات </strong></th>--}}

{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @php--}}
{{--                        $previousDate = null;--}}
{{--                        $currentDate = null;--}}
{{--                        $previousDayName = null;--}}
{{--                        $dayNames = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];--}}
{{--                        $currentDayName = null;--}}
{{--                    @endphp--}}
{{--                    @foreach ($plans as $index => $plan)--}}
{{--                        @php--}}
{{--                            $currentDate = $plan->start;--}}
{{--                            $currentDayName = $dayNames[date('N', strtotime($plan->start)) - 1];--}}
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
{{--                                        <a href="{{ route('employee.add-day', $workingDay) }}" class="btn btn-success">إضافة مدرسة</a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}

{{--                        @if ($plan->schools)--}}
{{--                            <tr--}}
{{--                                @if ($previousDate !== $currentDate)--}}
{{--                                    style="border-top: 2px solid rgba(143,187,178,0.85);"--}}
{{--                        @elseif (isset($plans[$index + 1]) && $plans[$index + 1]->start === $currentDate)--}}
{{--                            style="border-top: 2px dot-dot-dash rgba(143,187,178,0.18);"--}}
{{--                        @endif--}}
{{--                        >--}}
{{--                        <td>--}}
{{--                            @if ($currentDayName !== $previousDayName)--}}
{{--                                {{ $currentDayName }}--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            @if ($plan->start !== $previousDate)--}}
{{--                                <strong>{{ $plan->start }}</strong>--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                        <td>{{ $plan->schools->name }}</td>--}}
{{--                        <td>--}}
{{--                            <a href="{{ route('employee.edit-plan', $plan->id) }}" class="btn btn-outline-primary">تعديل</a>--}}
{{--                            <a href="{{ route('employee.delete-plan', $plan->id) }}" class="btn btn-outline-danger">حذف</a>--}}
{{--                            <a href="{{ route('employee.add-day', $plan->start) }}" class="btn btn-outline-success">إضافة</a>--}}
{{--                        </td>--}}
{{--                        </tr>--}}
{{--                        @endif--}}

{{--                        @php--}}
{{--                            $previousDate = $currentDate;--}}
{{--                            $previousDayName = $currentDayName; // Update the previous day name for the next iteration--}}
{{--                        @endphp--}}
{{--                    @endforeach--}}

{{--                    --}}{{-- Add a row for working days after the last plan --}}
{{--                    @foreach ($workingDays as $workingDay)--}}
{{--                        @if ($workingDay > $previousDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</td>--}}
{{--                                <td><strong>{{ $workingDay }}</strong></td>--}}
{{--                                <td>لا توجد مدارس</td>--}}
{{--                                <td>--}}
{{--                                    --}}{{-- Add the appropriate action for adding schools --}}
{{--                                    <a href="{{ route('employee.add-day', $workingDay) }}" class="btn btn-success">إضافة مدرسة</a>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    </div>--}}

{{--@endsection--}}

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

                <div class="card mt-2">
                    <div class="card-body">

                        <form method="GET" action="{{ route('employee.show-plan') }}">
                            @csrf

                            <div class="row py-4">
                                <div class="col-md-4">
                                    <label for="month" class="form-label"><strong>الشهر</strong></label>
                                    <select class="form-select" name="month" id="month" aria-label="Default select example">
                                        @php
                                            $arabicMonths = [
                                                'يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو',
                                                'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
                                            ];
                                        @endphp

                                        @foreach (range(1, 12) as $monthNumber)
                                            <option value="{{ $monthNumber }}" @if ($selectedMonth == $monthNumber) selected @endif>
                                                {{ $arabicMonths[$monthNumber - 1] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="year" class="form-label"><strong>السنة</strong></label>
                                    <select class="form-select" name="year" id="year" aria-label="Default select example">
                                        @foreach (range(now()->year - 3, now()->year + 3) as $year)
                                            <option value="{{ $year }}" @if ($selectedYear == $year) selected @endif>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary mt-4 px-lg-4">عرض</button>
                                </div>
                            </div>
                        </form>

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th><strong>اليوم</strong></th>
                                <th><strong>التاريخ</strong></th>
                                <th><strong>المدارس</strong></th>
                                <th><strong>عمليات</strong></th>
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
                                            style="border-top: 2px solid rgba(143,187,178,0.85);"
                                        @elseif (isset($plans[$index + 1]) && $plans[$index + 1]->start === $currentDate)
                                            style="border-top: 2px dot-dot-dash rgba(143,187,178,0.18);"
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
                                            <a href="{{ route('employee.add-day', $plan->start) }}" class="btn btn-outline-success">إضافة</a>
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
    </div>

@endsection
