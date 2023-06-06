@extends('layouts.master')

@section('content')

    <div class="container p-4">
        <div class="row">
            <div class="col-md-12">
                <h3>إضافة مدرسة لليوم</h3>
                <form action="{{ route('employee.store-day') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="date">التاريخ:</label>
                        <input type="text" class="form-control" id="date" name="date" value="{{ $date }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="schools">المدارس:</label>
                        <select class="form-control" id="schools" name="schools[]" multiple>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </form>
            </div>
        </div>
    </div>

@endsection

