{{--@extends('layouts.master')--}}

{{--@section('content')--}}

{{--    <div class="container p-4">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <h3>تعديل الخطة الشهرية</h3>--}}
{{--                <form action="{{ route('employee.update-plan', $plan) }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    @method('PUT')--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="date">التاريخ:</label>--}}
{{--                        <input type="text" class="form-control" id="date" name="date" value="{{ $plan->start }}" readonly>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="schools">المدارس:</label>--}}
{{--                        <select class="form-control" id="schools" name="schools[]" multiple>--}}
{{--                            @foreach ($schools as $school)--}}
{{--                                <option value="{{ $school->id }}" @if (in_array($school->id, $selectedSchools)) selected @endif>{{ $school->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--@endsection--}}
@extends('layouts.master')

@section('content')
    <div class="container p-4">
        {{-- Show errors --}}
                <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <h3>تعديل الخطة الشهرية</h3>
                    </div>

                </div>
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
                                <option value="{{ $school->id }}" @if ($school->id == $selectedSchool) selected @endif>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success mt-4">حفظ التعديلات</button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
