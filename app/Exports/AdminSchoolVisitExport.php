<?php

namespace App\Exports;

use App\Models\SchoolVisit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\View as ViewFacade;

class AdminSchoolVisitExport implements FromView
{
    protected $selectedDepartmentId;
    protected $selectedUserId;
    protected $school;
    protected $month;
    protected $year;

    public function __construct($selectedDepartmentId, $selectedUserId, $school, $month, $year)
    {
        $this->selectedDepartmentId = $selectedDepartmentId;
        $this->selectedUserId = $selectedUserId;
        $this->school = $school;
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $query = SchoolVisit::with('user', 'department', 'school')
            ->when($this->selectedDepartmentId, function ($query) {
                $query->where('department_id', $this->selectedDepartmentId);
            })
            ->when($this->selectedUserId, function ($query) {
                $query->where('user_id', $this->selectedUserId);
            })
            ->when($this->school, function ($query) {
                $query->where('school_id', $this->school);
            })
            ->when($this->month, function ($query) {
                $query->whereMonth('visit_date', $this->month);
            })
            ->when($this->year, function ($query) {
                $query->whereYear('visit_date', $this->year);
            });

//dd($query->toSql());
        $groupedData = $query->get()->groupBy('department.name');

        return ViewFacade::make('exports.custom_school_visits', [
            'groupedData' => $groupedData,
        ]);
    }

    public function downloadExcel($searchItem)
    {
        //dd($searchItem);

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
        $sheet->setCellValue('A1', 'وزارة التربية والتعليم');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('A2:C2');
        $sheet->setCellValue('A2', 'مديرية التربية والتعليم رفح');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('A3:C3');
        $title = 'تقرير زيارة ';
        if ($groupedData->isNotEmpty()) {


            $firstVisit = $groupedData->first()->first() ?? null;


        if ($firstVisit) {
            if ($searchItem == 'department') {
                $title .= $firstVisit->department->name;

            } elseif ($searchItem =='user') {
                $title .= $firstVisit->user->name;

            } elseif ($searchItem == 'school') {
                $title .='مدرسة '. $firstVisit->school->name;

            }
        }
        }

        // Set the title in the Excel sheet
        $sheet->setCellValue('A3', $title);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->setCellValue('B4', ''); // Leave a blank row

        $sheet->mergeCells('G1:I1');
        $sheet->setCellValue('G1', 'Ministry of Education and Higher Education');
        $sheet->getStyle('G1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('G2:I2');
        $sheet->setCellValue('G2', 'Directorate of Education and Education Rafah');
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
        $columnHeaders = ['م.', 'القسم', 'الزائر','مكان الزيارة', 'تاريخ الزيارة', 'وقت الحضور', 'وقت المغادرة', 'المسمى الوظيفي', 'أهداف الزيارة', 'ما تم تنفيذه'];
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
                $sheet->setCellValue('D' . $row, $visit->school->name);
                $sheet->setCellValue('E' . $row, $visit->visit_date);
                $sheet->setCellValue('F' . $row, $visit->coming_time);
                $sheet->setCellValue('G' . $row, $visit->leaving_time);
                $sheet->setCellValue('H' . $row, $visit->job_title);
                $sheet->setCellValue('I' . $row, $visit->purpose);
                $sheet->setCellValue('J' . $row, $visit->activities);

                $cellRange = 'A' . $row . ':J' . $row;
                $sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // Adjust the width of the columns
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('F')->setWidth(10);
                $sheet->getColumnDimension('G')->setWidth(10);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(20);

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
