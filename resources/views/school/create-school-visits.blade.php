@extends('layouts.master')

@section('content')
    <div class="container py-1">
        <div class="card py-2">
            <div class="card-header">
                <a href="{{route('school.add-visits')}}">
                <button type="button" class="btn btn-primary">
                    <span class="tf-icons bx bx-plus-circle"></span>&nbsp; إضافة زيارة
                </button>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-wrap">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>اليوم</th>
                            <th>التاريخ</th>
                            <th>الموظف</th>
                            <th>المسمى الوظيفي</th>
                            <th>توقيت الحضور</th>
                            <th>توقيت الانصراف</th>
                            <th>الهدف من الزيارة</th>
                            <th>الأنشطة المنفذة</th>
                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($schoolVisits as $schoolVisit)
                            <tr>
                                <td>{{ $schoolVisit->visit_date }}</td>
                                <td>{{ $schoolVisit->visit_date }}</td>
                                <td>{{ $schoolVisit->user->name }}</td>
                                <td>{{ $schoolVisit->user->job_title }}</td>
                                <td>{{ $schoolVisit->coming_time }}</td>
                                <td>{{ $schoolVisit->leaving_time }}</td>
                                <td>{{ $schoolVisit->purpose }}</td>
                                <td>{{ $schoolVisit->activities }}</td>
                                <td>
                                    {{-- Edit and Delete buttons --}}
                                    {{-- <a href="{{ route('schoolVisit.edit', $schoolVisit->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                    <a href="{{ route('schoolVisit.delete', $schoolVisit->id) }}" class="btn btn-danger btn-sm">حذف</a> --}}
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{-- Pagination links --}}
                        {{-- {{ $schoolVisits->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
