@extends('layouts.master')

@section('content')
    <div class="container py-1">
        <div class="card py-2">
            <h2>سجل زيارات المدارس</h2>


            @csrf
            <table class="table">
                <thead>
                <tr>
                    <th>اليوم</th>
                    <th>التاريخ</th>
                    <th>اسم المستخدم</th>
                    <th>المسمى الوظيفي</th>
                    <th>اسم المدرسة</th>
                    <th>وقت الحضور</th>
                    <th>وقت الانصراف</th>
                    <th>الهدف من الزيارة</th>
                    <th>الأنشطة المنفذة</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($schoolVisits as $schoolVisit)
                    @php
                        $start = \Carbon\Carbon::parse($schoolVisit->visit_date)->locale('ar');
                    @endphp

                    <tr>
                        <td><h6>{{ $start->translatedFormat('l') }}</h6></td>
                        <td>
                            {{--                                <input type="date" name="visit_date[]" class="form-control date-input" required>--}}
                            {{--                                <input type="hidden" name="day[]" class="day-input">--}}
                            <h6>{{ $start->translatedFormat('Y-m-d') }}</h6>
                            <input type="hidden" name="visit_date[]" value="{{ $start->translatedFormat('Y-m-d') }}">
                            <input type="hidden" name="day[]" value="{{ $start->translatedFormat('l') }}">
                        </td>
                        <td>
                            <h6>{{$schoolVisit->user->name}}</h6>
                            <input type="hidden" name="user_name[]" class="form-control user-name-input" required>
                            <input type="hidden" name="user_id[]" class="user-id-input">
                        </td>
                        <td>
                            <h6>{{$schoolVisit->user->job_title}}</h6>
                            <input type="hidden" name="job_title[]" class="form-control job-title-input" readonly>
                        </td>
                        <td>
                            <h6>{{$schoolVisit->school->name}}</h6>

                        </td>
                        <td>
                            <h6>{{$schoolVisit->coming_time}}</h6>

                        </td>
                        <td>

                            <h6>{{$schoolVisit->leaving_time}}</h6>
                        </td>
                        <td>
                            <h6>{{$schoolVisit->purpose}}</h6>
                        </td>
                        <td>
                            <h6>{{$schoolVisit->activities}}</h6>
                        </td>

                    </tr>


                @endforeach

                </tbody>
            </table>



        </div>
    </div>

@endsection
