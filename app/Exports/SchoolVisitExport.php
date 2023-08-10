<?php

namespace App\Exports;


use App\Models\SchoolVisit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Illuminate\Support\Facades\View as ViewFacade;

//class SchoolVisitExport implements FromQuery, WithHeadings, WithStyles, ShouldAutoSize, WithColumnWidths, WithMapping
class SchoolVisitExport extends StringValueBinder implements FromView, WithCustomValueBinder

{
    public function view(): View
    {
        $groupedData = SchoolVisit::with('user', 'department')
            ->get()
            ->groupBy('department.name');

        return ViewFacade::make('exports.custom_school_visits', [
            'groupedData' => $groupedData,
        ]);
    }
//    public function query()
//    {
//        return SchoolVisit::query();
//    }
//
//    public function headings(): array
//    {
//        return [
//            'الزائر',
//            'القسم',
//            'وقت الحضور',
//            'وقت المغادرة',
//        ];
//    }
//
//    public function styles(Worksheet|\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
//    {
//        return [
//            // Apply your header and row styles here
//        ];
//    }
//
//    public function columnWidths(): array
//    {
//        return [
//            'A' => 30,
//            'B' => 20,
//            'C' => 20,
//            'D' => 20,
//            'D' => 20,
//        ];
//    }
//
//    public function map($schoolVisit): array
//    {
//        return [
//            'الزائر' => $schoolVisit->user->name,
//            'القسم' => $schoolVisit->department->name,
//            'التاريخ'=> $schoolVisit->visit_date,
//            'وقت الحضور' => $schoolVisit->coming_time,
//            'وقت المغادرة' => $schoolVisit->leaving_time,
//        ];
//    }
}
