@extends('layouts.master')

@section('content')
    <div class="p-4">
        <h2 class="p-4">بحث</h2>
        <div class="container py-2">
            <div class="card px-4">
                <form method="GET" action="{{ route('admin.search-plan-date') }}">
                    <div class="row py-4">
                        <div class="col-md-4">
                            <label for="visit_date" class="form-label"><strong>تاريخ الزيارة</strong></label>
                            <input type="date" class="form-control" name="visit_date" id="visit_date">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4 px-lg-4" style="transform:translatex(0px) translatey(5px);" >بحث</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blade template code -->
        @if(isset($visits))
            <div class="container py-2">
                <div class="card px-4">
                    <h2 class="p-4">نتائج البحث</h2>
                    @if($visits->isEmpty())
                        <div class="p-4">لا توجد نتائج للبحث.</div>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">اسم المدرسة</th>
                                <th scope="col">تاريخ الزيارة</th>
                                <th scope="col">الزائرون</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($visits as $index => $visit)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $visit->schools->name }}</td>
                                    <td>{{ $visit->start }}</td>
                                    <td>{{ $visit->user->name }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
