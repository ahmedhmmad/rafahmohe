<?php

namespace App\Http\Controllers\Report;

use App\Exports\AdminSchoolVisitExport;
use App\Exports\PlanExport;
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

    public function exportPlan(Request $request)
    {

        $month = $request->input('month');
        $year = $request->input('year');

        return (new PlanExport($month,$year))->downloadExcel();
    }

    public function adminExportExcel(Request $request)
    {
        // dd($request->all());
        $selectedDepartmentId = $request->input('selected_department_id');
        $selectedUserId = $request->input('selected_user_id');
        $school = $request->input('school');
        $month = $request->input('month');
        $year = $request->input('year');

        // Determine the search parameter based on user selection
        $searchItem = null;
        if ($selectedDepartmentId !== null) {
            $searchItem = 'department';
        } elseif ($selectedUserId !== null) {
            $searchItem = 'user';
        } elseif ($school !== null) {
            $searchItem = 'school';
        } elseif ($month !== null) {
            $searchItem = 'month';
        } elseif ($year !== null) {
            $searchItem = 'year';
        }

        return (new AdminSchoolVisitExport($selectedDepartmentId, $selectedUserId, $school, $month, $year))
            ->downloadExcel($searchItem);
    }


}
