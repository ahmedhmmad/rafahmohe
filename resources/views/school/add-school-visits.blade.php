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
/*Some modifications*/
    .user-inputs , .time-inputs ,.visit-details{
        background-color: rgba(203, 234, 177, 0.56);
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #c7c7c7;
        margin-bottom: 0.7rem;
    }

    /* Styling for labels */

    label {
        font-weight: bold;
    }

    /* Spacing */

    .myform-label {
        font-weight: 1000;
        margin-bottom: 15px;
    }

    h3 {

        margin-bottom: 20px;
    }

</style>

@section('content')
    <div class="container py-2">
        <div class="card py2">
            <div class="card-body">
                <div class="toast-container p-3">
                </div>
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
                    <div class="user-inputs">

                    <div class="mb-3">
                        <label for="visitorName" class="form-label">اسم الزائر</label>
                        <input type="text" class="form-control user-name-input" id="visitorName" required>
                        <input type="hidden" name="user_id" class="user-id-input">
                    </div>
                    <div class="mb-3">
                        <label for="addCompanion" class="form-label">إضافة مرافق</label>
                        <input type="checkbox" class="form-check-input" id="addCompanion">
                    </div>

                    <!-- Add the 'companionsContainer' element to the HTML -->
                    <div id="companionsContainer" style="display: none;"></div>

                    </div>
                    <div class="time-inputs">

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
                    </div>
                    <div class="visit-details">
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
    </div>


@endsection
@push('scripts')
    <script>



        //global variable to store the user id
        var userId = null;
        // Function to clear the form fields
        function clearForm() {
            document.getElementById('visitorName').value = '';
            document.getElementById('visitDate').value = '';
            document.getElementById('comingTime').value = '';
            document.getElementById('leavingTime').value = '';
            document.getElementById('purpose').value = '';
            document.getElementById('activities').value = '';
            document.getElementById('addCompanion').checked = false;
            document.getElementById('companionsContainer').style.display = 'none';
            document.getElementById('companionsContainer').innerHTML = '';

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
            console.log(userId);
            var selectedCompanions = [];
            var companionCheckboxes = document.querySelectorAll('input[name="companions[]"]:checked');
            companionCheckboxes.forEach(function(checkbox) {
                selectedCompanions.push(checkbox.value);
            });

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
                    activities: activities,
                    companions: selectedCompanions
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
                        toast.setAttribute('style', 'direction: rtl; background-color: lightgreen;');
                        toast.setAttribute('data-bs-delay', '2500');
                        toast.setAttribute('data-bs-animation', 'true');
                        toast.setAttribute('data-bs-autohide', 'true');
                        toast.innerHTML = '<div class="toast-body">تم تسجيل الزيارة بنجاح</div>';

                        // Append the toast element to the toast container
                        var toastContainer = document.querySelector('.toast-container');
                        toastContainer.appendChild(toast);

                        // Show the toast
                        var bootstrapToast = new bootstrap.Toast(toast);
                        bootstrapToast.show();

                        // Optional: Clear the toast after a few seconds

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
                        var user = data[0];
                        userId = user.id;

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
        }
        );

        function fetchDepartmentUsers(userId) {
            return new Promise(function(resolve, reject) {
                fetch('{{ route("school.fetch.department.users") }}?user_id=' + userId)
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(function(data) {
                        resolve(data);
                    })
                    .catch(function(error) {
                        reject(error);
                    });
            });
        }


            function displayDepartmentUsers(usersData, containerElement) {
                // Check if the container element exists
                if (!containerElement) {
                    console.error("Element with ID 'companionsContainer' not found.");
                    return;
                }

                // Clear the container first
                containerElement.innerHTML = '';
                var row;
                var col;
                var colCount = 0;

                // Create and append checkboxes for each department user
                usersData.forEach(function(user) {
                    if (colCount === 0) {
                        // Create a new row for every three users
                        row = document.createElement('div');
                        row.classList.add('row');
                        companionsContainer.appendChild(row);
                    }

                    // Create a Bootstrap column for each user
                    col = document.createElement('div');
                    col.classList.add('col-md-4'); // Adjust the column size as needed
                    row.appendChild(col);

                    // Create checkbox and label for the user
                    var checkBoxLabel = document.createElement('label');
                    checkBoxLabel.classList.add('checkbox-label');

                    var checkBoxInput = document.createElement('input');
                    checkBoxInput.type = 'checkbox';
                    checkBoxInput.name = 'companions[]';
                    checkBoxInput.value = user.id;
                    checkBoxLabel.appendChild(checkBoxInput);

                    var userNameSpan = document.createElement('span');
                    userNameSpan.textContent = user.name;
                    userNameSpan.addsStyle = 'padding-right: 10px;';
                    checkBoxLabel.appendChild(userNameSpan);

                    col.appendChild(checkBoxLabel);

                    colCount++;
                    if (colCount === 3) {
                        colCount = 0;
                    }
                });

                // Show the container once data is received and appended
                containerElement.style.display = 'block';
            }



        // Event listener for the "Add Companion" checkbox
        document.getElementById('addCompanion').addEventListener('change', function() {
            var companionsContainer = document.getElementById('companionsContainer');
            if (this.checked) {
                // Check if a user is selected
                if (userId !== null) {
                    // Fetch and display department users with checkboxes
                    fetchDepartmentUsers(userId)
                        .then(function(data) {
                            console.log(data);
                            displayDepartmentUsers(data, companionsContainer);
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                } else {
                    // If no user is selected, show a message or perform any other action
                    console.log("Please select a user first.");
                }
            } else {
                // Clear and hide the department users list
                companionsContainer.innerHTML = '';
            }
        });




    </script>

@endpush
