@extends('layouts.master')

@section('content')
    <div class="p-4">
        <h2>إدارة ساعات العمل في المدارس</h2>

        <form method="post" action="{{ route('admin.store-school-working-hours') }}">
            @csrf

            <!-- اختر العام الدراسي -->
            <div class="form-group">
                <label for="academic_year">اختر العام الدراسي:</label>
                <select class="form-control" id="academic_year" name="academic_year">
                    <!-- قم بملء هذا القائمة المنسدلة باختيارات الأعوام الدراسية من قاعدة البيانات -->
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- اختر الفصل -->
            <div class="form-group">
                <label for="semester">اختر الفصل الدراسي:</label>
                <select class="form-control" id="semester" name="semester">
                    <!-- قم بملء هذه القائمة المنسدلة باختيارات الفصول الدراسية من قاعدة البيانات -->
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- جدول ساعات العمل في المدارس -->
            <table class="table">
                <thead>
                <tr>
                    <th>اسم المدرسة</th>
                    <th>الصباح</th>
                    <th>المساء</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($schools as $school)

                    <tr>
                        <td>{{ $school->name }}</td>
                        <td>
                            <label>
                                <input type="checkbox" name="working_hours[{{ $school->id }}][morning]" value="1">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" name="working_hours[{{ $school->id }}][afternoon]" value="1">
                            </label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">حفظ ساعات العمل</button>
        </form>
    </div>
@endsection
