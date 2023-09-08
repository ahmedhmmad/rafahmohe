{{--@extends('layouts.master')--}}

{{--@section('content')--}}

{{--    <div class="p-4">--}}
{{--        <h2 class="p-4">إدخال الخطة الشهرية<span style="color:#167ce8;padding-right: 1em;">{{ $year }}/{{ $month }}</span></h2>--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <form method="POST" action="{{ route('employee.store-plan') }}" onsubmit="return validateForm()">--}}
{{--                    @csrf--}}
{{--                    @php--}}
{{--                        $startOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);--}}
{{--                        $daysInMonth = $startOfMonth->daysInMonth;--}}
{{--                        $dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];--}}
{{--                    @endphp--}}

{{--                    @for ($day = 1; $day <= $daysInMonth; $day++)--}}
{{--                        @if ($startOfMonth->dayOfWeek !== 5 && $startOfMonth->dayOfWeek !== 6)--}}
{{--                            <div class="col-md-10" style="border-bottom: 1px dotted #000; padding-bottom: 20px;">--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-5">--}}
{{--                                            <label>--}}
{{--                                                <strong style="color: #50a14f">{{ $dayNames[$startOfMonth->dayOfWeek] }}</strong>--}}
{{--                                            </label>--}}
{{--                                            <input type="text" class="form-control" value="{{ $startOfMonth->isoFormat('D/MM/YYYY') }}" readonly>--}}
{{--                                            <input type="hidden" name="days[{{ $startOfMonth->format('Y-m-d') }}][date]" value="{{ $startOfMonth->format('Y-m-d') }}">--}}
{{--                                            @if ($errors->has($startOfMonth->format('Y-m-d')))--}}
{{--                                                <ul style="list-style: none; color: red;">--}}
{{--                                                    @foreach ($errors->get($startOfMonth->format('Y-m-d')) as $error)--}}
{{--                                                        <li>{{ $error }}</li>--}}
{{--                                                    @endforeach--}}
{{--                                                </ul>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                        <div class="col-7">--}}
{{--                                            <label class="form-label" style="color: #1e0ace">المدرسة</label>--}}
{{--                                            <select class="js-example-basic-multiple" name="days[{{ $startOfMonth->format('Y-m-d') }}][schools][]" id="schoolSelect{{ $day }}" multiple="multiple" required>--}}
{{--                                                @foreach($schools as $school)--}}
{{--                                                    @php--}}
{{--                                                        $schoolId = $school->id;--}}
{{--                                                        $dateKey = $startOfMonth->format('Y-m-d');--}}
{{--                                                        $existingPlan = $existingPlans->where('start', $dateKey)->where('school_id', $schoolId)->first();--}}
{{--                                                        $isRestricted = false;--}}

{{--                                                        if ($existingPlan) {--}}
{{--                                                            $existingPlanDepartmentId = $existingPlan->department_id ?? null;--}}

{{--                                                            if ($existingPlanDepartmentId == 19) {--}}
{{--                                                                // Special case for department 19--}}
{{--                                                                $isRestricted = count($existingPlans->where('start', $dateKey)--}}
{{--                                                                      ->where('department_id', 19)--}}
{{--                                                                      ->where(function ($query) {--}}
{{--                                                                          $query->where('school_id', '!=', 34)--}}
{{--                                                                                ->where('school_id', '!=', 35)--}}
{{--                                                                                ->where('school_id', '!=', 3434343404)--}}
{{--                                                                                ->where('school_id', '!=', 3434343405)--}}
{{--                                                                                ->where('school_id', '!=', 34343406)--}}
{{--                                                                                ->where('school_id', '!=', 34343405);--}}
{{--                                                                      })) > 3;--}}
{{--                                                            } else {--}}
{{--                                                                // Other departments--}}
{{--                                                                $isRestricted = in_array($dateKey, $existingPlanDates) && !$canOverrideMultiDepartment;--}}
{{--                                                            }--}}
{{--                                                        }--}}

{{--                                                        $disabled = $isRestricted && ($existingPlan && $existingPlan->school_id !== 34);--}}
{{--                                                    @endphp--}}

{{--                                                    <option value="{{ $schoolId }}" {{ $disabled ? 'disabled' : '' }} style="font-weight: {{ $disabled ? 'normal' : 'bold' }}; color: {{ $disabled ? 'grey' : 'black' }};">--}}
{{--                                                        {{ $school->name }}--}}
{{--                                                        {{ $disabled ? ' (غير متاح)' : '' }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @php--}}
{{--                            $startOfMonth->addDay();--}}
{{--                        @endphp--}}
{{--                    @endfor--}}

{{--                    <div class="col-md-10">--}}
{{--                        <div class="form-group">--}}
{{--                            <button type="submit" class="btn btn-primary">حفظ</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--@endsection--}}

@extends('layouts.master')

@section('content')
    <style>
        .dotted-border {
            border-bottom: 1px dotted #000;
            padding-bottom: 20px;
        }
        .day-name {
            color: #50a14f;
            font-weight: bold;
        }
        .school-label {
            color: #1e0ace;
        }
        .disabled-option {
            color: grey;
        }
        .active-option {
            color: black;
            font-weight: bold;
        }
        .school-label,
        .js-example-basic-multiple {
            display: block;
            width: 100%; /* Optional, but ensures they take up the full width */
        }
    </style>

    <div class="p-4">
        <h2 class="p-4">إدخال الخطة الشهرية<span style="color:#167ce8;padding-right: 1em;">{{ $year }}/{{ $month }}</span></h2>
        <div class="container">
            <div class="row">
                <form method="POST" action="{{ route('employee.store-plan') }}" onsubmit="return validateForm()">
                    @csrf

                    @php
                        $startOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);
                        $daysInMonth = $startOfMonth->daysInMonth;
                        $dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                    @endphp

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @if ($startOfMonth->dayOfWeek !== 5 && $startOfMonth->dayOfWeek !== 6)
                            <div class="col-md-10 dotted-border mb-2">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-5">
                                            <label class="day-name">{{ $dayNames[$startOfMonth->dayOfWeek] }}</label>
                                            <input type="text" class="form-control" value="{{ $startOfMonth->isoFormat('D/MM/YYYY') }}" readonly>
                                            <input type="hidden" name="days[{{ $startOfMonth->format('Y-m-d') }}][date]" value="{{ $startOfMonth->format('Y-m-d') }}">
                                            @if ($errors->has($startOfMonth->format('Y-m-d')))
                                                <ul style="list-style: none; color: red;">
                                                    @foreach ($errors->get($startOfMonth->format('Y-m-d')) as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                        <div class="col-7">
                                            <label class="school-label">المدرسة</label>
                                            <select class="js-example-basic-multiple" name="days[{{ $startOfMonth->format('Y-m-d') }}][schools][]" id="schoolSelect{{ $day }}" multiple="multiple" required>
                                                @foreach($schools as $school)
                                                    @php
                                                        $schoolId = $school->id;
                                                        $dateKey = $startOfMonth->format('Y-m-d');
                                                        $existingPlan = $existingPlans->where('start', $dateKey)->where('school_id', $schoolId)->first();
                                                        $isRestricted = false;

                                                        if ($existingPlan) {
                                                            $existingPlanDepartmentId = $existingPlan->department_id ?? null;

                                                            if ($existingPlanDepartmentId == 19) {
                                                                // Special case for department 19
                                                                $isRestricted = count($existingPlans->where('start', $dateKey)
                                                                      ->where('department_id', 19)
                                                                      ->where(function ($query) {
                                                                          $query->where('school_id', '!=', 34)
                                                                                ->where('school_id', '!=', 35)
                                                                                ->where('school_id', '!=', 3434343404)
                                                                                ->where('school_id', '!=', 3434343405)
                                                                                ->where('school_id', '!=', 34343406)
                                                                                ->where('school_id', '!=', 34343405);
                                                                      })) > 3;
                                                            } else {
                                                                // Other departments
                                                                $isRestricted = in_array($dateKey, $existingPlanDates) && !$canOverrideMultiDepartment;
                                                            }
                                                        }

                                                        $disabled = $isRestricted && ($existingPlan && $existingPlan->school_id !== 34);
                                                    @endphp

                                                    <option value="{{ $schoolId }}" {{ $disabled ? 'disabled' : '' }} class="{{ $disabled ? 'disabled-option' : 'active-option' }}">
                                                        {{ $school->name }}
                                                        {{ $disabled ? ' (غير متاح)' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php
                            $startOfMonth->addDay();
                        @endphp
                    @endfor

                    <div class="col-md-10 mt-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
