@extends('layouts.master')

@section('content')
    <div class="p-4">
        <h2 class="p-4">بحث</h2>
        <div class="container py-2">
            <div class="card px-4">
                <form method="GET" action="{{ route('admin.search-plan') }}">
                    <div class="row py-4">
                        <div class="col-md-4">
                            <label for="employee_name" class="form-label"><strong>اسم الموظف</strong></label>
                            <input type="text" class="form-control" name="employee_name" id="employee_name">
                        </div>
                        <div class="col-md-4">
                            <label for="department_name" class="form-label"><strong>القسم</strong></label>
                            <select class="form-select" name="department_name" id="department_name" aria-label="Default select example">
                                <option value="" selected>اختر القسم</option>
                                @foreach($departments as $department)
                                    @if($department->name != 'مدارس')
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);" >بحث</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($employees))
            <div class="container py-2">
                <div class="card px-4">
                    <h2 class="p-4">نتائج البحث</h2>
                    @if($employees->isEmpty())
                        <div class="p-4">لا توجد نتائج للبحث.</div>
                    @else
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">الاسم</th>
                                <th scope="col">القسم</th>
                                <th scope="col"> عمليات</th>
                                {{-- Add more columns as needed --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->department->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.show-plan', $employee->id) }}"><i class="bx bx-show me-2"></i></a>
                                        <a href="{{ route('admin.plan-vs-actual', $employee->id) }}"><i class="bx bx-duplicate"></i></a>

                                    </td>
                                    {{-- Add more columns as needed --}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
