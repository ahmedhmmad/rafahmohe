
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
        .highlight-school {
            background-color: #cae0d2;
        }
        .disabled-option {
            color: grey;
        }
        .active-option {
            color: black;
            /*font-weight: bold;*/
        }
        .school-label,
        .js-example-basic-multiple {
            display: block;
            width: 100%; /* Optional, but ensures they take up the full width */
        }
    </style>

    <div class="p-4">
        <h2 class="p-4">إدخال الخطة الشهرية<span style="color:#167ce8;padding-right: 1em;">{{ $year }}/{{ $month }}</span></h2>
        <h5 class="p-4 highlight-school">ملاحظة: المدارس المظللة تكون حسب حركة السيارات في هذا اليوم</h5>
        <div class="container mt-1">
            <div class="row">
                <form method="POST" action="{{ route('employee.store-plan') }}" onsubmit="return validateForm()">
                    @csrf

                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">

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
                                                        $carDirection = DB::table('car_movements')
                                                        ->where('date', '=', $dateKey)
                                                        ->pluck('direction')
                                                        ->toArray(); // Convert the result to an array

                                                    $isMatch = in_array($school->location, $carDirection);
                                    // Add CSS class based on whether there is a match
                                    $highlightClass = $isMatch ? 'highlight-school' : '';
                                                        // Filter existing plans for the current school
                                                       // If department is NOT 19, exclude plans from department 19
                                                        if($departmentId != 19) {
                                                            $existingPlan = $existingPlans->where('start', $dateKey)
                                                                                          ->where('school_id', $schoolId)
                                                                                          ->where('department_id', '!=', 19)
                                                                                          ->first();
                                                        } else {
                                                            $existingPlan = $existingPlans->where('start', $dateKey)
                                                                                          ->where('school_id', $schoolId)
                                                                                          ->first();
                                                        }

                                                        $isRestricted = false;

                                                        if ($existingPlan) {
                                                            $existingPlanDepartmentId = $existingPlan->department_id ?? null;

                                                            // Case for department 19
                                                            if ($departmentId == 19) {
                                                                // Count existing plans for department 19 excluding the exception schools
                                                                $countDepartment19Plans = $existingPlans->where('start', $dateKey)
                                                                    ->where('department_id', 19)
                                                                    ->whereNotIn('school_id', [34, 35, 3434343404, 3434343405, 34343406, 34343405])
                                                                    ->where('school_id', $schoolId)
                                                                    ->count();

                                                                $isRestricted = $countDepartment19Plans >= 2;
                                                            }
                                                            // Case when the school is booked by the user's department (other than 19)
                                                            else if ($existingPlanDepartmentId == $departmentId) {
                                                                $isRestricted = !$canOverrideDepartment;
                                                            }
                                                            // Case when the school is booked by another department
                                                            else if (in_array($dateKey, $existingPlanDates)) {
                                                                $isRestricted = !$canOverrideMultiDepartment;
                                                            }
                                                        }

                                                       $excludedSchoolIds = [34, 35, 3434343404, 3434343405, 34343406, 34343405];
                                              $disabled = $isRestricted && ($existingPlan && !in_array($existingPlan->school_id, $excludedSchoolIds));

                                                    @endphp

                                                    <option value="{{ $schoolId }}" {{ $disabled ? 'disabled' : '' }} class="{{ $disabled ? 'disabled-option' : 'active-option' }} {{ $highlightClass }}">
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

