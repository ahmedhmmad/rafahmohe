@extends('layouts.master')

@section('content')

    <div class="container p-4">

        <div class="row">
            <div class="col-md-12">
                <h3>إضافة مدرسة لليوم</h3>
                @php
                    $url = $_SERVER['REQUEST_URI'];
                    $parts = explode("/", $url);
                    $dateString = end($parts);
                    $date = date_create_from_format("Y-m-d", $dateString);
                    $year = date_format($date, "Y");
                    $month = date_format($date, "m");
                    $startOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);
                    $daysInMonth = $startOfMonth->daysInMonth;
                    $dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

                @endphp
                <form action="{{ route('employee.store-day') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="date">التاريخ:</label>
                        <input type="text" class="form-control" id="date" name="date" value="{{ $date->format('Y-m-d') }}" readonly>

                    </div>
                    <div class="form-group">
                        <label for="schools">المدارس:</label>
                    <select class="form-control" id="schools" name="schools[]" multiple>

                        @foreach ($schools as $school)

                            @php
                            $schoolId = $school->id;
                            $existingPlan = $existingPlans->where('school_id', $schoolId)->first();
                            $isRestricted = false;

                            if ($existingPlan) {

//                            $existingPlanDepartmentId = $existingPlan->department_id ?? null;
//
//                            // Check if the selected date ($date) is in the plan and the user can override the department
//                            $dateKey = $date->format('Y-m-d');
//                            $canOverrideDepartment = $canOverrideMultiDepartment || $dateKey === $existingPlan->date;
//
//                            if ($existingPlanDepartmentId != $departmentId) {
//                                $isRestricted = true &&!$canOverrideDepartment;
//                            }
                                $dateKey = $date->format('Y-m-d');
                               $existingPlanDepartmentId = $existingPlan->department_id ?? null;
                               if ($existingPlanDepartmentId == 19) {
                                // Special case for department 19
                                  $isRestricted = count($existingPlans->where('start', $dateKey)->where('department_id', 19)) > 3;
                               } else {
                                   // Other departments
                                         $isRestricted = in_array($dateKey, $existingPlanDates) && !$canOverrideMultiDepartment;
                               }

                                 }

                            $disabled = $isRestricted && ($existingPlan && $existingPlan->school_id !== 34);

                            @endphp
                                <option value="{{ $schoolId }}" {{ $disabled ? 'disabled' : '' }} style="font-weight: {{ $disabled ? 'normal' : 'bold' }}; color: {{ $disabled ? 'grey' : 'black' }};">
                                        {{ $school->name }}
                                        {{ $disabled ? ' (غير متاح)' : '' }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </form>
            </div>
        </div>
    </div>

@endsection

