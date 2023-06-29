@extends('layouts.master')

@section('content')
    <div class="container py-1">
        <div class="card py-2">
            <h2>إدخال زيارات المدرسة</h2>
            <h4 class="text-info">ملاحظة:  يتم عرض الزيارات حسب الخطة أولاً</h4>
            <form action="{{ route('school.store-visits') }}" method="POST">
                @csrf
                <table class="table">
                    <thead>
                    <tr>
                        <th>اليوم</th>
                        <th>التاريخ</th>
                        <th>اسم المستخدم</th>
                        <th>المسمى الوظيفي</th>
                        <th>وقت الدخول</th>
                        <th>وقت الخروج</th>
                        <th>اضافة تفاصيل الزيارة</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($plans as $plan)
                        @php
                            $start = \Carbon\Carbon::parse($plan->start)->locale('ar');
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
                                <h6>{{$plan->user->name}}</h6>
                                <input type="hidden" name="user_name[]" class="form-control user-name-input" required>
                                <input type="hidden" name="user_id[]" class="user-id-input">
                            </td>
                            <td>
                                <h6>{{$plan->user->job_title}}</h6>
                                <input type="hidden" name="job_title[]" class="form-control job-title-input" readonly>
                            </td>
                            <td>
                                <input type="time" name="coming_time[]" class="form-control"  required>
                            </td>
                            <td>
                                <input type="time" name="leaving_time[]" class="form-control" required>
                            </td>
                            <td>

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purposeActivitiesModal{{ $loop->index }}">+</button>
                            </td>




                            {{--                            <td>--}}
{{--                                <textarea type="text" name="purpose[]" class="form-control" required></textarea>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <textarea name="activities[]" class="form-control" required></textarea>--}}
{{--                            </td>--}}
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="purposeActivitiesModal{{ $loop->index }}" tabindex="-1" aria-labelledby="purposeActivitiesModalLabel{{ $loop->index }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="purposeActivitiesModalLabel{{ $loop->index }}">الهدف والأنشطة</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="purposeTextarea{{ $loop->index }}" class="form-label">الهدف من الزيارة</label>
                                            <textarea class="form-control" id="purposeTextarea{{ $loop->index }}" name="purpose[]" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="activitiesTextarea{{ $loop->index }}" class="form-label">الأنشطة المنفذة</label>
                                            <textarea class="form-control" id="activitiesTextarea{{ $loop->index }}" name="activities[]" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                                        <button type="button" class="btn btn-primary" onclick="setPurposeActivitiesValue({{ $loop->index }})">حفظ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    </tbody>
                </table>
                <button class="btn btn-primary" id="add-row">إضافة صف</button>
                <button type="submit" class="btn btn-success">حفظ</button>

            </form>
        </div>
    </div>

    <script>
        // Function to get the Arabic day from the date
        function getArabicDay(date) {
            var arabicDay = date.format('dddd');
            return arabicDay;
        }

        // Event listener for date input
        var dateInputs = document.querySelectorAll('.date-input');
        dateInputs.forEach(function(dateInput) {
            dateInput.addEventListener('change', function() {
                var selectedDate = new Date(this.value);
                var arabicDay = getArabicDay(selectedDate);
                var dayInput = this.parentNode.querySelector('.day-input');
                dayInput.value = arabicDay;
            });
        });

        // Event listener for user name input
        var userNameInputs = document.querySelectorAll('.user-name-input');
        userNameInputs.forEach(function(userNameInput) {
            userNameInput.addEventListener('input', function() {
                var userName = this.value;
                var jobTitleInput = this.parentNode.parentNode.querySelector('.job-title-input');

                // Make an AJAX request to fetch matching users
                fetch('{{ route("users.search") }}?name=' + userName)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        // Display the suggestions in a list
                        var suggestionsList = document.createElement('ul');
                        suggestionsList.classList.add('suggestions-list');
                        data.forEach(function(user) {
                            var listItem = document.createElement('li');
                            listItem.classList.add('suggestion-item');
                            listItem.innerText = user.name;
                            suggestionsList.appendChild(listItem);

                            // Event listener for selecting a user from the list
                            listItem.addEventListener('click', function() {
                                userNameInput.value = user.name;
                                jobTitleInput.value = user.job_title;
                                var userIdInput = userNameInput.parentNode.querySelector('.user-id-input');
                                userIdInput.value = user.id;
                                suggestionsList.remove();
                            });
                        });

                        // Remove any existing suggestions list
                        var existingSuggestionsList = userNameInput.parentNode.querySelector('.suggestions-list');
                        if (existingSuggestionsList) {
                            existingSuggestionsList.remove();
                        }

                        // Append the suggestions list to the parent container
                        userNameInput.parentNode.appendChild(suggestionsList);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        });
        var rowIndex = 0;

        // Event listener for adding a new row
        document.getElementById('add-row').addEventListener('click', function() {
            var table = document.querySelector('.table');
            var newRow = table.insertRow();
            rowIndex++;

            newRow.innerHTML = `
            <td><input type="text" name="day[]" class="form-control" required></td>
            <td>
                <input type="date" name="visit_date[]" class="form-control date-input" required>
                <input type="hidden" name="day[]" class="day-input">
            </td>
            <td>
                <input type="text" name="user_name[]" class="form-control user-name-input" required>
                <input type="hidden" name="user_id[]" class="user-id-input">
            </td>
            <td>
                <input type="text" name="job_title[]" class="form-control job-title-input" readonly>
            </td>
            <td><input type="time" name="coming_time[]" class="form-control" required></td>
            <td><input type="time" name="leaving_time[]" class="form-control" required></td>
            <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purposeActivitiesModal${rowIndex}">+</button>
            </td>

            <div class="modal fade" id="purposeActivitiesModal${rowIndex}" tabindex="-1" aria-labelledby="purposeActivitiesModalLabel${rowIndex}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="purposeActivitiesModalLabel${rowIndex}">الهدف والأنشطة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="purposeTextarea${rowIndex}" class="form-label">الهدف من الزيارة</label>
                                <textarea class="form-control" id="purposeTextarea${rowIndex}" name="purpose[]" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="activitiesTextarea${rowIndex}" class="form-label">الأنشطة المنفذة</label>
                                <textarea class="form-control" id="activitiesTextarea${rowIndex}" name="activities[]" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                            <button type="button" class="btn btn-primary" onclick="setPurposeActivitiesValue(${rowIndex})">حفظ</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // Attach event listeners to the newly added inputs
            var newDateInput = newRow.querySelector('.date-input');
            var newDayInput = newRow.querySelector('.day-input');
            var newUserNameInput = newRow.querySelector('.user-name-input');
            var newJobTitleInput = newRow.querySelector('.job-title-input');

            newDateInput.addEventListener('change', function() {
                var selectedDate = new Date(this.value);
                var arabicDay = getArabicDay(selectedDate);
                newDayInput.value = arabicDay;
            });

            newUserNameInput.addEventListener('input', function() {
                var userName = this.value;

                // Make an AJAX request to fetch matching users
                fetch('{{ route("users.search") }}?name=' + userName)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        // Display the suggestions in a list
                        var suggestionsList = document.createElement('ul');
                        suggestionsList.classList.add('suggestions-list');
                        data.forEach(function(user) {
                            var listItem = document.createElement('li');
                            listItem.style.listStyleType = "none";
                            listItem.classList.add('suggestion-item');
                            listItem.innerText = user.name;
                            suggestionsList.appendChild(listItem);

                            // Event listener for selecting a user from the list
                            listItem.addEventListener('click', function() {
                                newUserNameInput.value = user.name;
                                newJobTitleInput.value = user.job_title;
                                var newUserIdInput = newUserNameInput.parentNode.querySelector('.user-id-input');
                                newUserIdInput.value = user.id;
                                suggestionsList.remove();
                            });
                        });

                        // Remove any existing suggestions list
                        var existingSuggestionsList = newUserNameInput.parentNode.querySelector('.suggestions-list');
                        if (existingSuggestionsList) {
                            existingSuggestionsList.remove();
                        }

                        // Append the suggestions list to the parent container
                        newUserNameInput.parentNode.appendChild(suggestionsList);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        });

        function setPurposeActivitiesValue(index) {
            var purposeTextarea = document.getElementById('purposeTextarea' + index);
            var activitiesTextarea = document.getElementById('activitiesTextarea' + index);
            var purposeInput = document.getElementById('purpose' + index);
            var activitiesInput = document.getElementById('activities' + index);
            purposeInput.value = purposeTextarea.value;
            activitiesInput.value = activitiesTextarea.value;
            $('#purposeActivitiesModal' + index).modal('hide');
        }
    </script>
@endsection
