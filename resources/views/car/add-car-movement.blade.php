@extends('layouts.master')

@section('content')
    <div class="container py-4">
        {{-- Display any validation errors here --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li style="color: red;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                إضافة حركة سيارة جديدة
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('car.store-day-car-movement') }}">
                    @csrf

                    {{-- Date Input --}}
                    <div class="form-group">
                        <label for="date">تاريخ الحركة</label>
                        <input type="date" class="form-control" id="date" name="date" required value="{{ $carMovement->date }}">
                    </div>

                    {{-- Direction Select --}}
                    <div class="form-group">
                        <label for="direction">اتجاه السيارة</label>
                        <select class="form-control" id="direction" name="direction" required>
                            @foreach ($directionsTranslations as $key => $translation)
                                <option value="{{ $key }}">{{ $translation }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Add more form fields as needed --}}

                    <button type="submit" class="btn btn-primary">إضافة الحركة</button>
                </form>
            </div>
        </div>
    </div>
@endsection
