@extends('layouts.master')

@section('content')
    <div class="p-4">


        <h2 class="p-4">بحث</h2>
        <div class="container py-2">
            <div class="card px-4">
                <form method="GET" action="{{ route('admin.search-results') }}">

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
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
{{--                    <form action="" method="get" id="createPlanForm">--}}

                        <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);" >بحث</button>

{{--                    </form>--}}

                </div>
            </div>
                </form>
        </div>

    </div>
    </div>

@endsection
