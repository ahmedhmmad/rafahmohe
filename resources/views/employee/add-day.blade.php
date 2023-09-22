
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

    <div class="container p-4">
        <div class="card">
            <div class="card-title p-2">
                <h3>إضافة مدرسة لليوم</h3>
                <h6 class="p-4 highlight-school">ملاحظة: المدارس المظللة تكون حسب حركة السيارات في هذا اليوم</h6>
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
                                            $carDirection = DB::table('car_movements')
                                        ->where('date', '=', $dateKey)
                                        ->value('direction');
                                                        $isMatch = $school->location === $carDirection;
                                    // Add CSS class based on whether there is a match
                                    $highlightClass = $isMatch ? 'highlight-school' : '';
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

                                        @endphp
                                        <option value="{{ $schoolId }}" {{ $disabled ? 'disabled' : '' }} class="{{ $disabled ? 'disabled-option' : 'active-option' }} {{ $highlightClass }}">
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
