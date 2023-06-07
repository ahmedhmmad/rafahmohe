@extends('layouts.master')

@section('content')

    <div class="p-4">
        <h2 class="p-4">إدخال الخطة الشهرية<span style="color:#167ce8;padding-right: 1em;">{{ $year }}/{{ $month }}</span></h2>
        <div class="container">
            <div class="row">
                <form method="POST" action="{{ route('employee.store-plan') }}">
                    @csrf
                    @php
                        $startOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);
                        $daysInMonth = $startOfMonth->daysInMonth;
                        $dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                    @endphp
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @if ($startOfMonth->dayOfWeek !== 5 && $startOfMonth->dayOfWeek !== 6)
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-5  border-bottom">
                                            <label>
                                                <strong style="color: #50a14f">{{ $dayNames[$startOfMonth->dayOfWeek] }}</strong>
                                            </label>
                                            <input type="text" class="form-control" value="{{ $startOfMonth->isoFormat('D/MM/YYYY') }}" readonly>
                                            <input type="hidden" name="days[{{ $startOfMonth->format('Y-m-d') }}][date]" value="{{ $startOfMonth->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-7">
                                            <label class="form-label" style="color: #1e0ace">المدرسة</label>
                                            <select name="days[{{ $startOfMonth->format('Y-m-d') }}][schools][]" multiple class="form-select" id="schoolSelect{{ $day }}">
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
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

                    <div class="col-md-10">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
