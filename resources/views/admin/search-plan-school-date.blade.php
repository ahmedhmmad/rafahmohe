{{--@extends('layouts.master')--}}

{{--@section('content')--}}
{{--    <div class="p-4">--}}
{{--        <h2 class="p-4">بحث</h2>--}}
{{--        <div class="container py-2">--}}
{{--            <div class="card px-4">--}}
{{--                <form method="GET" action="{{ route('admin.search-plan-school-date') }}">--}}
{{--                    <div class="row py-4">--}}
{{--                        <div class="col-md-4">--}}
{{--                            <label for="school_name" class="form-label"><strong>المدرسة</strong></label>--}}
{{--                            <select class="form-select" name="school_name" id="school_name" aria-label="Default select example">--}}
{{--                                <option value="" selected>اختر المدرسة</option>--}}
{{--                                @foreach($schools as $school)--}}
{{--                                    <option value="{{ $school->id }}">{{ $school->name }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4">--}}
{{--                            <label for="visit_date" class="form-label"><strong>تاريخ الزيارة</strong></label>--}}
{{--                            <input type="date" class="form-control" name="visit_date" id="visit_date">--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4">--}}
{{--                            <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);" >بحث</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Blade template code -->--}}
{{--        @if(isset($visits))--}}
{{--            <div class="container py-2">--}}
{{--                <div class="card px-4">--}}
{{--                    <h2 class="p-4">نتائج البحث</h2>--}}
{{--                    @if($visits->isEmpty())--}}
{{--                        <div class="p-4">لا توجد نتائج للبحث.</div>--}}
{{--                    @else--}}
{{--                        <table class="table">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th scope="col">#</th>--}}
{{--                                <th scope="col">اسم المدرسة</th>--}}
{{--                                <th scope="col">تاريخ الزيارة</th>--}}
{{--                                <th scope="col">الزائرون</th>--}}
{{--                                <th scope="col">العدد</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($visits as $index => $visit)--}}
{{--                                <tr>--}}
{{--                                    <th scope="row">{{ $index + 1 }}</th>--}}
{{--                                    <td>{{ $visit->schools->name }}</td>--}}
{{--                                    <td>{{ $visit->start }}</td>--}}
{{--                                    <td>--}}
{{--                                        @foreach($visits as $user)--}}

{{--                                            {{ $user->user->name }}--}}
{{--                                            @if(!$loop->last)--}}
{{--                                                ,--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                    <td>{{ $visitCount }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}

{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}

{{--    </div>--}}
{{--@endsection--}}

@extends('layouts.master')

@section('content')
    <div class="p-4">
        <h2 class="p-4">بحث</h2>
        <div class="container py-2">
            <div class="card px-4">
                <form method="GET" action="{{ route('admin.search-plan-school-date') }}">
                    <div class="row py-4">
                        <div class="col-md-4">
                            <label for="school_name" class="form-label"><strong>المدرسة</strong></label>
                            <select class="form-select" name="school_name" id="school_name" aria-label="Default select example">
                                <option value="" selected>اختر المدرسة</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="visit_date" class="form-label"><strong>تاريخ الزيارة</strong></label>
                            <input type="date" class="form-control" name="visit_date" id="visit_date">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);" >بحث</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blade template code -->
        @if(isset($visits))
            <div class="container py-2">
                <div class="card px-4">
                    <h2 class="p-4">نتائج البحث</h2>
                    @if($visits->isEmpty())
                        <div class="p-4">لا توجد نتائج للبحث.</div>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">اسم المدرسة</th>
                                <th scope="col">تاريخ الزيارة</th>
                                <th scope="col">الزائرون</th>
                                <th scope="col">العدد</th>
                            </tr>
                            </thead>
                            <tbody>

{{--                            @foreach($visits as $index => $visit)--}}
{{--                                <tr>--}}
{{--                                    <th scope="row">{{ $index + 1 }}</th>--}}
{{--                                    <td>{{ $visit->schools->name }}</td>--}}
{{--                                    <td>{{ $visit->start }}</td>--}}
{{--                                    <td>--}}

{{--                                        @foreach($visits as $user)--}}
{{--                                            {{ $user->user->name }}--}}
{{--                                            @if(!$loop->last)--}}
{{--                                                ,--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                    <td>{{ $visitCount }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}


@foreach($visits as $index => $visit)
    <tr>
        <th scope="row">{{ $index + 1 }}</th>
        <td>{{ $visit->schools->name }}</td>
        <td>{{ $visit->start }}</td>
        <td>

            @foreach($visits as $user)
                {{ $user->user->name }}
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
        <td>{{ $visitCount }}</td>
    </tr>
        @break
@endforeach




                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
