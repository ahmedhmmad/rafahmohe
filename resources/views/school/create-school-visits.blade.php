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
                            <th><strong>اليوم</strong></th>
                            <th><strong>التاريخ</strong></th>
                            <th><strong>الموظف</strong></th>
                            <th><strong>المسمى الوظيفي</strong></th>
                            <th><strong>توقيت الحضور</strong></th>
                            <th><strong>توقيت الانصراف</strong></th>
                            <th><strong>الهدف من الزيارة</strong></th>
                            <th><strong>الأنشطة المنفذة</strong></th>
                            <th><strong>الإجراءات</strong></th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($schoolVisits as $schoolVisit)
                            @php
                                $start = \Carbon\Carbon::parse($schoolVisit->visit_date)->locale('ar');
                            @endphp
                            <tr>
                                <td><h6>{{ $start->translatedFormat('l') }}</h6></td>
                                <td>{{ $schoolVisit->visit_date }}</td>
                                <td>{{ $schoolVisit->user->name }}</td>
                                <td>{{ $schoolVisit->user->job_title }}</td>
                                <td>{{ $schoolVisit->coming_time }}</td>
                                <td>{{ $schoolVisit->leaving_time }}</td>
                                <td>{{ $schoolVisit->purpose }}</td>
                                <td>{{ $schoolVisit->activities }}</td>
                                <td>
{{--                                     Delete buttons --}}


                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $schoolVisit->id }}"
                                            >
                                                حذف
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal modal-fade" id="deleteModal{{ $schoolVisit->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form class="modal-content" method="POST" action="{{ route('school.delete-visit', $schoolVisit->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalTopTitle"> حذف الزيارة </h5>

                                                        </div>
                                                        <div class="modal-body">
                                                            <p>هل أنت متأكد من حذف الزيارة؟</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-danger">حذف</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>



                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col">

                            <div class="demo-inline-spacing">
                                <!-- Basic Pagination -->
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <li class="page-item {{ $schoolVisits->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $schoolVisits->previousPageUrl() }}" aria-label="Previous">
                                                <i class="tf-icon bx bx-chevrons-right">السابق</i>
                                            </a>
                                        </li>
                                        @for($i = 1; $i <= $schoolVisits->lastPage(); $i++)
                                            <li class="page-item {{ $schoolVisits->currentPage() == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $schoolVisits->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ $schoolVisits->hasMorePages() ? '' : 'disabled' }}">
                                            <a class="page-link" href="{{ $schoolVisits->nextPageUrl() }}" aria-label="Next">
                                                <i class="tf-icon bx bx-chevron-left">التالي</i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                <!--/ Basic Pagination -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
