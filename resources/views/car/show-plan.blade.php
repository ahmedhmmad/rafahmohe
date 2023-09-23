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
                        <h3>عرض حركات السيارات</h3>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body">

                        <form method="GET" action="{{ route('car.show-plan') }}">
                            @csrf

                            <div class="row py-4">
                                <div class="col-md-4">
                                    <label for="month" class="form-label"><strong>الشهر</strong></label>
                                    <select class="form-select" name="month" id="month" aria-label="Default select example">
                                        @php
                                            $arabicMonths = [
                                                'يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو',
                                                'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
                                            ];
                                        @endphp

                                        @foreach (range(1, 12) as $monthNumber)
                                            <option value="{{ $monthNumber }}" @if ($selectedMonth == $monthNumber) selected @endif>
                                                {{ $arabicMonths[$monthNumber - 1] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="year" class="form-label"><strong>السنة</strong></label>
                                    <select class="form-select" name="year" id="year" aria-label="Default select example">
                                        @foreach (range(now()->year - 3, now()->year + 3) as $year)
                                            <option value="{{ $year }}" @if ($selectedYear == $year) selected @endif>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary mt-4 px-lg-4">عرض</button>
                                </div>
                            </div>
                        </form>

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th><strong>اليوم</strong></th>
                                <th><strong>التاريخ</strong></th>
                                <th><strong>اتجاه السيارة</strong></th> <!-- Updated column header -->
                                <th><strong>عمليات</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $previousDate = null;
                                $currentDate = null;
                                $previousDayName = null;
                                $dayNames = ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'];
                                $currentDayName = null;
                            @endphp
                            @foreach ($carMovements as $index => $carMovement)
                                @php
                                    $currentDate = $carMovement->date;
                                    $currentDayName = $dayNames[date('N', strtotime($carMovement->date)) - 1];
                                    $directionsTranslations = [
                                        'east' => 'شرق رفح',
                                        'west' => 'غرب رفح',
                                        'home' => 'البلد',
                                        'far' => 'أسرار - المسمية - غسان - مرمرة',
                                        'special' => 'الشوكة',
                                        'free' => 'بدون',
                                    ];
                                @endphp

                                {{-- Display car movements --}}
                                <tr
                                    {{-- Add your styling or border logic here --}}
                                    @if ($previousDate !== $currentDate)
                                        style="border-top: 2px solid rgba(8,117,95,0.85);"
                                    @elseif (isset($carMovements[$index + 1]) && $carMovements[$index + 1]->date === $currentDate)
                                        style="border-top: 2px dot-dot-dash rgba(143,187,178,0.18);"
                                    @endif
                                >
                                    <td>
                                        @if ($currentDayName !== $previousDayName)
                                            {{ $currentDayName }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($currentDate !== $previousDate)
                                            <strong>{{ $currentDate }}</strong>
                                        @endif
                                    </td>
                                    <td>{{ $directionsTranslations[$carMovement->direction] }}</td> <!-- Display car direction -->
                                    <td>
                                        <a href="{{ route('car.edit-car-movement', $carMovement->id) }}" class="btn btn-outline-primary">تعديل</a>
                                        <a href="{{ route('car.delete-car-movement', $carMovement->id) }}" class="btn btn-outline-danger">حذف</a>
                                        <a href="{{ route('car.add-car-movement', $carMovement->id) }}" class="btn btn-outline-info">اضافة</a>
                                    </td>
                                </tr>

                                @php
                                    $previousDate = $currentDate;
                                    $previousDayName = $currentDayName; // Update the previous day name for the next iteration
                                @endphp
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
