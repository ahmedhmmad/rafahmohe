@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <h3>عرض الخطة الشهرية</h3>

                <form method="GET" action="{{ route('employee.monthly-plan') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="month" class="form-label"><strong>الشهر</strong></label>
                            <select class="form-select" name="month" id="month" aria-label="Month">
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

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year" class="form-label"><strong>السنة</strong></label>
                            <select class="form-select" name="year" id="year" aria-label="Default select example">
                                <option selected>اختر السنة</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4">عرض</button>
                        </div>
                    </div>
                </form>

{{--                <table class="table table-bordered table-hover">--}}
{{--                    <thead class="table-primary">--}}
{{--                    <tr>--}}
{{--                        <th>اليوم</th>--}}
{{--                        <th>التاريخ</th>--}}
{{--                        <th>المدارس</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @php--}}
{{--                        $previousDate = null;--}}
{{--                        $currentDate = null;--}}
{{--                        $schoolsString = '';--}}
{{--                        $dayNames = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];--}}
{{--                    @endphp--}}
{{--                    @foreach ($plans as $plan)--}}
{{--                        @php--}}
{{--                            $currentDate = $plan->start;--}}
{{--                        @endphp--}}

{{--                        @if ($currentDate === $previousDate)--}}
{{--                            @php--}}
{{--                                $schoolsString .= ', ' . $plan->schools->name;--}}
{{--                            @endphp--}}
{{--                        @else--}}
{{--                            @if ($previousDate !== null)--}}
{{--                                <tr>--}}
{{--                                    <td>{{ $dayNames[date('N', strtotime($previousDate)) - 1] }}</td>--}}
{{--                                    <td>{{ $previousDate }}</td>--}}
{{--                                    <td>{{ $schoolsString }}</td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}

{{--                            @php--}}
{{--                                $schoolsString = $plan->schools->name;--}}
{{--                            @endphp--}}
{{--                        @endif--}}

{{--                        @php--}}
{{--                            $previousDate = $currentDate;--}}
{{--                        @endphp--}}
{{--                    @endforeach--}}

{{--                    @if ($previousDate !== null)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $dayNames[date('N', strtotime($previousDate)) - 1] }}</td>--}}
{{--                            <td>{{ $previousDate }}</td>--}}
{{--                            <td>{{ $schoolsString }}</td>--}}
{{--                        </tr>--}}
{{--                    @endif--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                    <tr>
                        <th><strong>اليوم</strong></th>
                        <th><strong>التاريخ</strong></th>
                        <th> <strong>المدارس</strong></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $dayNames = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];
                    @endphp

                    @foreach ($workingDays as $workingDay)
                        @php
                            $schoolsString = '';

                            // Find schools for the current working day
                            $schoolsForDay = $plans->where('start', $workingDay)->pluck('schools.name')->toArray();

                            if (!empty($schoolsForDay)) {
                                $schoolsString = implode(', ', $schoolsForDay);
                            }
                        @endphp

                        <tr>
                            <td>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</td>
                            <td>{{ $workingDay }}</td>
                            <td>
                                @if ($schoolsString)
                                    {{ $schoolsString }}
                                @else
                                    لا توجد مدارس
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
