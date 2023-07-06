@extends('layouts.master')

@section('content')

    <div class="container py-2">
        <h2 >سجل زيارات المدارس</h2>
        <div class="card py-2">
            <div class="card-body">
            <form method="GET" action="{{route('head.view-visits')}}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
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
                <table class="table">
                            <thead>
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
                                        {{$schoolVisit->coming_time}}

                                    </td>
                                    <td>

                                        {{$schoolVisit->leaving_time}}
                                    </td>
                                    <td>
                                        {{$schoolVisit->purpose}}
                                    </td>
                                    <td>
                                        {{$schoolVisit->activities}}
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

                    <div class="demo-inline-spacing">
                        <!-- Basic Pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item {{ $schoolVisits->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $schoolVisits->previousPageUrl() }}" aria-label="Previous">
                                        <i class="tf-icon bx bx-chevrons-right">السابق</i>
                                    </a>
                                </li>
                                @for($i = 1; $i <= $schoolVisits->lastPage(); $i++)
                                    <li class="page-item {{ $schoolVisits->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $schoolVisits->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ $schoolVisits->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $schoolVisits->nextPageUrl() }}" aria-label="Next">
                                        <i class="tf-icon bx bx-chevron-left">التالي</i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!--/ Basic Pagination -->
                    </div>
                </div>
            </div>




@endsection
