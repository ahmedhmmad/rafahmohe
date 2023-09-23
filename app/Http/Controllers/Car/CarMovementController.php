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




}
