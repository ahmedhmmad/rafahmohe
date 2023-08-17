@extends('layouts.master')

@section('content')
    <div class="container py-1">
        <div class="card py-2">
            <!-- Modal for Excel Export -->
            <div class="modal fade" id="showExportExcelModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">تصدير إكسل</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('exports.exportExcel') }}" method="GET">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="month" class="form-label">اختر الشهر</label>
                                    <select name="month" id="month" class="form-control">
                                        <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>يناير</option>
                                        <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>فبراير</option>
                                        <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>مارس</option>
                                        <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>ابريل</option>
                                        <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>مايو</option>
                                        <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>يونيو</option>
                                        <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>يوليو</option>
                                        <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>اغسطس</option>
                                        <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>سبتمبر</option>
                                        <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>اكتوبر</option>
                                        <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>نوفمبر</option>
                                        <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>ديسمبر</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="orderBy" class="form-label">ترتيب حسب</label>
                                    <select name="orderBy" id="orderBy" class="form-control">
                                        <option value="date">التاريخ</option>
                                        <option value="department">القسم</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">الغاء</button>
                                <button type="submit" class="btn btn-primary">تصدير</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <a href="{{route('school.add-visits')}}">
                <button type="button" class="btn btn-success">
                    <span class="tf-icons bx bx-plus-circle"></span>&nbsp; إضافة زيارة
                </button>
                </a>
{{--                export excel button--}}
{{--                <a href="{{ route('exports.exportExcel') }}">--}}
{{--                    <button type="button" class="btn btn-success">--}}
{{--                        <span class="tf-icons bx bx-export"></span>&nbsp; تصدير إكسل--}}
{{--                    </button>--}}
{{--                </a>--}}
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#showExportExcelModal">
                    <span class="tf-icons bx bx-export"></span>&nbsp; تصدير إكسل
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-wrap">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">

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
