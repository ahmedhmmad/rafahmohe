@extends('layouts.master')

@section('content')
<style>
    .row-unmatched {
        background-color: #f2dede; /* Red background color for unmatched rows */
        color: #a94442; /* Text color for unmatched rows */
    }

    .row-unmatched strong {
        font-weight: bold;
    }
    .row-matched {
     background-color: #d4edda; /* Light green background color for matched rows */
     color: #155724; /* Text color for matched rows */
      }
    .unmatched-school {
        font-weight: bold;
    }
</style>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <h3>عرض الخطة مقابل الزيارات الفعلية
                        <span class=" text-danger">
                            @php
                        $userName = \App\Models\User::where('id', $id)->first()->name;
 @endphp
                            {{ $userName}}

                        </span>
                        </h3>
                    </div>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.plan-vs-actual', $id) }}">
                        <div class="row py-4">
                            <div class="col-md-4">
                                <label for="month" class="form-label"><strong>الشهر</strong></label>
                                <select class="form-select" name="month" id="month" aria-label="Default select example">
                                    <option value="" selected>اختر الشهر</option>
                                    <option value="1">يناير</option>
                                    <option value="2">فبراير</option>
                                    <option value="3">مارس</option>
                                    <option value="4">أبريل</option>
                                    <option value="5">مايو</option>
                                    <option value="6">يونيو</option>
                                    <option value="7">يوليو</option>
                                    <option value="8">أغسطس</option>
                                    <option value="9">سبتمبر</option>
                                    <option value="10">أكتوبر</option>
                                    <option value="11">نوفمبر</option>
                                    <option value="12">ديسمبر</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="year" class="form-label"><strong>السنة</strong></label>
                                <select class="form-select" name="year" id="year" aria-label="Default select example">
{{--                                    <option value="" selected>اختر السنة</option>--}}
                                    <option value="2023">2023</option>
                                    <!-- Add options for the desired years -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary mt-4 px-lg-4">عرض</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card mt-2">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>اليوم</th>
                                <th>التاريخ</th>
                                <th>المدارس المخططة</th>
                                <th>الزيارات الفعلية</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $item)
                                @php
                                    $plannedSchoolsArray = explode(', ', $item['planned_schools']);
                                    $actualVisitsArray = explode(', ', $item['actual_visits']);
                                    $unmatchedSchools = array_diff($plannedSchoolsArray, $actualVisitsArray);
                                @endphp

                                <tr class="{{ empty($unmatchedSchools) ? 'row-matched' : 'row-unmatched' }}">
                                    <td>{{ $item['day'] }}</td>
                                    <td><strong>{{ $item['date'] }}</strong></td>
                                    <td>
                                        @foreach ($plannedSchoolsArray as $school)
                                            @if (in_array($school, $unmatchedSchools))
                                                <strong class="unmatched-school">{{ $school }}</strong>,
                                            @else
                                                {{ $school }},
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($actualVisitsArray as $visit)
                                            @if (in_array($visit, $unmatchedSchools))
                                                <strong class="unmatched-school">{{ $visit }}</strong>,
                                            @else
                                                {{ $visit }},
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
