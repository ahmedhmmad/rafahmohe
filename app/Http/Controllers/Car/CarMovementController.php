<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Models\CarMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarMovementController extends Controller
{
    public function index()
    {
        return view('car.select-month-year-plan');
    }

    public function create($month, $year)
    {
        $errors = collect([]);

        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $errors->push('اختر الشهر المطلوب.');
        }

        if (!is_numeric($year) || $year < 2020 || $year > 2099) {
            $errors->push('اختر السنة المطلوبة.');
        }

        return view('car.create-plan', compact('month', 'year', 'errors'));
    }

    public function store(Request $request)
    {
        // Data validation
        $data = $request->validate([
            'days.*.date' => 'required|date',
            'days.*.directions' => 'required|array',
            'days.*.directions.*' => 'in:east,west,home,far,special,free',  // validate direction value
        ]);

        foreach ($data['days'] as $day => $info) {
            foreach ($info['directions'] as $direction) {
                CarMovement::create([
                    'date' => $day,
                    'direction' => $direction,
                ]);
            }
        }

        return redirect()->route('home')->with('success', 'Car movement plan saved successfully!');
    }

    public function show()
    {
        // Replace '2023-09-26' with the specific date for testing
        $testDate = now();

        // Get the first day of the current month
        $firstDayOfMonth = Carbon::parse($testDate)->timezone('Asia/Gaza')->startOfMonth();

        // Calculate the last week's start date (7 days before the last day of the current month)
        $lastWeekStart = $firstDayOfMonth->copy()->endOfMonth()->subDays(6);

        if (Carbon::parse($testDate) >= $lastWeekStart) {
            // Get the start and end dates of the recent month
            $startOfMonth = Carbon::parse($testDate)->timezone('Asia/Gaza')->addMonthNoOverflow()->startOfMonth();
            $endOfMonth = Carbon::parse($testDate)->timezone('Asia/Gaza')->addMonthNoOverflow()->endOfMonth();
        } else {
            // Use the current month
            $startOfMonth = Carbon::parse($testDate)->timezone('Asia/Gaza')->startOfMonth();
            $endOfMonth = Carbon::parse($testDate)->timezone('Asia/Gaza')->endOfMonth();
        }

        // Retrieve all car movements for the specified month and order them by date
        $carMovements = CarMovement::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date')
            ->get();

        // Generate working days array (if needed)
        $workingDays = [];
        $currentDay = $startOfMonth->copy();

        while ($currentDay <= $endOfMonth) {
            if (!$currentDay->isFriday() && !$currentDay->isSaturday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }
            $currentDay->addDay();
        }

        return view('car.show-plan', compact('carMovements', 'workingDays'));
    }
    public function editCarMovement($id)
    {
        // Retrieve the car movement you want to edit
        $carMovement = CarMovement::findOrFail($id);

        // Define your directionsTranslations array (same as in your show method)
        $directionsTranslations = [
            'east' => 'شرق رفح',
            'west' => 'غرب رفح',
            'home' => 'البلد',
            'far' => 'أسرار - المسمية - غسان - مرمرة',
            'special' => 'الشوكة',
            'free' => 'بدون',
        ];

        // Pass the car movement and directionsTranslations to the edit-car-movement blade
        return view('car.edit-car-movement', compact('carMovement', 'directionsTranslations'));
    }

    public function updateCarMovement(Request $request, $id)
    {
        // Data validation
        $data = $request->validate([
            'date' => 'required|date',
            'direction' => 'required|in:east,west,home,far,special,free',
        ]);

        // Retrieve the car movement you want to update
        $carMovement = CarMovement::findOrFail($id);

        // Update the car movement
        $carMovement->update($data);

        // Redirect to the show plan page with a success message
        return redirect()->route('car.show-plan')->with('success', 'Car movement updated successfully!');
    }

    public function deleteCarMovement($id)
    {
        // Retrieve the car movement you want to delete
        $carMovement = CarMovement::findOrFail($id);

        // Delete the car movement
        $carMovement->delete();

        // Redirect to the show plan page with a success message
        return redirect()->route('car.show-plan')->with('success', 'Car movement deleted successfully!');
    }

    public function deleteAllCarMovements()
    {
        // Delete all car movements
        CarMovement::truncate();

        // Redirect to the show plan page with a success message
        return redirect()->route('car.show-plan')->with('success', 'All car movements deleted successfully!');
    }

    public function addCarMovement($id)
    {
        // Retrieve the car movement you want to add
        $carMovement = CarMovement::findOrFail($id);

        // Define your directionsTranslations array (same as in your show method)
        $directionsTranslations = [
            'east' => 'شرق رفح',
            'west' => 'غرب رفح',
            'home' => 'البلد',
            'far' => 'أسرار - المسمية - غسان - مرمرة',
            'special' => 'الشوكة',
            'free' => 'بدون',
        ];

        // Pass the directionsTranslations and carMovement to the add-car-movement blade
        return view('car.add-car-movement', compact('directionsTranslations', 'carMovement'));
    }


    public function storeCarMovement(Request $request)
    {
        // Data validation
        $data = $request->validate([
            'date' => 'required|date',
            'direction' => 'required|in:east,west,home,far,special,free',
        ]);

        // Create the car movement
        CarMovement::create($data);

        // Redirect to the show plan page with a success message
        return redirect()->route('car.show-plan')->with('success', 'Car movement added successfully!');
    }
    public function showCarMovements(Request $request)
    {
        // Retrieve the selected month and year from the request
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        // If the month and year are not provided, use the current month and year
        if (!$selectedMonth || !$selectedYear) {
            $selectedMonth = now()->month;
            $selectedYear = now()->year;
        }

        // Get the first and last day of the selected month and year
        $firstDayOfMonth = now()->setYear($selectedYear)->setMonth($selectedMonth)->startOfMonth();
        $lastDayOfMonth = now()->setYear($selectedYear)->setMonth($selectedMonth)->endOfMonth();

        // Retrieve car movements for the selected month and year
        $carMovements = CarMovement::whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])->get();

        // You may want to modify the data to match your view requirements if necessary

        return view('car.show-car-movements', compact('carMovements'));
    }





}
