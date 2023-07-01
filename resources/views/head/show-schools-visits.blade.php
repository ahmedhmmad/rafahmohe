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
                            <input type="time" name="coming_time[]" class="form-control"  required>
                        </td>
                        <td>
                            <input type="time" name="leaving_time[]" class="form-control" required>
                        </td>
                        <td>

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purposeActivitiesModal{{ $loop->index }}">+</button>
                        </td>




                        {{--                            <td>--}}
                        {{--                                <textarea type="text" name="purpose[]" class="form-control" required></textarea>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <textarea name="activities[]" class="form-control" required></textarea>--}}
                        {{--                            </td>--}}
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="purposeActivitiesModal{{ $loop->index }}" tabindex="-1" aria-labelledby="purposeActivitiesModalLabel{{ $loop->index }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="purposeActivitiesModalLabel{{ $loop->index }}">الهدف والأنشطة</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="purposeTextarea{{ $loop->index }}" class="form-label">الهدف من الزيارة</label>
                                        <textarea class="form-control" id="purposeTextarea{{ $loop->index }}" name="purpose[]" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="activitiesTextarea{{ $loop->index }}" class="form-label">الأنشطة المنفذة</label>
                                        <textarea class="form-control" id="activitiesTextarea{{ $loop->index }}" name="activities[]" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                                    <button type="button" class="btn btn-primary" onclick="setPurposeActivitiesValue({{ $loop->index }})">حفظ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                </tbody>
            </table>



        </div>
    </div>

@endsection
