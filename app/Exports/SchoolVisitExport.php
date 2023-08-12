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
        $drawing->setName('Logo')->setPath($logoPath)->setCoordinates('D1')->setWorksheet($sheet);

        $sheet->setCellValue('B1', 'مديرية التربية والتعليم رفح');
        $sheet->setCellValue('B2', 'مكتب المدير');
        $sheet->getStyle('B1:B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('G1', 'Directorate of Education and Education Rafah');
        $sheet->setCellValue('G2', 'Director Office');
        $sheet->getStyle('G1:G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add column headers directly
        $columnHeaders = ['م.','القسم', 'الزائر', 'تاريخ الزيارة', 'وقت الحضور', 'وقت المغادرة', 'المسمى الوظيفي', 'أهداف الزيارة', 'ما تم تنفيذه'];
        $columnIndex = 'A';
        foreach ($columnHeaders as $header) {
            $cellCoordinate = $columnIndex . '4';
            $sheet->setCellValue($cellCoordinate, $header);

            // Apply styling to the header cell
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5A5A5A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ];

            $sheet->getStyle($cellCoordinate)->applyFromArray($headerStyle);
            $sheet->getRowDimension(4)->setRowHeight(20);


            $columnIndex++;
        }

        // Fill in your data from $groupedData
        $row = 5;
        $index=1;
                foreach ($groupedData as $departmentName => $visits) {
                    foreach ($visits as $visit) {
                        $sheet->setCellValue('A' . $row, $index++);
                        $sheet->setCellValue('B' . $row, $departmentName);
                        $sheet->setCellValue('C' . $row, $visit->user->name);
                        $sheet->setCellValue('D' . $row, $visit->visit_date);
                        $sheet->setCellValue('E' . $row, $visit->coming_time);
                        $sheet->setCellValue('F' . $row, $visit->leaving_time);
                        $sheet->setCellValue('G' . $row, $visit->job_title);
                        $sheet->setCellValue('H' . $row, $visit->purpose);
                        $sheet->setCellValue('I' . $row, $visit->activities);

                        // Adjust the width of the columns
                        $sheet->getColumnDimension('A')->setWidth(4);
                        $sheet->getColumnDimension('B')->setWidth(25);
                        $sheet->getColumnDimension('C')->setWidth(30);
                        $sheet->getColumnDimension('D')->setWidth(10);
                        $sheet->getColumnDimension('E')->setWidth(10);
                        $sheet->getColumnDimension('F')->setWidth(10);
                        $sheet->getColumnDimension('G')->setWidth(15);
                        $sheet->getColumnDimension('H')->setWidth(30);
                        $sheet->getColumnDimension('I')->setWidth(30);

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


