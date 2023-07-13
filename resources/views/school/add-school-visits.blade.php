@extends('layouts.master')

<style>
    .suggestions-list {
        list-style-type: none;
        padding: 0;
        margin-top: 5px;
    }

    .suggestion-item {
        cursor: pointer;
        padding: 5px;
    }


</style>

@section('content')
    <div class="container py-2">
        <div class="card py2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="mb-4">إضافة زيارة مدرسية</h3>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('school.create-visits') }}">
                            <button type="button" class="btn btn-secondary" id="returnButton">
                                <span class="tf-icons bx bx-arrow-back"></span>&nbsp;العودة
                            </button>
                        </a>
                    </div>
                </div>

                <form id="schoolVisitForm" action="{{ route('school.store-visits') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="visitorName" class="form-label">اسم الزائر</label>
                        <input type="text" class="form-control user-name-input" id="visitorName" required>
                        <input type="hidden" name="user_id" class="user-id-input">
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="visitDate" class="form-label">تاريخ الزيارة</label>
                            <input type="date" class="form-control" id="visitDate" required>
                        </div>
                        <div class="col">
                            <label for="comingTime" class="form-label">وقت الحضور</label>
                            <input type="time" class="form-control" id="comingTime" required>
                        </div>
                        <div class="col">
                            <label for="leavingTime" class="form-label">وقت الانصراف</label>
                            <input type="time" class="form-control" id="leavingTime" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">الهدف من الزيارة</label>
                        <textarea class="form-control" id="purpose" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="activities" class="form-label">الأنشطة المنفذة</label>
                        <textarea class="form-control" id="activities" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">إضافة الزيارة</button>


                    <h6 class="text-secondary mt-2">
                        <span class="tf-icons bx bx-help-circle"></span>&nbsp;ملاحظة: بعد الانتهاء من إضافة الزيارات يمكنك الضغط على زر العودة
                    </h6>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->

    <script>
        // Function to clear the form fields
        function clearForm() {
            document.getElementById('visitorName').value = '';
            document.getElementById('visitDate').value = '';
            document.getElementById('comingTime').value = '';
            document.getElementById('leavingTime').value = '';
            document.getElementById('purpose').value = '';
            document.getElementById('activities').value = '';
        }

        // Event listener for form submission
        document.getElementById('schoolVisitForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Get form field values
            var visitorName = document.getElementById('visitorName').value;
            var userId=document.querySelector('.user-id-input').value;
            var visitDate = document.getElementById('visitDate').value;
            var comingTime = document.getElementById('comingTime').value;
            var leavingTime = document.getElementById('leavingTime').value;
            var purpose = document.getElementById('purpose').value;
            var activities = document.getElementById('activities').value;
            console.log(userId,visitDate,comingTime,leavingTime,purpose,activities);

            // Perform any necessary validation or data processing here

            // Make an AJAX request to submit the form
            fetch('{{ route('school.store-visits') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: userId,
                    visitorName: visitorName,
                    visitDate: visitDate,
                    comingTime: comingTime,
                    leavingTime: leavingTime,
                    purpose: purpose,
                    activities: activities
                })
            })
                .then(function(response) {
                    if (response.ok) {
                        // Clear the form fields
                        clearForm();

                        // Show success toast
                        // You can use a library like Bootstrap Toasts or any other notification library
                        // to show a success message. Here's an example using Bootstrap Toasts:
                        var toast = document.createElement('div');
                        toast.classList.add('toast', 'show');
                        toast.setAttribute('role', 'alert');
                        toast.setAttribute('aria-live', 'assertive');
                        toast.setAttribute('aria-atomic', 'true');
                        toast.innerHTML = '<div class="toast-header"><strong class="me-auto">Success</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body">تمت إضافة الزيارة بنجاح.</div>';
                        document.body.appendChild(toast);

                        // Return to the previous page or perform any other action
                        // You can add your own logic to redirect or handle the return button click event

                        // Optional: Clear the success toast after a few seconds
                        setTimeout(function() {
                            toast.remove();
                        }, 5000);
                    } else {

                       //print Body to console
                        response.json().then(function(data) {
                            console.log(data);
                        });
                       // throw new Error('Something went wrong');
                    }
                })
                .catch(function(error) {
                    console.log(error);
                    // Handle the error here
                });
        });

        // Event listener for return button click
        document.getElementById('returnButton').addEventListener('click', function() {
            // Implement your logic to return to the previous page or perform any other action
        });

        // Event listener for user name input
        var userNameInputs = document.querySelectorAll('.user-name-input');
        userNameInputs.forEach(function(userNameInput) {
            userNameInput.addEventListener('input', function() {
                var userName = this.value;

                // Make an AJAX request to fetch matching users
                fetch('{{ route("users.search") }}?name=' + userName)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        // Remove any existing suggestions list
                        var existingSuggestionsList = userNameInput.parentNode.querySelector('.suggestions-list');
                        if (existingSuggestionsList) {
                            existingSuggestionsList.remove();
                        }

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
                                console.log(user.name);
                                userNameInput.value = user.name;
                                //make the userName bold
                                userNameInput.style.fontWeight = 'bold';
                                // var jobTitleInput = userNameInput.parentNode.parentNode.querySelector('.job-title-input');
                                // jobTitleInput.value = user.job_title;
                                var userIdInput = userNameInput.parentNode.querySelector('.user-id-input');
                                userIdInput.value = user.id;
                                console.log(userIdInput.value);
                                //Remove List once a user is selected
                                suggestionsList.remove();
                                console.log('list is removed')
                            });
                        });

                        // Append the suggestions list to the parent container
                        userNameInput.parentNode.appendChild(suggestionsList);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        });

    </script>
@endsection
