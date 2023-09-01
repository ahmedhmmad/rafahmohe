<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class HeadController extends Controller
{


    public function monthlyPlan(Request $request)
    {
        //dd($request->all());
        $month = $request->input('month');
        $year = $request->input('year');
        $user = $request->input('userId');




        // Validate the month and year inputs here if needed

        // Create a Carbon instance for the selected month and year
        $date = Carbon::create($year, $month);

        // Retrieve the monthly plans for the selected month and year
        $plans = Plan::whereYear('start', $year)
            ->where('user_id',$user)
            ->whereMonth('start', $month)
            ->get();

        // Generate the working days array for the selected month
        $workingDays = $this->generateWorkingDays($month, $year);

        return view('head.monthly-plan', compact('plans', 'workingDays', 'date','month', 'year','user'));
    }
    public function show($id)
    {

//        $user = Auth::user();

        // Get the start and end dates of the recent month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Retrieve the plans for the recent month and order them by date
        $plans = Plan::where('user_id', $id)
            ->whereBetween('start', [$startOfMonth, $endOfMonth])
            ->orderBy('start')
            ->get();

        $userId=$id;


        // Generate an array of working days for the recent month
        $workingDays = [];
        $currentDay = $startOfMonth->copy();

        while ($currentDay <= $endOfMonth) {
            // Check if the current day is a working day
            // You can modify this condition based on your business logic
            if (!$currentDay->isSaturday()&& !$currentDay->isFriday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }

            $currentDay->addDay();
        }



        return view('head.show-plan', compact('plans', 'workingDays','userId'));
    }



    private function generateWorkingDays($month, $year)
    {
        $workingDays = [];
        $totalDays = Carbon::create($year, $month)->daysInMonth;

        // Loop through each day of the month
        for ($day = 1; $day <= $totalDays; $day++) {
            $date = Carbon::create($year, $month, $day);

            // Check if the day is a working day (Monday to Friday)

            if (!$date->isFriday() && !$date->isSaturday()) {
                $workingDays[] = $date->toDateString();
            }
        }

        return $workingDays;
    }
}
