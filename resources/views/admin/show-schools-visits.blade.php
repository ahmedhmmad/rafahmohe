@extends('layouts.master')

@section('content')

    <div class="container py-2">
        <h2 >سجل زيارات المدارس</h2>
        <div class="card py-2">
            <div class="card-body">
                <form method="GET" action="{{route('admin.show-schools-visits')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="selected_department_id" class="form-label fw-bold">اختر القسم</label>
                            <select name="selected_department_id" id="selected_department_id" class="form-select" dir="rtl">
                                <option value="">اختر القسم</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('selected_department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="selected_user_id" class="form-label fw-bold">اختر المستخدم</label>
                            <select name="selected_user_id" id="selected_user_id" class="form-select" dir="rtl">
                                <option value="">اختر المستخدم</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('selected_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                    <div class="row">

                        <div class="form-group col-md-4">
                            <label for="school">المدرسة</label>
                            <select name="school" id="school" class="form-control form-select">
                                <option value="">اختر المدرسة</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="month">الشهر</label>
                            <select name="month" id="month" class="form-control form-select">
                                <option value="">اختر الشهر</option>
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
                        <div class="form-group col-md-2">
                            <label for="year">السنة</label>
                            <select name="year" id="year" class="form-control form-select">
                                <option value="">اختر السنة</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>

                            </select>
                        </div>
                        <div class="form-group col-md-2"><button type="submit" class="btn btn-primary mt-4 px-lg-4">بحث</button></div>

                    </div>

                </form>
            </div>
        </div>
        <!-- Excel Export Button -->
        <div class="mt-3">
            @php
                $exportRouteParams = [
                    'selected_department_id' => request('selected_department_id'),
                    'selected_user_id' => request('selected_user_id'),
                    'school' => request('school'),
                    'month' => request('month'),
                    'year' => request('year')
                ];
            @endphp

            <a href="{{ route('admin.export-schools-visits', $exportRouteParams) }}" class="btn btn-success">تصدير إلى Excel</a>
        </div>
    </div>

    </div>

    <!-- TimeLine Modal -->
    <div class="modal" id="timelineModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">المخطط الزمني</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="loading-spinner">
                        <img src="{{url('/img/loading.gif')}}" alt="Loading..." width="50" height="50">
                    </div>
                    <!-- Timeline details will be displayed here -->
                    <div id="timelineDetails">
                        <!-- Timeline data will be added dynamically here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>



    <div class="container py-1">
        <div class="card py-2">
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead class="table-primary">
                    <tr>
                        <th><strong>اليوم</strong></th>
                        <th><strong>التاريخ</strong></th>
                        <th><strong>اسم المستخدم</strong></th>
                        <th><strong>المسمى الوظيفي</strong></th>
                        <th><strong>اسم المدرسة</strong></th>
                        <th><strong>وقت الحضور</strong></th>
                        <th><strong>وقت الانصراف</strong></th>
                        <th><strong>الهدف من الزيارة</strong></th>
                        <th><strong>الأنشطة المنفذة</strong></th>
                    </tr>

                    </thead>
                    <tbody>

                    @foreach ($schoolVisits as $schoolVisit)
                        @php
                            $start = \Carbon\Carbon::parse($schoolVisit->visit_date)->locale('ar');
                        @endphp

                        <tr>
                            <td><h6>{{ $start->translatedFormat('l') }}</h6></td>
                            <td>
                                {{--                                <input type="date" name="visit_date[]" class="form-control date-input" required>--}}
                                {{--                                <input type="hidden" name="day[]" class="day-input">--}}
                                <h6>{{ $start->translatedFormat('Y-m-d') }}</h6>
                                <input type="hidden" name="visit_date[]" value="{{ $start->translatedFormat('Y-m-d') }}">
                                <input type="hidden" name="day[]" value="{{ $start->translatedFormat('l') }}">
                            </td>
                            <td>
                                <h6>{{ $schoolVisit->user->name }}</h6>
                                <div class="view-timeline-link" data-user-id="{{ $schoolVisit->user_id }}" data-visit-date="{{ $schoolVisit->visit_date }}">المخطط الزمني</div>
                                <input type="hidden" name="user_name[]" class="form-control user-name-input" required>
                                <input type="hidden" name="user_id[]" class="user-id-input">
                            </td>
                            <td>
                                <h6>{{$schoolVisit->user->job_title}}</h6>
                                <input type="hidden" name="job_title[]" class="form-control job-title-input" readonly>
                            </td>
                            <td>
                                <h6>{{$schoolVisit->school->name}}</h6>


                            </td>
                            <td>
                                {{$schoolVisit->coming_time}}

                            </td>
                            <td>

                                {{$schoolVisit->leaving_time}}
                            </td>
                            <td>
                                <!-- Display limited characters for "Purpose" -->
                                <div class="limited-content" data-content="{{ $schoolVisit->purpose }}">{{ Str::limit($schoolVisit->purpose, 15) }}</div>
                                @if (strlen($schoolVisit->purpose) > 15)
                                    <a href="#" class="view-more-link">المزيد..</a>
                                @endif
                            </td>
                            <td>
                                <!-- Display limited characters for "Activities" -->
                                <div class="limited-content" data-content="{{ $schoolVisit->activities }}">{{ Str::limit($schoolVisit->activities, 15) }}</div>
                                @if (strlen($schoolVisit->activities) > 15)
                                    <a href="#" class="view-more-link">المزيد..</a>
                                @endif
                            </td>





                            {{--                            <td>--}}
                            {{--                                <textarea type="text" name="purpose[]" class="form-control" required></textarea>--}}
                            {{--                            </td>--}}
                            {{--                            <td>--}}
                            {{--                                <textarea name="activities[]" class="form-control" required></textarea>--}}
                            {{--                            </td>--}}
                        </tr>



                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col">
            @php
                $queryParams = [
                    'selected_department_id' => request('selected_department_id'),
                    'selected_user_id' => request('selected_user_id'),
                    'school' => request('school'),
                    'month' => request('month'),
                    'year' => request('year')
                ];
            @endphp

            <div class="demo-inline-spacing">
                <!-- Basic Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item {{ $schoolVisits->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $schoolVisits->appends($queryParams)->previousPageUrl() }}" aria-label="Previous">
                            <i class="tf-icon bx bx-chevrons-right">السابق</i>
                            </a>
                        </li>
                        @for($i = 1; $i <= $schoolVisits->lastPage(); $i++)
                            <li class="page-item {{ $schoolVisits->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $schoolVisits->appends($queryParams)->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $schoolVisits->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $schoolVisits->appends($queryParams)->nextPageUrl() }}" aria-label="Next">
                            <i class="tf-icon bx bx-chevron-left">التالي</i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!--/ Basic Pagination -->
            </div>
        </div>
    </div>
<style>

    .view-timeline-link {
        display: none;
        cursor: pointer;
        color: #007bff;
        text-decoration: underline;
    }

    .view-timeline-link:hover {
        color: #0056b3;
    }


    /* Style to display link on row hover */
    tr:hover .view-timeline-link {
        display: inline-block;
        text-decoration: none;
      border-top: #cae0d2 2px dashed;
    }
    .loading-spinner {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }
</style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script>



        $(document).ready(function() {
            function showLoadingSpinner() {
                console.log('show');
                $('.loading-spinner').show();
            }

            // Function to hide the loading spinner
            function hideLoadingSpinner() {
                console.log('hide');
                $('.loading-spinner').hide();
            }

                 // Handle the click event of the "Read More" link
            $('.view-more-link').click(function() {
                var contentDiv = $(this).prev('.limited-content');
                var fullContent = contentDiv.data('content');
                var linkText = $(this).text().toLowerCase();

                if (linkText === 'المزيد..') {
                    contentDiv.text(fullContent);
                    $(this).text('أقل..');
                } else {
                    contentDiv.text(fullContent.substring(0, 15) + '...');
                    $(this).text('المزيد..');
                }
            });

                // Handle the click event of the "View Timeline" link
                $('.view-timeline-link').click(function() {
                    var date = $(this).data('visit-date');
                    var userId = $(this).data('user-id');
                    showLoadingSpinner();

                    // Send an AJAX request to fetch the user timeline
                    $.ajax({
                        url: '/admin/get-user-timeline', // Update the URL if needed
                        method: 'GET',
                        data: { date: date, user_id: userId },
                        success: function(response) {
                            hideLoadingSpinner();
                            // Clear the previous timeline details
                            $('#timelineDetails').html('');

                            // Append the timeline content to the modal body
                            $('#timelineDetails').html(response.timeline_content);

                            // Show the modal
                            $('#timelineModal').modal('show');
                        },
                        error: function(error) {
                            hideLoadingSpinner();
                            console.log(error);
                        }
                    });
                });

            $('#selected_department_id').change(function() {
                var departmentId = $(this).val();

                // Send an AJAX request to fetch the department users
                $.ajax({
                    url: '/fetch-department-users',
                    method: 'GET',
                    data: { department_id: departmentId },
                    success: function(response) {
                        // Clear the previous options in the users select box
                        $('#selected_user_id').empty();

                        // Add the "اختر المستخدم" option
                        var selectOption = $('<option>', { value: '', text: 'اختر الموظف' });
                        $('#selected_user_id').append(selectOption);

                        // Add the new options based on the department users
                        response.users.forEach(function(user) {
                            var option = $('<option>', { value: user.id, text: user.name });
                            $('#selected_user_id').append(option);
                        });
                    }
                });
            });


        });
    </script>


@endsection
