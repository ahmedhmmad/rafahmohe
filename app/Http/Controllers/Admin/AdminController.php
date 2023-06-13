<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Plan;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
//    public function search(Request $request)
//    {
//        $employeeName = $request->input('employee_name');
//        $departmentId = $request->input('department_name');
//
//        // Perform the search based on the provided criteria
//        $employees = User::when($employeeName, function ($query) use ($employeeName) {
//            $terms = explode(' ', $employeeName);
//            $searchTerm = implode('%', $terms);
//
//            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
//        })->when($departmentId, function ($query) use ($departmentId) {
//            return $query->where('department_id', $departmentId);
//        })->get();
//
//        // Pass the search results to another blade
//        return view('admin.search-results', compact('employees'));
//    }



    public function search(Request $request)
    {
        $departments=Department::all();
        $employeeName = $request->input('employee_name');
        $departmentId = $request->input('department_name');

        // Perform the search based on the provided criteria
        $employees = User::when($employeeName, function ($query) use ($employeeName) {
            $terms = explode(' ', $employeeName);
            $searchTerm = implode('%', $terms);

            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        })->when($departmentId, function ($query) use ($departmentId) {
            return $query->where('department_id', $departmentId);
        })->get();

        // Pass the search results to the same blade view
        return view('admin.search-plan', compact('employees','departments'));
    }

//    public function searchSchoolDate(Request $request)
//    {
//        $schools=School::all();
//        $schoolId = $request->input('school_name');
//        $visitDate = $request->input('visit_date');
//
//        // Perform the search based on the provided criteria
//        $visits = Plan::when($schoolId, function ($query) use ($schoolId) {
//            return $query->where('school_id', $schoolId);
//        })->when($visitDate, function ($query) use ($visitDate) {
//            return $query->where('start', $visitDate);
//        })->get();
//
//        // Pass the search results to the blade
//        return view('admin.search-plan-school-date', compact('visits','schools'));
//    }
    public function searchSchoolDate(Request $request)
    {
        $schools = School::all();
//        $visits = null;
//        $visitCount = 0;
//
//        if ($request->filled('school_name') || $request->filled('visit_date')) {
//            $schoolId = $request->input('school_name');
//            $visitDate = $request->input('visit_date');
//
//            $visits = Plan::when($schoolId, function ($query) use ($schoolId) {
//                return $query->where('school_id', $schoolId);
//            })->when($visitDate, function ($query) use ($visitDate) {
//                return $query->where('start', $visitDate);
//            })->with('visitor')->get();
//
//            $visitCount = $visits->count();
//        }
//
//        return view('admin.search-plan-school-date', compact('visits', 'schools', 'visitCount'));
//
        $visits = null;
        $visitCount = 0;

        if ($request->filled('school_name') || $request->filled('visit_date')) {
            $schoolId = $request->input('school_name');
            $visitDate = $request->input('visit_date');

            $query = Plan::query();

            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }

            if ($visitDate) {
                $query->where('start', $visitDate);
            }

            $visits = $query->with('visitor')->get();
            $visitCount = $visits->count();
        }

        return view('admin.search-plan-school-date', compact('visits', 'schools', 'visitCount'));

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
            $userName=User::where('id',$id)->first()->name;

        while ($currentDay <= $endOfMonth) {
            // Check if the current day is a working day
            // You can modify this condition based on your business logic
            if ($currentDay->isWeekday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }

            $currentDay->addDay();
        }



        return view('admin.show-plan', compact('plans', 'workingDays','userName'));
    }

    public function searchPlanBySchool(Request $request)
    {
        $schools = School::all();
        $schoolId = $request->input('school_name');
        $selectedMonth = $request->input('month') ?? date('m');

        $visits = null;
        if ($request->filled('school_name')) {
            $visits = Plan::where('school_id', $schoolId)
                ->whereMonth('start', $selectedMonth)
                ->with(['schools', 'visitor'])
                ->orderBy('start', 'asc')
                ->get();
        }

        return view('admin.search-plan-school', compact('visits', 'schools'));
    }


    public function searchPlanByDate(Request $request)
    {
        $schools = School::all();
        $visitDate = $request->input('visit_date');
        $visits = null;
        if ($request->filled('visit_date')) {
            $visits = Plan::where('start', $visitDate)
                ->with(['schools', 'visitor'])
                ->orderBy('start', 'asc')
                ->get();
        }

        return view('admin.search-plan-date', compact('visits', 'schools'));
    }
    public function searchAllSchool(Request $request)
    {

                $currentMonth = Carbon::now()->month;

                $month = $request->input('month', $currentMonth);

                // Get all schools
                $schools = School::all();

                // Get the plans for the selected month
                $plans = Plan::whereMonth('start', $month)->get();

                // Group plans by school
                $groupedPlans = $plans->groupBy('school_id');

                return view('admin.search-all-schools', compact('schools', 'groupedPlans', 'month'));
            }






}
