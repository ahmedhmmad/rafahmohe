@extends('layouts.master')

@section('content')

    <div class="container py-2">
        <h2 >سجل زيارات المدارس</h2>
        <div class="card py-2">
            <div class="card-body">
            <form method="GET" action="{{route('head.view-visits')}}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="school">الموظف</label>
                        <select name="user" id="user" class="form-control form-select">
                            <option value="">اختر الموظف</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="school">المدرسة</label>
                        <select name="school" id="school" class="form-control form-select">
                            <option value="">اختر المدرسة</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="month">الشهر</label>
                        <select name="month" id="month" class="form-control form-select">
                            <option value="">اختر الشهر</option>
                            <option value="1">يناير</option>
                            <option value="2">فبراير</option>
                            <option value="3">مارس</option>
                            <option value="4">أبريل</option>
                            <option value="5">مايو</option>
                            <option value="6">يونيو</option>
                            <option value="7">يوليو</option>
                            <option value="8">أغسطس</option>
                            <option value="9">سبتمبر</option>
                            <option value="10">أكتوبر</option>
                            <option value="11">نوفمبر</option>
                            <option value="12">ديسمبر</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="year">السنة</label>
                        <select name="year" id="year" class="form-control form-select">
                            <option value="">اختر السنة</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>

                        </select>
                    </div>
                    <div class="form-group col-md-2"><button type="submit" class="btn btn-primary mt-4 px-lg-4">بحث</button></div>

                </div>

            </form>
            </div>
        </div>
        </div>
    </div>

    <div class="container py-1">
        <div class="card py-2">
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead class="table-primary">
                            <tr>
                                <th><strong>اليوم</strong></th>
                                <th><strong>التاريخ</strong></th>
                                <th><strong>اسم المستخدم</strong></th>
                                <th><strong>المسمى الوظيفي</strong></th>
                                <th><strong>اسم المدرسة</strong></th>
                                <th><strong>وقت الحضور</strong></th>
                                <th><strong>وقت الانصراف</strong></th>
                                <th><strong>الهدف من الزيارة</strong></th>
                                <th><strong>الأنشطة المنفذة</strong></th>
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
                                        <h6><strong>{{$schoolVisit->user->name}}</strong></h6>
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
                                        {{$schoolVisit->coming_time}}

                                    </td>
                                    <td>

                                        {{$schoolVisit->leaving_time}}
                                    </td>
{{--                                    <td>--}}
{{--                                        {{$schoolVisit->purpose}}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{$schoolVisit->activities}}--}}
{{--                                    </td>--}}

                                        <td>
                                            <span title="{{$schoolVisit->purpose}}">
                                                {{\Str::limit($schoolVisit->purpose, 25)}}
                                            </span>
                                        </td>
                                        <td>
                                            <span title="{{$schoolVisit->activities}}">
                                                {{\Str::limit($schoolVisit->activities, 25)}}
                                            </span>
                                        </td>




                                    {{--                            <td>--}}
                                    {{--                                <textarea type="text" name="purpose[]" class="form-control" required></textarea>--}}
                                    {{--                            </td>--}}
                                    {{--                            <td>--}}
                                    {{--                                <textarea name="activities[]" class="form-control" required></textarea>--}}
                                    {{--                            </td>--}}
                                </tr>
                                <!-- Modal -->

                            @endforeach

                            </tbody>
                        </table>
            </div>
        </div>

    </div>

            <div class="row">
                <div class="col">
                    @php
                        $queryParams = [
                            'user' => request('user'),
                            'school' => request('school'),
                            'month' => request('month'),
                            'year' => request('year')
                        ];
                    @endphp
                    <div class="demo-inline-spacing">
                        <!-- Basic Pagination -->
                        <nav aria-label="Page navigation">
                            {{ $schoolVisits->appends(request()->query())->onEachSide(3)->links('vendor.pagination.custom') }}
                        </nav>
                        <!--/ Basic Pagination -->
                    </div>
                </div>
            </div>




@endsection
