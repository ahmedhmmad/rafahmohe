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
        $drawing->setName('Logo')->setPath($logoPath)->setCoordinates('D1');
        $drawing->setOffsetX(50); // Adjust the X offset to center the logo
        $drawing->setOffsetY(5); // Adjust the Y offset to center the logo
        $drawing->setWorksheet($sheet);

// Merge cells D1:F3 and center-align the logo within it
        $sheet->mergeCells('D1:F3')->getStyle('D1:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'مديرية التربية والتعليم رفح');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('A2:C2');
        $sheet->setCellValue('A2', 'مكتب المدير');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('A3:C3');
        $sheet->setCellValue('A3', 'تقرير زيارات مدرسة ' . $groupedData->first()->first()->school->name);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->setCellValue('B4', ''); // Leave a blank row

        $sheet->mergeCells('G1:I1');
        $sheet->setCellValue('G1', 'Directorate of Education and Education Rafah');
        $sheet->getStyle('G1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('G2:I2');
        $sheet->setCellValue('G2', 'Director Office');
        $sheet->getStyle('G2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->setCellValue('G3', ''); // Leave a blank row
        $sheet->setCellValue('H4', ''); // Leave a blank row

        $sheet->setCellValue('D1', ''); // Empty cell for spacing
        $sheet->setCellValue('D2', ''); // Empty cell for spacing
        $sheet->setCellValue('D3', ''); // Empty cell for spacing

// Merge D1:F1 to D4:F4


//        $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(14);
//        $sheet->mergeCells('D2:F2');
//        $sheet->getStyle('D2')->getFont()->setBold(true)->setSize(14);
//        $sheet->mergeCells('D3:F3');
//        $sheet->getStyle('D3')->getFont()->setBold(true)->setSize(12);
        $sheet->setCellValue('D4', ''); // Leave a blank row

// Add column headers directly
        $columnHeaders = ['م.', 'القسم', 'الزائر', 'تاريخ الزيارة', 'وقت الحضور', 'وقت المغادرة', 'المسمى الوظيفي', 'أهداف الزيارة', 'ما تم تنفيذه'];
        $columnIndex = 'A';
        foreach ($columnHeaders as $header) {
            $cellCoordinate = $columnIndex . '5';
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
        $row = 6;
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

                        $cellRange = 'A' . $row . ':I' . $row;
                        $sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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


