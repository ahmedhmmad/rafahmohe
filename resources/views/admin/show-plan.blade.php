@extends('layouts.master')

@section('content')

    <div class="container">
        <div class="row">
            <div class="card mt-4 mx-2">
                <div class="col-md-12">
                    <h3 class="pt-2 "> عرض الخطة الشهرية:  <span class="text-info">{{$userName}}</span></h3>

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

@endsection
