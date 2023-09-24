@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong> الأقسام التي أدخلت الخطة</strong>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($departmentsWithPlans as $department)
                                <strong><li>{{ $department->name }}</li></strong>
                                <ul>
                                    @foreach ($usersWithPlans[$department->id] as $user)
                                        <li>{{ $user->user->name }}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong> الأقسام التي لم تدخل الخطة</strong>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($departmentsWithoutPlans as $department)
                            <li>{{ $department->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>اختر شهر وسنة لعرض الملخص</strong>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.summary') }}">
                            @csrf
                            <div class="form-group">
                                <label for="month">الشهر:</label>
                                <select class="form-control" id="month" name="month">
                                    @foreach ($months as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $selectedMonth ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">السنة:</label>
                                <input type="number" class="form-control" id="year" name="year" value="{{ $selectedYear }}">
                            </div>
                            <button type="submit" class="btn btn-primary">عرض الخطط</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
