@extends('layouts.master')

@section('content')

    <div class="container p-4">
        <div class="row">
            <div class="col-md-12">
                <h3>تعديل الخطة الشهرية</h3>
                <form action="{{ route('employee.update-plan', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="date">التاريخ:</label>
                        <input type="text" class="form-control" id="date" name="date" value="{{ $plan->start }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="schools">المدارس:</label>
                        <select class="form-control" id="schools" name="schools[]" multiple>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}" @if (in_array($school->id, $selectedSchools)) selected @endif>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </form>
            </div>
        </div>
    </div>

@endsection
