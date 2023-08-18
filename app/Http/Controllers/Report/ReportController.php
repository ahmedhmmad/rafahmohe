<?php

namespace App\Http\Controllers\Report;

use App\Exports\AdminSchoolVisitExport;
use App\Exports\SchoolVisitExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
//    public function exportExcel()
//    {
//        return Excel::download(new SchoolVisitExport(), 'custom-school-visits.xlsx');
//    }


        public function exportExcel(Request $request)
    {
        $month = $request->input('month');
        $orderBy = $request->input('orderBy');


        return (new SchoolVisitExport($month,$orderBy))->downloadExcel();
    }

    public function adminExportExcel(Request $request)
    {
//        dd($request->all());
        $selectedDepartmentId = $request->input('selected_department_id');
        $selectedUserId = $request->input('selected_user_id');
        $school = $request->input('school');
        $month = $request->input('month');
        $year = $request->input('year');

        return (new AdminSchoolVisitExport($selectedDepartmentId, $selectedUserId, $school, $month, $year))->downloadExcel();
    }

}
