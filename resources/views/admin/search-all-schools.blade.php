@extends('layouts.master')

@section('content')
    <div class="p-2">
        <h2 class="p-4">بحث</h2>
        <div class="container py-2">
            <div class="card px-2">
                <form method="GET" action="{{ route('admin.search-all-schools') }}">
                    <div class="row py-4">
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
                                    <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>{{ $value }}</option>
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
        <div class="container py-2">
            @foreach($groupedPlans as $schoolId => $plans)
                @php
                    $school = $schools->find($schoolId);
                @endphp
                @if($school)
                    <span class="card px-2">
                        <h2 class="p-4">نتائج البحث للمدرسة: {{ $school->name }}</h2>
                        @if($plans->isEmpty())
                            <div class="p-4">لا توجد نتائج للبحث في هذا الشهر.</div>
                        @else
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">تاريخ الزيارة</th>
                                        <th scope="col">الزائرون</th>
                                        <th scope="col">عدد الزوار</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $index = 1;
                                    @endphp
                                    @foreach($plans as $plan)
                                        @php
                                            //$currentDate = $plan->start->format('Y-m');
                                             {{ $currentDate= Carbon\Carbon::parse($plan->start)->format('Y-m'); }}
                                            $userNames = $plan->user->name;
                                            $visitorCount = 1;
                                        @endphp
                                        @foreach($plans as $subPlan)
                                            @if(Carbon\Carbon::parse($plan->start)->format('Y-m')=== Carbon\Carbon::parse($subPlan->start)->format('Y-m') && $plan->id !== $subPlan->id)
                                                @php
                                                    $userNames .= ', ' . $subPlan->user->name;
                                                    $visitorCount++;
                                                @endphp

                                            @endif

                                        @endforeach
                                        <tr>
                                            <th scope="row">{{ $index++ }}</th>
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
                                        @break
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </span>
                @endif
            @endforeach
        </div>
    </div>
@endsection

