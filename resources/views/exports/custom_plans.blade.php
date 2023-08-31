<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Create a new Spreadsheet instance
$spreadsheet = new Spreadsheet();

// Set right-to-left direction for the entire sheet
$spreadsheet->getActiveSheet()->setRightToLeft(true);

// Get the active sheet
$sheet = $spreadsheet->getActiveSheet();

// Set header section with logo and title
$logoPath = public_path('/img/logo.webp');
$sheet->mergeCells('B1:D2'); // Merge cells for the logo
$logoDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$logoDrawing->setName('Logo')->setPath($logoPath)->setCoordinates('B1')->setWorksheet($sheet);

$sheet->setCellValue('F1', 'مديرية التربية والتعليم رفح');
$sheet->setCellValue('F2', 'مكتب المدير');
$sheet->getStyle('F1:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Add column headers
$columnHeaders = ['القسم', 'الزائر', 'تاريخ الزيارة', 'وقت الحضور', 'وقت المغادرة', 'المسمى الوظيفي', 'أهداف الزيارة', 'ما تم تنفيذه'];
$columnIndex = 1;
foreach ($columnHeaders as $header) {
    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex) . '4';
    $sheet->setCellValue($cellCoordinate, $header);
    $columnIndex++;
}

// ... (Fill in your data from $groupedData as you did before)
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
// Adjust column widths
$columnIndex = 'A';
foreach ($columnHeaders as $header) {
    $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
    $columnIndex++;
}

// Create a new Xlsx writer and save the spreadsheet to a file
$writer = new Xlsx($spreadsheet);

// Set the appropriate headers for downloading the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="exported_excel.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
