<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excel Header and Data</title>
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
        <td colspan="2" class="center"><strong>مديرية التربية والتعليم رفح</strong></td>
        <td colspan="2" class="center logo-cell">
            <img class="logo" src="{{ public_path('/img/logo.webp') }}" alt="Logo">
        </td>
    </tr>
    <tr>
        <td colspan="2" class="center"><strong>مكتب المدير</strong></td>
        <td colspan="2" class="center"><strong>Director Office</strong></td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th>القسم</th>
        <th>الزائر</th>
        <th>وقت الحضور</th>
        <th>وقت المغادرة</th>
    </tr>
    </thead>
    <tbody>
    @foreach($groupedData as $departmentName => $visits)
        @foreach($visits as $visit)
            <tr>
                <td>{{ $departmentName }}</td>
                <td>{{ $visit->user->name }}</td>
                <td>{{ $visit->coming_time }}</td>
                <td>{{ $visit->leaving_time }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</body>
</html>

