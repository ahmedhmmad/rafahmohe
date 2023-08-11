<?php

namespace App\Exports;

use App\Models\SchoolVisit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Illuminate\Support\Facades\View as ViewFacade;


class SchoolVisitExport extends StringValueBinder implements FromView, WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */
//    public function collection()
//    {
//        //
//    }

    public function view(): View
    {
        $groupedData = SchoolVisit::with('user', 'department')
            ->get()
            ->groupBy('department.name');

        return ViewFacade::make('exports.custom_school_visits', [
            'groupedData' => $groupedData,
        ]);
    }
}
