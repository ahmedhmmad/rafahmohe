@extends('layouts.master')

@section('content')

    <div class="container">
        <div class="row">
            <div class="card mt-4 mx-2">
                <div class="col-md-12">
                    <h3 class="pt-2 ">عرض الخطة الشهرية للموظف:  <span style="color:red"> {{$userName}}</span></h3>
                    <form method="GET" action="{{ route('head.monthly-plan') }}">
                        <input type="hidden" name="userId" value="{{ $userId }}">
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

                <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                        <tr>
                            <th style="font-weight: bold;color:#020e4f">اليوم</th>
                            <th style="font-weight: bold;color:#020e4f">التاريخ</th>
                            <th style="font-weight: bold;color:#020e4f">المدارس</th>
                            {{--                        <th>عمليات</th>--}}
                        </tr>
                        </thead>
                        <tbody>
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
{{--                                    <td>--}}
{{--                                        --}}{{-- Add the appropriate action for adding schools --}}
{{--                                        --}}{{-- <a href="{{ route('employee.add-day', $workingDay) }}" class="btn btn-success">إضافة مدرسة</a> --}}
{{--                                    </td>--}}
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
