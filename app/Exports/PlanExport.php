<?php
namespace App\Exports;


use App\Models\Plan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View as ViewFacade;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class PlanExport implements FromView
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        //dd month and year
       // dd($this->month, $this->year);
        $user = Auth::user();
        $plans = Plan::with('school')
            ->where('user_id', $user->id)
            ->when($this->month, function ($query) {
                $query->whereMonth('start', $this->month);
            })
            ->when($this->year, function ($query) {
                $query->whereYear('start', $this->year);
            })
            ->get();


        $groupedData = $plans->groupBy(function ($plan) {
            return $plan->start;
        })->map(function ($plansPerDay) {
            return $plansPerDay->groupBy('start')->map(function ($plansPerDate) {
                return $plansPerDate->pluck('school.name')->implode(', ');
            });
        });


        return ViewFacade::make('exports.custom_plans', [
            'groupedData' => $groupedData,
        ]);
    }


// Inside the downloadExcel() method
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
        $sheet->setCellValue('A1', 'وزارة التربية والتعليم');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('A2:C2');
        $sheet->setCellValue('A2', 'مديرية التربية والتعليم رفح');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->mergeCells('A3:C3');
        $title = 'الخطة الشهرية ';


        // Set the title in the Excel sheet
        $sheet->setCellValue('A3', $title);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        $sheet->setCellValue('B4', ''); // Leave a blank row

//        $sheet->mergeCells('E1:G1');
        $sheet->setCellValue('D1', 'Ministry of Education and Higher Education');
        $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//
//        $sheet->mergeCells('E2:G2');
        $sheet->setCellValue('D2', 'Directorate of Education and Education Rafah');
        $sheet->getStyle('D2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('Ds2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        //$sheet->setCellValue('G3', ''); // Leave a blank row
        //$sheet->setCellValue('H4', ''); // Leave a blank row

//        $sheet->setCellValue('D1', ''); // Empty cell for spacing
//        $sheet->setCellValue('D2', ''); // Empty cell for spacing
//        $sheet->setCellValue('D3', ''); // Empty cell for spacing
//

        $sheet->setCellValue('D4', ''); // Leave a blank row
// Add column headers directly
        $columnHeaders = ['م.', 'اليوم', 'التاريخ', 'المدرسة'];
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
            $sheet->getRowDimension(5)->setRowHeight(20);

            $columnIndex++;
        }

// Fill in your data from $groupedData
        $row = 6;
        $dayIndex = 1; // Initialize day index

        foreach ($groupedData as $dayName => $plansPerDate) {
            foreach ($plansPerDate as $date => $schoolNames) {
                $sheet->setCellValue('A' . $row, $dayIndex); // Set the day index

                // Use Carbon to get the day name from the date
                $dayName = Carbon::parse($date)->translatedFormat('l');
                $sheet->setCellValue('B' . $row, $dayName); // Set the day name

                $sheet->setCellValue('C' . $row, $date);

                // Merge cells D, E, and F
                $sheet->mergeCells("D{$row}:F{$row}");
                $sheet->setCellValue('D' . $row, $schoolNames);

                // Apply styling and formatting to the merged cell
                $cellRange = 'A' . $row . ':F' . $row;
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true); // Wrap text in the cell
                $sheet->getStyle($cellRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle($cellRange)->getFont()->setSize(14);
                $sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getRowDimension($row)->setRowHeight(22); // Set the row height

                // Adjust the width of the columns
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(18);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(99); // Adjusted width for merged cells
                $sheet->getColumnDimension('E')->setWidth(0);  // Hide unused columns
                $sheet->getColumnDimension('F')->setWidth(0);  // Hide unused columns

                $row++;
                $dayIndex++; // Increment the day index
            }
        }


        // Create a temporary file path
            $tempFilePath = tempnam(sys_get_temp_dir(), 'exported_excel');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($tempFilePath);


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="exported_excel.xlsx"');
            header('Cache-Control: max-age=0');

            // Read and output the temporary file
            readfile($tempFilePath);

            // Clean up the temporary file
            unlink($tempFilePath);
        }

}



