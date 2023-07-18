@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">بحث</h2>
        <div class="container py-2">
            <div class="card px-2">
                <form method="GET" action="{{ route('admin.search-plan-school') }}">
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
                            <label for="month" class="form-label"><strong>الشهر</strong></label>
                            <select class="form-select" name="month" id="month" aria-label="Default select example">
                                @php
                                    $currentMonth = date('n');
                                    $months = [
                                        1 => 'يناير',
                                        2 => 'فبراير',
                                        3 => 'مارس',
                                        4 => 'أبريل',
                                        5 => 'مايو',
                                        6 => 'يونيو',
                                        7 => 'يوليو',
                                        8 => 'أغسطس',
                                        9 => 'سبتمبر',
                                        10 => 'أكتوبر',
                                        11 => 'نوفمبر',
                                        12 => 'ديسمبر',
                                        // Add the remaining months here
                                    ];
                                @endphp
                                @foreach($months as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $currentMonth ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);">بحث</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blade template code -->
        @if(isset($visits))
            <div class="container py-2">
                <span class="card px-2">
                    <h2 class="p-4"> نتائج البحث</h2>
                    @if($visits->isEmpty())
                        <div class="p-4">لا توجد نتائج للبحث.</div>
                    @else
                        <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">اسم المدرسة</th>
                                <th scope="col">تاريخ الزيارة</th>
                                <th scope="col">الزائرون</th>
                                <th scope="col">عدد الزوار</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currentDate = null;
                                $userNames = '';
                                $visitorCount = 0;
                            @endphp
                            @foreach($visits as $index => $visit)
                                @if ($visit->start !== $currentDate)
                                    @if ($currentDate !== null)
                                        <tr>
                                            <th scope="row">{{ $index }}</th>
                                            <td>{{ $visit->schools->name }}</td>
                                            <td>{{ $currentDate }}</td>
                                            <td>{{ $userNames }}</td>
                                            <td>
                                                @if ($visitorCount >= 3)
                                                    <span class="badge bg-danger p-2">{{ $visitorCount }}</span>
                                                @else
                                                    {{ $visitorCount }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @php
                                        $currentDate = $visit->start;
                                        $userNames = $visit->user->name;
                                        $visitorCount = 1;
                                    @endphp
                                @else
                                    @php
                                        $userNames .= ', ' . $visit->user->name;
                                        $visitorCount++;
                                    @endphp
                                @endif
                            @endforeach
                            @if ($currentDate !== null)
                                <tr>
                                    <th scope="row">{{ $visits->count() }}</th>
                                    <td>{{ $visits->last()->schools->name }}</td>
                                    <td>{{ $currentDate }}</td>
                                    <td>{{ $userNames }}</td>
                                    <td>{{ $visitorCount }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                @endif
            </div>
    </div>
    @endif
    </div>
@endsection
