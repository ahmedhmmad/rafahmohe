<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Plan;
use App\Models\PlanRestriction;
use App\Models\School;
use App\Models\SchoolVisit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
public function timeline()
{

    return view('admin.timeline');
}
    public function getUserTimeline(Request $request)
    {
        $date = $request->input('date');
        $user_id = $request->input('user_id');

        $timeline = $this->fetchUserTimeline($date, $user_id); // Assuming you have another method named 'fetchUserTimeline' to get the timeline data
        $timelineContent = View::make('admin.timeline')->with('timeline', $timeline)->render();


        return response()->json([
            'timeline' => $timeline,
            'timeline_content' => $timelineContent,
        ]);
    }
    public function fetchUserTimeline($date, $user_id)
    {
        // Assuming you have a 'schoolvisits' table and a 'SchoolVisit' model defined
        // You can use the 'where' method to filter the data based on the date and user ID
        $timeline = SchoolVisit::where('visit_date', $date)
            ->where('user_id', $user_id)
            ->get();

        return $timeline;
    }
//    public function showUserTimeline($date, $user_id)
//    {
//        $timeline = $this->getUserTimeline($date, $user_id);
//
//        return view('your-blade-template', compact('timeline'));
//    }
    public function fetchDepartmentUsers(Request $request)
    {
        $departmentId = $request->input('department_id');

        $users = [];
        if ($departmentId) {
            $users = User::where('department_id', $departmentId)->get(['id', 'name']);
        }

        return response()->json(['users' => $users]);
    }
    public function getTableOverrideData(Request $request)
    {
        // Retrieve the data for the table (e.g., plan restrictions) with paginatiob
        $planRestrictions = PlanRestriction::paginate(10);


        // Pass the data to the view
        return view('admin.table-override-data', compact('planRestrictions'));
    }

    public function deletePlanRestriction($planRestricion)
    {
        $planRestricion = PlanRestriction::find($planRestricion);
        $planRestricion->delete();
        return redirect()->back()->with('success', 'تم حذف الاسثناء بنجاح.');
    }

//    public function showOverridePlanRestrictionsForm(Request $request)
//    {
//        // Retrieve the list of users (you can customize this query as per your needs)
//        $users = User::all();
//        $departments = Department::all();
//        $selected_department_id= $request->input('selected_department_id');
//        $selected_user_id= $request->input('selected_user_id');
//        $selectedUser = null;
//        $selectedDepartment = null;
//        $selectedUserOrDepartment = '';
//
//        // Retrieve the selected user or department based on the form input
//        if ($selected_user_id) {
//            $selectedUser = User::find($request->input('selected_user_id'));
//            $selectedUserOrDepartment = 'user';
//        } elseif ($selected_department_id) {
//            $selectedDepartment = Department::find($request->input('selected_department_id'));
//            $selectedUserOrDepartment = 'department';
//        }
//
//
//        return view('admin.override-plan-restrictions', compact('users', 'selectedUser', 'selectedDepartment', 'departments', 'selectedUserOrDepartment'));
//    }

    public function showOverridePlanRestrictionsForm(Request $request)
    {
        dd($request->all());
        // Retrieve the list of users (you can customize this query as per your needs)
        $users = User::all();
        $departments = Department::all();
        $selected_department_id = $request->input('selected_department_id');
        $selected_user_id = $request->input('selected_user_id');
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

        // Fetch the table data
        $tableOverrideData = $this->getTableOverrideData($request);

        return view('admin.override-plan-restrictions', compact('users', 'selectedUser', 'selectedDepartment', 'departments', 'selectedUserOrDepartment', 'tableOverrideData'));
    }



    public function overridePlanRestrictions(Request $request)
    {
        //dd($request->all());
        $selectedUserOrDepartment = $request->selected_user_or_department;
       // dd($selectedUserOrDepartment);

//        $request->validate([
//            'can_override_last_week' => 'nullable|boolean',
//            'can_override_department' => 'nullable|boolean',
//            'override_start_date' => 'nullable|date',
//            'override_end_date' => 'nullable|date',
//            'override_week_number' => 'nullable|integer',
//            'override_department_id' => 'nullable|exists:departments,id',
//        ]);
        $request->validate([
            'can_override_last_week' => 'nullable|boolean',
            'can_override_department' => 'nullable|boolean',
            'override_start_date' => 'nullable|date',
            'override_end_date' => 'nullable|date',
            'override_week_number' => 'nullable|integer',
            'can_override_multi_department' => 'nullable|boolean',
        ], [
            'override_start_date.date' => 'The override start date must be a valid date.',
            'override_end_date.date' => 'The override end date must be a valid date.',
        ]);

        $validatedData = $request->all();

        if (!isset($validatedData['override_start_date']) || $validatedData['override_start_date'] === null) {
            $validatedData['override_start_date'] = now()->toDateString(); // Set default value as today's date if not provided
        }

        if (!isset($validatedData['override_end_date']) || $validatedData['override_end_date'] === null) {
            $validatedData['override_end_date'] = now()->addDay()->toDateString(); // Set default value as tomorrow's date if not provided
        }


        $validatedData['can_override_last_week'] = $validatedData['can_override_last_week'] ?? false;
        $validatedData['can_override_department'] = $validatedData['can_override_department'] ?? false;

        if ($selectedUserOrDepartment === 'user') {
           $userId= json_decode($request->input('selected_user_id'))->id;

            $user = User::findOrFail($userId);
           PlanRestriction::updateOrCreate(['user_id' => $user->id], $validatedData);


           // PlanRestriction::updateOrCreate(['user_id' => $userId], $validatedData);
        } elseif ($selectedUserOrDepartment === 'department') {
            $departmentId = json_decode($request->input('selected_department_id'))->id;
            $users = User::where('department_id', $departmentId)->get();
            foreach ($users as $user) {
                PlanRestriction::updateOrCreate(['user_id' => $user->id], $validatedData);
            }
        }


        return redirect()->back()->with('success', 'تم استثناء قيود الخطة بنجاح.');
    }


    public function search(Request $request)
    {
        // Get all departments except department_id == 24
        $departments = Department::where('id', '!=', 24)->get();

        $employeeName = $request->input('employee_name');
        $departmentId = $request->input('department_name');

        // Perform the search based on the provided criteria
        $employees = User::when($employeeName, function ($query) use ($employeeName) {
            $terms = explode(' ', $employeeName);
            $searchTerm = implode('%', $terms);

            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        })->when($departmentId, function ($query) use ($departmentId) {
            return $query->where('department_id', $departmentId);
        })
            ->where('department_id', '!=', 24) // Exclude users with department_id == 24
            ->get();

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

    public function summaryDepartmentsWithPlans()
    {
        // Define the allowed department IDs
        $allowedDepartmentIds = [21, 10, 19, 17, 6, 20, 12];

        // Retrieve the list of departments that are in the allowed list
        $departments = Department::whereIn('id', $allowedDepartmentIds)->get();

        // Initialize arrays to store departments with plans and without plans
        $departmentsWithPlans = [];
        $departmentsWithoutPlans = [];

        // Loop through each department in the allowed list
        foreach ($departments as $department) {
            $usersInDepartment = User::where('department_id', $department->id)->pluck('id')->toArray();
            $hasPlans = Plan::whereIn('user_id', $usersInDepartment)->exists();

            if ($hasPlans) {
                $departmentsWithPlans[] = $department;
            } else {
                $departmentsWithoutPlans[] = $department;
            }
        }

        // Return the departments with plans and departments without plans to a view
        return view('admin.department-plan-summary', compact('departmentsWithPlans', 'departmentsWithoutPlans'));
    }
}
