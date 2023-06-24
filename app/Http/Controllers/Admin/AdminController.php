<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Plan;
use App\Models\PlanRestriction;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function fetchDepartmentUsers(Request $request)
    {
        $departmentId = $request->input('department_id');

        $users = [];
        if ($departmentId) {
            $users = User::where('department_id', $departmentId)->get(['id', 'name']);
        }

        return response()->json(['users' => $users]);
    }
//    public function showOverridePlanRestrictionsForm(Request $request)
//    {
//        // Retrieve the list of users (you can customize this query as per your needs)
//        $users = User::all();
//        $departments = Department::all();
//        $selectedUserOrDepartment = null;
//       // dd($request->all());
//
//        // Retrieve the selected user or department based on the form input
//        if ($request->has('selected_user_id')) {
//
//            $selectedUserOrDepartment = User::find($request->input('selected_user_id'));
//           // dd($selectedUserOrDepartment);
//        } elseif ($request->has('selected_department_id')) {
//            $selectedUserOrDepartment = Department::find($request->input('selected_department_id'));
//            // Get the first user of the selected department
//
//            $users = $selectedUserOrDepartment->users;
//        }
//
//
//        return view('admin.override-plan-restrictions', compact('users', 'selectedUserOrDepartment', 'departments'));
//    }

    public function showOverridePlanRestrictionsForm(Request $request)
    {
        // Retrieve the list of users (you can customize this query as per your needs)
        $users = User::all();
        $departments = Department::all();
        $selected_department_id= $request->input('selected_department_id');
        $selected_user_id= $request->input('selected_user_id');
        $selectedUser = null;
        $selectedDepartment = null;
        $selectedUserOrDepartment = '';

        // Retrieve the selected user or department based on the form input
        if ($selected_user_id) {
            $selectedUser = User::find($request->input('selected_user_id'));
            $selectedUserOrDepartment = 'user';
        } elseif ($selected_department_id) {
            $selectedDepartment = Department::find($request->input('selected_department_id'));
            $selectedUserOrDepartment = 'department';
        }


        return view('admin.override-plan-restrictions', compact('users', 'selectedUser', 'selectedDepartment', 'departments', 'selectedUserOrDepartment'));
    }




    public function overridePlanRestrictions(Request $request)
    {
        //dd($request->all());
        $selectedUserOrDepartment = $request->selected_user_or_department;
       // dd($selectedUserOrDepartment);

        $request->validate([
            'can_override_last_week' => 'nullable|boolean',
            'can_override_department' => 'nullable|boolean',
            'override_start_date' => 'nullable|date',
            'override_end_date' => 'nullable|date',
            'override_week_number' => 'nullable|integer',
            'override_department_id' => 'nullable|exists:departments,id',
        ]);

        $validatedData = $request->all();
        $validatedData['can_override_last_week'] = $validatedData['can_override_last_week'] ?? false;
        $validatedData['can_override_department'] = $validatedData['can_override_department'] ?? false;

        if ($selectedUserOrDepartment === 'user') {
           $userId= json_decode($request->input('selected_user_id'))->id;

            $user = User::findOrFail($userId);
           PlanRestriction::updateOrCreate(['user_id' => $user->id], $validatedData);


           // PlanRestriction::updateOrCreate(['user_id' => $userId], $validatedData);
        } elseif ($selectedUserOrDepartment === 'department') {
            $departmentId = json_decode($request->input('selected_department_id'))->id;
            //$departmentId = $request->input('selected_department_id');

            PlanRestriction::whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })->update($validatedData);
        }

        return redirect()->back()->with('success', 'تم استثناء قيود الخطة بنجاح.');
    }








    public function search(Request $request)
    {
        $departments = Department::all();
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
        return view('admin.search-plan', compact('employees', 'departments'));
    }

    public function searchSchoolDate(Request $request)
    {
        $schools = School::all();
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
        $userName = User::findOrFail($id)->name;

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
            if (!$currentDay->isFriday() && !$currentDay->isSaturday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }

            $currentDay->addDay();
        }

        return view('admin.show-plan', compact('plans', 'workingDays', 'userName'));
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

        $visitedSchoolIds = $groupedPlans->keys();

        // Get the unvisited schools
        $unvisitedSchools = $schools->whereNotIn('id', $visitedSchoolIds);

        return view('admin.search-all-schools', compact('schools', 'groupedPlans', 'month', 'unvisitedSchools'));
    }
}
