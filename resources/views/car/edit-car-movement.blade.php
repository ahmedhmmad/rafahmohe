@extends('layouts.master')

@section('content')

    <div class="container py-4">
        {{-- Show errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li style="color: red;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <h3>تعديل اتجاه السيارة</h3>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body">
                        {{-- Add your car movement editing form here --}}
                        <form method="POST" action="{{ route('car.update-car-movement', $carMovement->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="date">تاريخ الحركة</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $carMovement->date }}">
                            </div>

                            {{-- Add your form fields for editing here --}}
                            <div class="form-group">
                                <label for="direction">اتجاه السيارة</label>
                                <select class="form-control" id="direction" name="direction">
                                    {{-- Populate options based on your directionsTranslations --}}
                                    @foreach ($directionsTranslations as $directionKey => $directionValue)
                                        <option value="{{ $directionKey }}" {{ $carMovement->direction === $directionKey ? 'selected' : '' }}>
                                            {{ $directionValue }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Add more form fields as needed --}}

                            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
