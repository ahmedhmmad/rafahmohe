<?php

namespace App\Http\Controllers\Report;

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

}
