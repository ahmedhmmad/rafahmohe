@extends('layouts.master')

@section('content')
    <div class="p-4">
        <h2 class="p-4">بحث</h2>
        <div class="container py-2">
            <div class="card px-4">
                <form method="GET" action="{{ route('school.index') }}">
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
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year" class="form-label"><strong>السنة</strong></label>
                            <select class="form-select" name="year" id="year" aria-label="Default select example">
                                <option value="" selected>اختر السنة</option>
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
            </div>
        </div>

        <!-- Blade template code -->
        @if(isset($schoolPlannedVisits))
            <div class="container py-2">
                <div class="card px-4">
                    <h2 class="p-4">نتائج البحث</h2>
                    @if($schoolPlannedVisits->isEmpty())
                        <div class="p-4">لا توجد نتائج للبحث.</div>
                    @else
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">اليوم</th>
                                <th scope="col">تاريخ الزيارة</th>
                                <th scope="col">الزائرون</th>
{{--                                <th scope="col">العدد</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $visitedDays = [];
                            @endphp
                            @foreach($schoolPlannedVisits as $index => $schoolPlannedVisit)
                                @php
                                    $currentDay = \Carbon\Carbon::parse($schoolPlannedVisit->start)->format('Y-m-d');
                                @endphp
                                @if (!in_array($currentDay, $visitedDays))
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $schoolPlannedVisit->start ? \Carbon\Carbon::parse($schoolPlannedVisit->start)->locale('ar')->isoFormat('dddd') : '' }}</td>
                                        <td>{{ $schoolPlannedVisit->start ? \Carbon\Carbon::parse($schoolPlannedVisit->start)->format('Y-m-d') : '' }}</td>
                                        <td>
                                            @foreach($schoolPlannedVisits as $visit)
                                                @if(\Carbon\Carbon::parse($visit->start)->format('Y-m-d') === $currentDay)
                                                    {{ $visit->user->name }} ({{ $visit->user->department->name }})
                                                    @if(!$loop->last)
                                                        ,
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
{{--                                        <td>--}}
{{--                                            <a href="" class="btn btn-primary">عرض</a>--}}
{{--                                        </td>--}}
                                    </tr>
                                    @php
                                        $visitedDays[] = $currentDay;
                                    @endphp
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
