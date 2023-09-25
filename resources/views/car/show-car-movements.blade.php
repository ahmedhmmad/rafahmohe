
@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <h3>عرض حركات السيارات الشهرية</h3>
                    </div>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('car.show-car-movements') }}">
                        <div class="row py-4">
                            <!-- Month and Year selection form -->
                            <div class="col-md-4">
                                <label for="month" class="form-label"><strong>الشهر</strong></label>
                                <select class="form-select" name="month" id="month" aria-label="Default select example">
                                   <option value="" selected>اختر الشهر</option>
                                      <option value="1">يناير</option>
                                        <option value="2">فبراير</option>
                                        <option value="3">مارس</option>
                                        <option value="4">ابريل</option>
                                        <option value="5">مايو</option>
                                        <option value="6">يونيو</option>
                                        <option value="7">يوليو</option>
                                        <option value="8">اغسطس</option>
                                        <option value="9">سبتمبر</option>
                                        <option value="10">اكتوبر</option>
                                        <option value="11">نوفمبر</option>
                                        <option value="12">ديسمبر</option>

                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="year" class="form-label"><strong>السنة</strong></label>
                                <select class="form-select" name="year" id="year" aria-label="Default select example">
                                    <option value="" selected>اختر السنة</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary mt-4 px-lg-4">عرض</button>
                            </div>
                        </div>
                    </form>
                </div>

{{--                <a href="{{ route('exports.exportCarMovements', ['month' => now()->month, 'year' => now()->year]) }}" class="btn btn-success">--}}
{{--                    <i class="fas fa-file-export"></i>--}}
{{--                    تصدير حركات السيارات الشهرية--}}
{{--                </a>--}}
                <button class="btn btn-primary" onclick="printTable()">
                    <i class="fas fa-print"></i>
                    طباعة حركات السيارات الشهرية
                </button>

                <div class="card mt-2">
                    <div class="card-body">
                        <table class="table" id="printTable">
                            <thead>
                            <tr>
                                <th>اليوم</th>
                                <th>التاريخ</th>
                                <th>اتجاه السيارة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                use Carbon\Carbon;

                                $dayNameTranslations = [
                                    'Saturday' => 'السبت',
                                    'Sunday' => 'الأحد',
                                    'Monday' => 'الاثنين',
                                    'Tuesday' => 'الثلاثاء',
                                    'Wednesday' => 'الأربعاء',
                                    'Thursday' => 'الخميس',
                                    'Friday' => 'الجمعة',
                                ];
                                $directionsTranslations = [
                                    'east' => 'شرق رفح',
                                    'west' => 'غرب رفح',
                                    'home' => 'البلد',
                                    'far' => 'أسرار - المسمية - غسان - مرمرة',
                                    'special' => 'الشوكة',
                                    'free' => 'بدون',
                                ];

                                $previousDate = null;
                                $schoolsString = '';
                            @endphp

                            @foreach ($carMovements as $index => $carMovement)
                                @php
                                    $englishDayName = Carbon::parse($carMovement->date)->format('l');
                                    $currentDate = $carMovement->date;
                                    $currentDayName = $dayNameTranslations[$englishDayName];
                                @endphp

                                @if ($currentDate !== $previousDate && $previousDate !== null)
                                    <tr>
                                        <td>{{ $dayNameTranslations[Carbon::parse($previousDate)->format('l')] }}</td>
                                        <td><strong>{{ $previousDate }}</strong></td>
                                        <td>{{ $schoolsString }}</td>
                                    </tr>
                                    @php $schoolsString = ''; @endphp
                                @endif

                                @php
                                    $schoolsString .= ($schoolsString ? ', ' : '') . $directionsTranslations[$carMovement->direction];
                                    $previousDate = $currentDate;
                                @endphp

                                @if ($index === count($carMovements) - 1)
                                    <tr>
                                        <td>{{ $currentDayName }}</td>
                                        <td><strong>{{ $currentDate }}</strong></td>
                                        <td>{{ $schoolsString }}</td>
                                    </tr>
                                @endif
                            @endforeach



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
