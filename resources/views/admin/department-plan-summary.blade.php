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
                                <li>{{ $department->name }}</li>
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
    </div>
@endsection
