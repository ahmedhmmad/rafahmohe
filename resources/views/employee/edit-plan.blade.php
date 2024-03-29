@extends('layouts.master')

@section('content')
    <div class="container p-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <h3>تعديل الخطة الشهرية</h3>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">

                        <form action="{{ route('employee.update-plan', $plan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="date">التاريخ:</label>
                                <input type="text" class="form-control" id="date" name="date" value="{{ $plan->start }}" readonly>
                            </div>
                            <div class="form-group m-2">
                                <label for="school">المدرسة:</label>
                                <select class="js-example-basic-single" style="width: 90%" id="school" name="school">
                                    @foreach ($schools as $school)
                                        @php
                                            $schoolId = $school->id;
                                            $dateKey = $plan->start;
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
                                                else {
                                                    $isRestricted = !$canOverrideMultiDepartment;
                                                }
                                                }
                                              $excludedSchoolIds = [34, 35, 3434343404, 3434343405, 34343406, 34343405];
                                              $disabled = $isRestricted && ($existingPlan && !in_array($existingPlan->school_id, $excludedSchoolIds));


                                              //  $disabled = $isRestricted && ($existingPlan && in_array($existingPlan->school_id, $excludedSchoolIds));


                                        @endphp
                                        <option value="{{ $schoolId }}" {{ $disabled ? 'disabled' : '' }} style="font-weight: {{ $disabled ? 'normal' : 'bold' }}; color: {{ $disabled ? 'grey' : 'black' }};">
                                            {{ $school->name }} {{ $disabled ? '(غير متاح)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success mt-4">حفظ التعديلات</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
