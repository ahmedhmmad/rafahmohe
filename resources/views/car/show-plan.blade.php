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
                        <h3>تعديل الخطة الشهرية</h3>
                    </div>

                </div>

                <div class="card mt-2">
                    <div class="card-body">


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
                            @foreach ($carMovements as $index=> $carMovement)
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
                                        {{-- Add your action links for editing and deleting car movements --}}
{{--                                        <a href="{{ route('employee.edit-car-movement', $carMovement->id) }}" class="btn btn-outline-primary">تعديل</a>--}}
{{--                                        <a href="{{ route('employee.delete-car-movement', $carMovement->id) }}" class="btn btn-outline-danger">حذف</a>--}}
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
    </div>

@endsection
