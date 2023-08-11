<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title> مديرية التربية والتعليم رفح</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        .center {
            text-align: center;
        }
        .logo-cell {
            width: 20%; /* Adjust the logo cell width */
            text-align: right; /* Align the logo to the right */
            border: none; /* Remove border from the logo cell */
        }
        .logo {
            max-width: 100px;
            max-height: 100px;
            margin: 0 auto; /* Center the logo */
        }
        .borderless {
            border: none; /* Remove border from the header table */
        }
    </style>
</head>
<body>
<table class="borderless">
    <tr>
        <td colspan="2" class="center borderless">
            <strong>مديرية التربية والتعليم رفح</strong>
        </td>
        <td colspan="2" class="center logo-cell borderless">
            <img class="logo" src="{{ public_path('/img/logo.webp') }}" alt="Logo">
        </td>
        <td colspan="2" class="center borderless">
            <strong>Directrate of Education -Rafah</strong>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="center borderless">
            <strong>مكتب المدير</strong>
        </td>
        <td colspan="2" class="center borderless"></td>

        <td colspan="2" class="center borderless">
            <strong>Director Office</strong>
        </td>
    </tr>
</table>


<table>
    <thead>
    <tr>
        <th>القسم</th>
        <th>الزائر</th>
        <th>تاريخ الزيارة</th>
        <th>وقت الحضور</th>
        <th>وقت المغادرة</th>
        <th>المسمى الوظيفي</th>
        <th>أهداف الزيارة</th>
        <th>ما تم تنفيذه</th>
    </tr>
    </thead>
    <tbody>
    @foreach($groupedData as $departmentName => $visits)
        @foreach($visits as $visit)
            <tr>
                <td>{{ $departmentName }}</td>
                <td>{{ $visit->user->name }}</td>
                <td>{{ $visit->visit_date }}</td>
                <td>{{ $visit->coming_time }}</td>
                <td>{{ $visit->leaving_time }}</td>
                <td>{{$visit->job_title}}</td>
                <td>{{$visit->purpose}}</td>
                <td>{{$visit->activities}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</body>
</html>
