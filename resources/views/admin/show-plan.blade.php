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
                        @endphp
                        @foreach ($plans as $plan)
                            @php
                                $currentDate = $plan->start;
                            @endphp

                            {{-- Display a row for working days with no schools --}}
                            @foreach ($workingDays as $workingDay)
                                @if ($workingDay > $previousDate && $workingDay < $currentDate && date('N', strtotime($workingDay)) != 5 && date('N', strtotime($workingDay)) != 6)
                                    <tr>
                                        <td><strong>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</strong></td>
                                        <td>{{ date('Y/m/d', strtotime($workingDay)) }}</td>
                                        <td>لا توجد مدارس</td>
                                        <td>
                                            {{-- Add the appropriate action for adding schools --}}
{{--                                            <a href="" class="btn btn-success">إضافة مدرسة</a>--}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if ($plan->schools)
                                <tr>
                                    <td>
                                        @if ($plan->start !== $previousDate)
                                            <strong>{{ $dayNames[date('N', strtotime($plan->start)) - 1] }}</strong>
                                        @endif
                                    </td>
                                    <td>{{ date('Y/m/d', strtotime($plan->start)) }}</td>
                                    <td>{{ $plan->schools->name }}</td>
                                    <td>
                                        {{-- Add the appropriate action for adding schools --}}
                                        {{--                                    <a href="" class="btn btn-primary">تعديل</a>--}}
                                        {{--                                    <a href="" class="btn btn-danger">حذف</a>--}}
                                        {{--                                    <a href="" class="btn btn-success">إضافة مدرسة</a>--}}
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
                                    <td><strong>{{ $dayNames[date('N', strtotime($workingDay)) - 1] }}</strong></td>
                                    <td>{{ date('Y/m/d', strtotime($workingDay)) }}</td>
                                    <td>لا توجد مدارس</td>
                                    <td>
                                        {{-- Add the appropriate action for adding schools --}}
                                        {{--                                    <a href="" class="btn btn-success">إضافة مدرسة</a>--}}
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
