<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function search(Request $request)
    {
        $employeeName = $request->input('employee_name');
        $departmentId = $request->input('department_name');

        // Perform the search based on the provided criteria
        $employees = User::when($employeeName, function ($query) use ($employeeName) {
            return $query->where('name', 'LIKE', '%' . $employeeName . '%');
        })->when($departmentId, function ($query) use ($departmentId) {
            return $query->where('department_id', $departmentId);
        })->get();

        // Pass the search results to another blade
        return view('admin.search-results', compact('employees'));
    }



    public function showDepartmentUserPlans()
    {
        if (!auth()->user()->hasRole('Administrator')) {
            abort(403, 'Unauthorized');
        }

        $departmentId = auth()->user()->department_id;
        $departmentEmployees = User::where('department_id', $departmentId)
            ->orderBy('name', 'asc')
            ->get();

        return view('your-view-name', compact('departmentEmployees'));
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


        // Generate an array of working days for the recent month
        $workingDays = [];
        $currentDay = $startOfMonth->copy();

        while ($currentDay <= $endOfMonth) {
            // Check if the current day is a working day
            // You can modify this condition based on your business logic
            if ($currentDay->isWeekday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }

            $currentDay->addDay();
        }



        return view('admin.show-plan', compact('plans', 'workingDays'));
    }
}
