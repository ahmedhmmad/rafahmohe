{{--@extends('layouts.master')--}}

{{--@section('content')--}}

{{--    <div class="container p-4">--}}
{{--        <div class="card">--}}
{{--            <div class="card-title p-2">--}}
{{--                <h3>إضافة مدرسة لليوم</h3>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--            <div class="card mt-2">--}}
{{--            <div class="card-body">--}}

{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}

{{--                @php--}}
{{--                    $url = $_SERVER['REQUEST_URI'];--}}
{{--                    $parts = explode("/", $url);--}}
{{--                    $dateString = end($parts);--}}
{{--                    $date = date_create_from_format("Y-m-d", $dateString);--}}
{{--                    $year = date_format($date, "Y");--}}
{{--                    $month = date_format($date, "m");--}}
{{--                    $startOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);--}}
{{--                    $daysInMonth = $startOfMonth->daysInMonth;--}}
{{--                    $dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];--}}

{{--                @endphp--}}
{{--                <form action="{{ route('employee.store-day') }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="date">التاريخ:</label>--}}
{{--                        <input type="text" class="form-control" id="date" name="date" value="{{ $date->format('Y-m-d') }}" readonly>--}}

{{--                    </div>--}}
{{--                    <div class="form-group m-2">--}}
{{--                        <label for="schools">المدارس:</label>--}}
{{--                    <select class="js-example-basic-multiple" id="schools"  style="width: 90%" name="schools[]" multiple="multiple">--}}

{{--                        @foreach ($schools as $school)--}}

{{--                            @php--}}
{{--                            $schoolId = $school->id;--}}
{{--                            $existingPlan = $existingPlans->where('school_id', $schoolId)->first();--}}
{{--                            $isRestricted = false;--}}

{{--                            if ($existingPlan) {--}}

{{--                            $existingPlanDepartmentId = $existingPlan->department_id ?? null;--}}

{{--                            // Check if the selected date ($date) is in the plan and the user can override the department--}}
{{--                            $dateKey = $date->format('Y-m-d');--}}
{{--                            $canOverrideDepartment = $canOverrideMultiDepartment || $dateKey === $existingPlan->date;--}}

{{--//                            if ($existingPlanDepartmentId != $departmentId) {--}}
{{--//                                $isRestricted = true &&!$canOverrideDepartment;--}}
{{--//                            }--}}
{{--                              if ($existingPlanDepartmentId == 19) {--}}
{{--                              // Special case for department 19--}}
{{--                              $isRestricted = count($existingPlans->where('start', $dateKey)--}}
{{--                                                                      ->where('department_id', 19)--}}
{{--                                                                      ->where(function ($query) {--}}
{{--                                                                          $query->where('school_id', '!=', 34)--}}
{{--                                                                                ->where('school_id', '!=', 35)--}}
{{--                                                                                ->where('school_id', '!=', 3434343404)--}}
{{--                                                                                ->where('school_id', '!=', 3434343405)--}}
{{--                                                                                ->where('school_id', '!=', 34343406)--}}
{{--                                                                                ->where('school_id', '!=', 34343405);--}}
{{--                                                                      })) > 3;--}}
{{--//                              $isRestricted = count($existingPlans->where('start', $dateKey)->where('department_id', 19)) > 3;--}}


{{--                               } else {--}}
{{--                                                                // Other departments--}}
{{--                              $isRestricted = true &&!$canOverrideDepartment;--}}
{{--                              }--}}

{{--                            }--}}

{{--                            $disabled = $isRestricted && ($existingPlan && $existingPlan->school_id !== 34);--}}

{{--                            @endphp--}}
{{--                                <option value="{{ $schoolId }}" {{ $disabled ? 'disabled' : '' }} style="font-weight: {{ $disabled ? 'normal' : 'bold' }}; color: {{ $disabled ? 'grey' : 'black' }};">--}}
{{--                                        {{ $school->name }}--}}
{{--                                        {{ $disabled ? ' (غير متاح)' : '' }}--}}
{{--                                </option>--}}

{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <button type="submit" class="btn btn-primary">حفظ</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--        </div>--}}
{{--    </div>--}}


{{--@endsection--}}

@extends('layouts.master')

@section('content')

    <div class="container p-4">
        <div class="card">
            <div class="card-title p-2">
                <h3>إضافة مدرسة لليوم</h3>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        @php
                            $url = $_SERVER['REQUEST_URI'];
                            $parts = explode("/", $url);
                            $dateString = end($parts);
                            $date = date_create_from_format("Y-m-d", $dateString);
                            $year = date_format($date, "Y");
                            $month = date_format($date, "m");
                            $startOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);
                        @endphp
                        <form action="{{ route('employee.store-day') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="date">التاريخ:</label>
                                <input type="text" class="form-control" id="date" name="date" value="{{ $date->format('Y-m-d') }}" readonly>
                            </div>
                            <div class="form-group m-2">
                                <label for="schools">المدارس:</label>
                                <select class="js-example-basic-multiple" id="schools" style="width: 90%" name="schools[]" multiple="multiple">
                                    @foreach ($schools as $school)
                                        @php
                                            $schoolId = $school->id;
                                            $dateKey = $date->format('Y-m-d');
                                            $existingPlan = $existingPlans->where('start', $dateKey)->where('school_id', $schoolId)->first();
                                            $isRestricted = false;

                                            if ($existingPlan) {
                                                $existingPlanDepartmentId = $existingPlan->department_id ?? null;

                                                // Case for department 19
                                                if ($departmentId == 19) {
                                                    $isRestricted = count($existingPlans->where('start', $dateKey)
                                                          ->where('department_id', 19)
                                                          ->where(function ($query) {
                                                              $query->where('school_id', '!=', 34)
                                                                    ->where('school_id', '!=', 35)
                                                                    ->where('school_id', '!=', 3434343404)
                                                                    ->where('school_id', '!=', 3434343405)
                                                                    ->where('school_id', '!=', 34343406)
                                                                    ->where('school_id', '!=', 34343405);
                                                          })) > 2;
                                                }
                                                // Case when the school is booked by the user's department (other than 19)
                                                else if ($existingPlanDepartmentId == $departmentId) {
                                                    $isRestricted = !$canOverrideDepartment;
                                                }
                                                // Case when the school is booked by another department
                                                else {
                                                    $isRestricted = !$canOverrideMultiDepartment;
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
        </div>
    </div>

@endsection
