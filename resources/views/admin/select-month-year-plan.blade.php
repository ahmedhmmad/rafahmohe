@extends('layouts.master')



@section('content')
    <div class="p-4">


    <h2 class="p-4">إدخال الخطة الشهرية</h2>
    <div class="container">

        <div class="row">
        <div class="col-md-4">
            <label for="monthSelect" class="form-label"><strong>الشهر</strong></label>
            <select class="form-select" id="monthSelect" aria-label="Default select example">
                <option selected>اختر الشهر المطلوب</option>
                <option value="1">يناير</option>
                <option value="2">فبراير</option>
                <option value="3">مارس</option>
                <option value="4">ابريل</option>
                <option value="5">مايو</option>
                <option value="6">يونيو</option>
                <option value="7">يوليو</option>
                <option value="8">اغسطس</option>
                <option value="9">سبتمبر</option>
                <option value="10">اكتوبر</option>
                <option value="11">نوفمبر</option>
                <option value="12">ديسمبر</option>
            </select>
        </div>
            <div class="col-md-4">
            <label for="yearSelect" class="form-label"><strong>السنة</strong></label>
            <select class="form-select" id="yearSelect" aria-label="Default select example">
                <option selected>اختر السنة </option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>

        </div>
            <div class="col-md-4">
                <form action="{{ route('employee.create-plan', ['month' => ':selectedMonth', 'year' => ':selectedYear']) }}" method="get" id="createPlanForm">

                    <button type="submit" class="btn btn-primary mt-4" style="transform:translatex(0px) translatey(5px);" onclick="setSelectedValues()">التالي</button>

                </form>

            </div>
        </div>
    </div>
    </div>

@endsection

<script>
    function setSelectedValues() {
        var selectedMonth = document.getElementById('monthSelect').value;
        var selectedYear = document.getElementById('yearSelect').value;
        document.getElementById('createPlanForm').action = "{{ route('employee.create-plan', ['month' => ':selectedMonth', 'year' => ':selectedYear']) }}".replace(':selectedMonth', selectedMonth).replace(':selectedYear', selectedYear);
    }
</script>
