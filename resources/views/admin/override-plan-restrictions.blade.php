@extends('layouts.master')

@section('content')
    <div class="container py-2">
        <div class="card py-2">
            <div class="card-body">
            <h2>استثناء قيود الخطة</h2>

            <form method="GET" action="{{ route('admin.override-plan-restrictions') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="selected_department_id" class="form-label fw-bold">اختر القسم</label>
                        <select name="selected_department_id" id="selected_department_id" class="form-select" dir="rtl">
                            <option value="">اختر القسم</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('selected_department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_department_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="selected_user_id" class="form-label fw-bold">اختر المستخدم</label>
                        <select name="selected_user_id" id="selected_user_id" class="form-select" dir="rtl">
                            <option value="">اختر المستخدم</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('selected_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_user_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3 align-self-end">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </div>

            </form>
        </div>
        </div>
    </div>
{{--    New Container--}}



                @if ($selectedUserOrDepartment)
                    <div class="container py-2">
                        <div class="card py-2">
                            <div class="card-body">

                    <form method="POST" action="{{ route('admin.apply-override-plan-restrictions') }}">
                        @csrf

                        <!-- Inside the <form> tag -->
                        <input type="hidden" name="selected_user_or_department" value="{{ $selectedUserOrDepartment }}">

                        @if ($selectedUserOrDepartment == 'user')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="can_override_last_week" class="form-label fw-bold">السماح باستثناء الأسبوع الماضي</label>
                                        <select name="can_override_last_week" id="can_override_last_week" class="form-control" required>
                                            <option value="1" {{ old('can_override_last_week') ? 'selected' : '' }}>نعم</option>
                                            <option value="0" {{ old('can_override_last_week') ? '' : 'selected' }}>لا</option>
                                        </select>
                                        @error('can_override_last_week')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="override_start_date" class="form-label fw-bold">تاريخ بدء الاستثناء</label>
                                        <input type="date" name="override_start_date" id="override_start_date" class="form-control" value="{{ old('override_start_date') }}">
                                        @error('override_start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="can_override_department" class="form-label fw-bold">السماح باستثناء الموظف</label>
                                        <select name="can_override_department" id="can_override_department" class="form-control" required>
                                            <option value="1" {{ old('can_override_department') ? 'selected' : '' }}>نعم</option>
                                            <option value="0" {{ old('can_override_department') ? '' : 'selected' }}>لا</option>
                                        </select>
                                        @error('can_override_department')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="override_end_date" class="form-label fw-bold">تاريخ انتهاء الاستثناء</label>
                                        <input type="date" name="override_end_date" id="override_end_date" class="form-control" value="{{ old('override_end_date') }}">
                                        @error('override_end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                            </div>


                        @elseif ($selectedUserOrDepartment == 'department')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="can_override_last_week" class="form-label fw-bold">السماح باستثناء الأسبوع الماضي</label>
                                                <select name="can_override_last_week" id="can_override_last_week" class="form-control" required>
                                                    <option value="1" {{ old('can_override_last_week') ? 'selected' : '' }}>نعم</option>
                                                    <option value="0" {{ old('can_override_last_week') ? '' : 'selected' }}>لا</option>
                                                </select>
                                                @error('can_override_last_week')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="override_start_date" class="form-label fw-bold">تاريخ بدء الاستثناء</label>
                                                <input type="date" name="override_start_date" id="override_start_date" class="form-control" value="{{ old('override_start_date') }}">
                                                @error('override_start_date')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="can_override_department" class="form-label fw-bold">السماح باستثناء جميع موظفي القسم </label>
                                                <select name="can_override_department" id="can_override_department" class="form-control" required>
                                                    <option value="1" {{ old('can_override_department') ? 'selected' : '' }}>نعم</option>
                                                    <option value="0" {{ old('can_override_department') ? '' : 'selected' }}>لا</option>
                                                </select>
                                                @error('can_override_department')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="mb-3">
                                                <label for="override_end_date" class="form-label fw-bold">تاريخ انتهاء الاستثناء</label>
                                                <input type="date" name="override_end_date" id="override_end_date" class="form-control" value="{{ old('override_end_date') }}">
                                                @error('override_end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                        </div>

                        @endif




                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">تطبيق الاستثناء</button>
                        </div>
                                    </div>
                                </div>
                    </form>
                </div>
            </div>
        </div>

                @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#selected_department_id').change(function() {
                var departmentId = $(this).val();

                // Send an AJAX request to fetch the department users
                $.ajax({
                    url: '/fetch-department-users',
                    method: 'GET',
                    data: { department_id: departmentId },
                    success: function(response) {
                        // Clear the previous options in the users select box
                        $('#selected_user_id').empty();

                        // Add the "اختر المستخدم" option
                        var selectOption = $('<option>', { value: '', text: 'اختر الموظف' });
                        $('#selected_user_id').append(selectOption);

                        // Add the new options based on the department users
                        response.users.forEach(function(user) {
                            var option = $('<option>', { value: user.id, text: user.name });
                            $('#selected_user_id').append(option);
                        });
                    }
                });
            });
        });
    </script>
@endsection
