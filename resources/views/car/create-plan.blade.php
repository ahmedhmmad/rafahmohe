
@extends('layouts.master')

@section('content')
    <style>
        .dotted-border {
            border-bottom: 1px dotted #000;
            padding-bottom: 20px;
        }
        .day-name {
            color: #50a14f;
            font-weight: bold;
        }
        .school-label {
            color: #1e0ace;
        }
        .highlight-school {
            background-color: #cae0d2;
        }
        .disabled-option {
            color: grey;
        }
        .active-option {
            color: black;
            /*font-weight: bold;*/
        }
        .school-label,
        .js-example-basic-multiple {
            display: block;
            width: 100%; /* Optional, but ensures they take up the full width */
        }
    </style>

    <div class="p-4">
        <h2 class="p-4">إدخال الخطة الشهرية<span style="color:#167ce8;padding-right: 1em;">{{ $year }}/{{ $month }}</span></h2>
        <div class="container">
            <div class="row">
                <form method="POST" action="{{ route('car.store-plan') }}" onsubmit="return validateForm()">
                    @csrf

                    @php
                        $startOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);
                        $daysInMonth = $startOfMonth->daysInMonth;
                        $dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                        $directionsTranslations = [
                            'east' => 'شرق رفح',
                            'west' => 'غرب رفح',
                            'home' => 'البلد',
                            'far' => 'أسرار - المسمية - غسان - مرمرة',
                            'special' => 'الشوكة',
                            'free' => 'بدون',
                        ];

                    @endphp

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @if ($startOfMonth->dayOfWeek !== 5 && $startOfMonth->dayOfWeek !== 6)
                            <div class="col-md-10 dotted-border mb-2">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-5">
                                            <label class="day-name">{{ $dayNames[$startOfMonth->dayOfWeek] }}</label>
                                            <input type="text" class="form-control" value="{{ $startOfMonth->isoFormat('D/MM/YYYY') }}" readonly>
                                            <input type="hidden" name="days[{{ $startOfMonth->format('Y-m-d') }}][date]" value="{{ $startOfMonth->format('Y-m-d') }}">
                                            @if ($errors->has($startOfMonth->format('Y-m-d')))
                                                <ul style="list-style: none; color: red;">
                                                    @foreach ($errors->get($startOfMonth->format('Y-m-d')) as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                        <div class="col-7">
                                            <label class="car-plan-option">الاتجاه</label>
                                            <select class="js-example-basic-multiple" name="days[{{ $startOfMonth->format('Y-m-d') }}][directions][]" multiple="multiple" required>
                                                @foreach($directionsTranslations as $direction => $translation)
                                                    <option value="{{ $direction }}">
                                                        {{ $translation }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                        @php
                            $startOfMonth->addDay();
                        @endphp
                    @endfor

                    <div class="col-md-10 mt-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

