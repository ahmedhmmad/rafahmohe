<?php

namespace App\Exports;

use App\Models\SchoolVisit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\View as ViewFacade;

class SchoolVisitExport implements FromView
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

    public function downloadExcel()
    {
        $view = $this->view();
        $groupedData = $view->getData()['groupedData'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set right-to-left direction for the entire sheet
        $spreadsheet->getActiveSheet()->setRightToLeft(true);

        // Add custom header and logo (adjust coordinates as needed)
        $logoPath = public_path('/img/logo.webp');
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo')->setPath($logoPath)->setCoordinates('B1')->setWorksheet($sheet);

        $sheet->setCellValue('F1', 'مديرية التربية والتعليم رفح');
        $sheet->setCellValue('F2', 'مكتب المدير');
        $sheet->getStyle('F1:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Adjust column widths
        $numberOfColumns = 8; // Change this to match the number of columns in your view
        $columnIndex = 'A';

        foreach (range(0, $numberOfColumns - 1) as $col) {
            $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
            $columnIndex++;
        }

        // Fill in your data from $groupedData
        $row = 5;
        foreach ($groupedData as $departmentName => $visits) {
            foreach ($visits as $visit) {
                $sheet->setCellValue('A' . $row, $departmentName);
                $sheet->setCellValue('B' . $row, $visit->user->name);
                $sheet->setCellValue('C' . $row, $visit->visit_date);
                $sheet->setCellValue('D' . $row, $visit->coming_time);
                $sheet->setCellValue('E' . $row, $visit->leaving_time);
                $sheet->setCellValue('F' . $row, $visit->job_title);
                $sheet->setCellValue('G' . $row, $visit->purpose);
                $sheet->setCellValue('H' . $row, $visit->activities);
                $row++;
            }
        }


    // Create a temporary file path
        $tempFilePath = tempnam(sys_get_temp_dir(), 'exported_excel');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFilePath);

        // Set the appropriate headers for downloading the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="exported_excel.xlsx"');
        header('Cache-Control: max-age=0');

        // Read and output the temporary file
        readfile($tempFilePath);

        // Clean up the temporary file
        unlink($tempFilePath);
    }
}
