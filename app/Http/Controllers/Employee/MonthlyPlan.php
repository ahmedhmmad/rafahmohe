<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;


class MonthlyPlan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee.select-month-year-plan');
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create($month, $year)
    {
        $user = Auth::user();
        $userActivity = UserActivity::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->value('plan_input');
        if ($userActivity) {
            session()->flash('success', 'لقد قمت بإدخال الخطة الشهرية لهذا الشهر مسبقاً، يمكنك التعديل الآن');
            return redirect()->route('employee.show-plan', ['month' => $month, 'year' => $year]);
        } else {


            $errors = collect([]);
            $departmentId = Auth::user()->department->id;

            $allowedDepartmentIds = [21, 10, 19, 17, 6, 20, 12];

            if (!in_array($departmentId, $allowedDepartmentIds)) {

                $errors->push('لا يمكنك إدخال الخطة لأنه ليس لقسمك خطة شهرية');
                return redirect()->back()->withErrors($errors);
            }


            $planRestriction = Auth::user()->planRestrictions->first();
            //$canOverrideDepartment = $planRestriction ? ($planRestriction->can_override_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : true;
            $canOverrideDepartment = !$planRestriction || ($planRestriction->can_override_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now());
            $canOverrideMultiDepartment = $planRestriction ? ($planRestriction->can_override_multi_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;

            // Check if the month is a valid value
            if (!is_numeric($month) || $month < 1 || $month > 12) {
                $errors->push('اختر الشهر المطلوب.');
            }

            // Check if the year is a valid value
            if (!is_numeric($year) || $year < 2020 || $year > 2099) {
                $errors->push('اختر السنة المطلوبة.');
            }

            $currentMonth = null; // Initialize the variable outside the try-catch block
            $currentYear = null;
            $canOverrideLastWeek = false; // Initialize the variable outside the try-catch block
            $lastDayOfMonth = null; // Initialize the variable outside the try-catch block

            try {
                // Check if the specified month and year create a valid date
                $currentMonth = Carbon::create($year, $month)->month;
                $currentYear = Carbon::create($year, $month)->year;


                $existingPlans = Plan::whereMonth('start', $currentMonth)
                    ->whereYear('start', $currentYear)
                    ->get();

                $existingPlanDates = $existingPlans->pluck('start')->toArray();

                $planRestriction = Auth::user()->planRestrictions->first();
                $canOverrideLastWeek = $planRestriction ? ($planRestriction->can_override_last_week && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;

            } catch (\Exception $e) {
                $errors->push('اختر الشهر والسنة المطلوبة.');
            }

            // Calculate the last day of the month outside the try-catch block
            //$lastDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth();
            $lastDayOfMonth = Carbon::create($currentYear, $currentMonth)->subMonth()->endOfMonth();

            // Check if the current date is within the allowed range for entering the plan
            $currentDate = now();
            $lastWeekOfMonth = $lastDayOfMonth->copy()->subWeek();
            //dd($currentDate < $lastWeekOfMonth || $currentDate > $lastDayOfMonth);


            if ($currentDate->month == $month || $currentDate < $lastWeekOfMonth || $currentDate > $lastDayOfMonth) {

                if (!$canOverrideLastWeek) {
                    $lastWeekOfMonth->modify('+1 day');
                    $validDates = $lastWeekOfMonth->format('d/m/Y') . ' - ' . $lastDayOfMonth->format('d/m/Y');
                    $errorMessage = 'لا يمكن إدخال الخطة إلا خلال الأسبوع الأخير من الشهر الحالي:: الفترة المسموحة: ' . $validDates;
                    $errors->push($errorMessage);
                }
            }

            if ($errors->isNotEmpty()) {
                return redirect()->back()->withErrors($errors);
            }

            //$schools = School::all();
//        $schools = School::orderBy('name')->get();
//        $schools = School::orderByRaw("id = 34 DESC, name ASC")->get();
            $schools = School::orderByRaw("FIELD(id, 34, 35, 3434343404, 3434343405, 34343406, 34343405) DESC, name ASC")->get();


            //dd($existingPlans, $existingPlanDates, $canOverrideDepartment, $canOverrideMultiDepartment, $departmentId);

            return view('employee.create-plan', compact('schools', 'month', 'year', 'existingPlanDates', 'existingPlans', 'canOverrideDepartment', 'canOverrideMultiDepartment', 'departmentId'));
        }
    }

    public function create2($month, $year)
    {
        $errors = collect([]);
        $departmentId = Auth::user()->department->id;
        $planRestriction = Auth::user()->planRestrictions->first();

//        $canOverrideDepartment = $planRestriction ? $planRestriction->can_override_department : false;
//        $canOverrideMultiDepartment = $planRestriction ? $planRestriction->can_override_multi_department : false;

//        $canOverrideDepartment = $planRestriction ? ($planRestriction->can_override_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;
        $canOverrideDepartment = $planRestriction ? ($planRestriction->can_override_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : true;

        $canOverrideMultiDepartment = $planRestriction ? ($planRestriction->can_override_multi_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;


        // Check if the month is a valid value
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $errors->push('اختر الشهر المطلوب.');
        }

        // Check if the year is a valid value
        if (!is_numeric($year) || $year < 2020 || $year > 2099) {
            $errors->push('اختر السنة المطلوبة.');
        }
        $currentMonth = null; // Initialize the variable outside the try-catch block
        $currentYear = null;
        $canOverrideLastWeek = false;
        $lastDayOfMonth = null;


        try {
            // Check if the specified month and year create a valid date
            $currentMonth = Carbon::create($year, $month)->month;
            $currentYear = Carbon::create($year, $month)->year;
            $lastDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth();

            $existingPlans = Plan::whereMonth('start', $currentMonth)
                ->whereYear('start', $currentYear)
                ->get();

            $existingPlanDates = $existingPlans->pluck('start')->toArray();

            $planRestriction = Auth::user()->planRestrictions->first();
            $canOverrideLastWeek = $planRestriction ? $planRestriction->can_override_last_week  && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now() : false;

        } catch (\Exception $e) {
            $errors->push('اختر الشهر والسنة المطلوبة.');
        }

        // Check if the current date is within the allowed range for entering the plan

        $currentDate = now();
        $lastWeekOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth()->subWeek();


        if ($currentDate->month != $month || $currentDate < $lastWeekOfMonth || $currentDate > $lastDayOfMonth) {


            if (!$canOverrideLastWeek) {

//                    $errors->push('لا يمكن إدخال الخطة فقط خلال الأسبوع الأخير من الشهر الحالي.');
                $validDates = $lastWeekOfMonth->format('d/m/Y') . ' - ' . $lastDayOfMonth->format('d/m/Y');
                $errorMessage = 'لا يمكن إدخال الخطة فقط خلال الأسبوع الأخير من الشهر الحالي. الفترة المسموحة: ' . $validDates;
                $errors->push($errorMessage);
                }

        }

        if ($errors->isNotEmpty()) {
            return redirect()->back()->withErrors($errors);
        }

        $schools = School::all();
        //dd($existingPlans, $existingPlanDates, $canOverrideDepartment, $canOverrideMultiDepartment, $departmentId);

        return view('employee.create-plan', compact('schools', 'month', 'year', 'existingPlanDates', 'existingPlans','canOverrideDepartment','canOverrideMultiDepartment','departmentId'));

    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());

        $days = $request->input('days');
        $user = Auth::user();
        $departmentId = $user->department->id;

        foreach ($days as $date => $dayData) {
            $schoolIds = array_values($dayData['schools']);

            foreach ($schoolIds as $schoolId) {
                $plan = new Plan();
                $plan->school_id = $schoolId;
                $plan->user_id = $user->id;
                $plan->department_id = $departmentId;
                $plan->start = $date;
                $plan->end = $date;

                $plan->save();
            }
        }
        $month = $request->input('month');
        $year = $request->input('year');

        // Create a new UserActivity record or update an existing one
        UserActivity::updateOrCreate(
            [
                'user_id' => $user->id,
                'month' => $month,
                'year' => $year,
            ],
            [
                'plan_input' => true, // Set this to true to indicate plan input
                // 'ticket_closed' => false, // You can set ticket_closed status here if needed
            ]
        );
        session()->flash('success', 'تم الحفظ بنجاح');

        return redirect()->route('home');
    }
//    public function show()
//    {
//        $errors = collect([]);
//
//        $user = Auth::user();
//        $departmentId = $user->department->id;
//
//        $allowedDepartmentIds = [21, 10, 19, 17, 6, 20, 12];
//
//        if (!in_array($departmentId, $allowedDepartmentIds)) {
//
//            $errors->push('لا يمكنك إدخال/ تعديل الخطة لأنه ليس لقسمك خطة شهرية');
//            return redirect()->back()->withErrors($errors);
//        }
//
//        // Get the start and end dates of the recent month
//
//
//        $startOfMonth = now()->timezone('Asia/Gaza')->addMonthNoOverflow()->startOfMonth();
//        $endOfMonth = now()->timezone('Asia/Gaza')->addMonthNoOverflow()->endOfMonth();
//
//
//        // Retrieve the plans for the recent month and order them by date
//        $plans = Plan::where('user_id', $user->id)
//            ->whereBetween('start', [$startOfMonth, $endOfMonth])
//            ->orderBy('start')
//            ->get();
//
//        // Generate an array of working days for the recent month
//        $workingDays = [];
//        $currentDay = $startOfMonth->copy();
//
//        while ($currentDay <= $endOfMonth) {
//            // Check if the current day is a working day (excluding Fridays and Saturdays)
//            if (!$currentDay->isFriday() && !$currentDay->isSaturday()) {
//                $workingDays[] = $currentDay->format('Y-m-d');
//            }
//
//            $currentDay->addDay();
//        }
//
////        dd($workingDays);
//
//        return view('employee.show-plan', compact('plans', 'workingDays'));
//    }
//

//    public function show() {
//
//        $errors = collect([]);
//
//        $user = Auth::user();
//
//        $departmentId = $user->department->id;
//
//        $allowedDepartmentIds = [21, 10, 19, 17, 6, 20, 12];
//
//        if (!in_array($departmentId, $allowedDepartmentIds)) {
//            $errors->push('لا يمكنك إدخال/ تعديل الخطة لأنه ليس لقسمك خطة شهرية');
//            return redirect()->back()->withErrors($errors);
//        }
//
//        // Check if current date is in last week of month
//        $inLastWeek = now()->endOfMonth()->subDays(6)->isPast();
//
//        if ($inLastWeek) {
//
//            // Get the start and end dates of the recent month
//            $startOfMonth = now()->timezone('Asia/Gaza')->addMonthNoOverflow()->startOfMonth();
//            $endOfMonth = now()->timezone('Asia/Gaza')->addMonthNoOverflow()->endOfMonth();
//
//        } else {
//
//            // Use the current month
//            $startOfMonth = now()->timezone('Asia/Gaza')->startOfMonth();
//            $endOfMonth = now()->timezone('Asia/Gaza')->endOfMonth();
//
//        }
//
//        // Retrieve the plans for the month and order them by date
//        $plans = Plan::where('user_id', $user->id)
//            ->whereBetween('start', [$startOfMonth, $endOfMonth])
//            ->orderBy('start')
//            ->get();
//
//        // Generate working days array
//        $workingDays = [];
//        $currentDay = $startOfMonth->copy();
//
//        while ($currentDay <= $endOfMonth) {
//            if (!$currentDay->isFriday() && !$currentDay->isSaturday()) {
//                $workingDays[] = $currentDay->format('Y-m-d');
//            }
//            $currentDay->addDay();
//        }
//
//        return view('employee.show-plan', compact('plans', 'workingDays'));
//
//    }
    public function show(Request $request)
    {
        $errors = collect([]);
        $user = Auth::user();
        $departmentId = $user->department->id;
        $allowedDepartmentIds = [21, 10, 19, 17, 6, 20, 12];

        if (!in_array($departmentId, $allowedDepartmentIds)) {
            $errors->push('لا يمكنك إدخال/ تعديل الخطة لأنه ليس لقسمك خطة شهرية');
            return redirect()->back()->withErrors($errors);
        }

        // Get the selected month and year from the request
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        // Use the current month and year if not provided
        if (!$selectedMonth || !$selectedYear) {
            $selectedMonth = now()->month;
            $selectedYear = now()->year;
        }

        // Calculate the start and end dates based on the selected month and year
        $startOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)
            ->timezone('Asia/Gaza')
            ->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Retrieve the plans for the selected month and order them by date
        $plans = Plan::where('user_id', $user->id)
            ->whereBetween('start', [$startOfMonth, $endOfMonth])
            ->orderBy('start')
            ->get();

        // Generate working days array
        $workingDays = [];
        $currentDay = $startOfMonth->copy();

        while ($currentDay <= $endOfMonth) {
            if (!$currentDay->isFriday() && !$currentDay->isSaturday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }
            $currentDay->addDay();
        }

        return view('employee.show-plan', compact('plans', 'workingDays', 'selectedMonth', 'selectedYear'));
    }

    public function show2()
    {
        $errors = collect([]);
        $user = Auth::user();
        $departmentId = $user->department->id;
        $allowedDepartmentIds = [21, 10, 19, 17, 6, 20, 12];

        if (!in_array($departmentId, $allowedDepartmentIds)) {
            $errors->push('لا يمكنك إدخال/ تعديل الخطة لأنه ليس لقسمك خطة شهرية');
            return redirect()->back()->withErrors($errors);
        }

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

        // Retrieve the plans for the month and order them by date
        $plans = Plan::where('user_id', $user->id)
            ->whereBetween('start', [$startOfMonth, $endOfMonth])
            ->orderBy('start')
            ->get();

        // Generate working days array
        $workingDays = [];
        $currentDay = $startOfMonth->copy();

        while ($currentDay <= $endOfMonth) {
            if (!$currentDay->isFriday() && !$currentDay->isSaturday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }
            $currentDay->addDay();
        }

        return view('employee.show-plan', compact('plans', 'workingDays'));
    }


    public function showonly()
    {
        $user = Auth::user();

        // Get the start and end dates of the recent month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Retrieve the plans for the recent month and order them by date
        $plans = Plan::where('user_id', $user->id)
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



        return view('employee.showonly-plan', compact('plans', 'workingDays'));
    }

    public function edit(string $id)
    {
        $planRestriction = Auth::user()->planRestrictions->first();
        $departmentId=Auth::user()->department->id;
        $canOverrideLastWeek = $planRestriction ? $planRestriction->can_override_last_week  && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now() : false;

        // Assuming you have methods or attributes to determine these:
        $canOverrideDepartment = !$planRestriction || ($planRestriction->can_override_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now());
        $canOverrideMultiDepartment = $planRestriction ? ($planRestriction->can_override_multi_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;


        $currentDate = now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
        $lastWeekOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth()->subWeek();
        $lastDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth();


        if (!(($currentDate->gt($lastWeekOfMonth) && $currentDate->lt($lastDayOfMonth)))) {
            if ($canOverrideLastWeek) {
                try {
                    // Find the plan by ID and eager load the 'schools' relationship
                    $plan = Plan::with('schools')->findOrFail($id);

                    // Retrieve the selected school for the plan
                    $selectedSchool = $plan->schools->pluck('id')->first();

                    // As we're not restricting the list of schools based on the plan's date, we can fetch all the schools
                    $schools = School::orderByRaw("FIELD(id, 34, 35, 3434343404, 3434343405, 34343406, 34343405) DESC, name ASC")->get();

                    // Retrieve all plans with the same start date as the plan being edited
                    $existingPlans = Plan::where('start', $plan->start)->get();

                    // Pass the plan, schools, selectedSchool, canOverrideMultiDepartment, canOverrideDepartment, and existingPlans to the view
                    return view('employee.edit-plan', compact('plan','departmentId','existingPlans', 'schools', 'selectedSchool', 'canOverrideMultiDepartment', 'canOverrideDepartment', 'existingPlans'));

                } catch (\Exception $e) {

                    // Handle any exceptions that occur during the process
                    return redirect()
                        ->back()
                        ->withErrors(['error'=>'لا يمكنك تعديل الخطة الشهرية في هذا الوقت.']);
                }
            }
            else{
                return redirect()
                    ->back()
                    ->withErrors(['error'=>'لا يمكنك تعديل الخطة الشهرية في هذا الوقت.']);
            }
        }
        else{
            try {
                // Find the plan by ID and eager load the 'schools' relationship
                $plan = Plan::with('schools')->findOrFail($id);

                // Retrieve the selected school for the plan
                $selectedSchool = $plan->schools->pluck('id')->first();

                // As we're not restricting the list of schools based on the plan's date, we can fetch all the schools
                $schools = School::orderByRaw("FIELD(id, 34, 35, 3434343404, 3434343405, 34343406, 34343405) DESC, name ASC")->get();

                // Retrieve all plans with the same start date as the plan being edited
                $existingPlans = Plan::where('start', $plan->start)->get();

                // Pass the plan, schools, selectedSchool, canOverrideMultiDepartment, canOverrideDepartment, and existingPlans to the view
                return view('employee.edit-plan', compact('plan','departmentId','existingPlans', 'schools', 'selectedSchool', 'canOverrideMultiDepartment', 'canOverrideDepartment', 'existingPlans'));

            } catch (\Exception $e) {

                // Handle any exceptions that occur during the process
                return redirect()
                    ->back()
                    ->withErrors(['error'=>'لا يمكنك تعديل الخطة الشهرية في هذا الوقت.']);
            }
        }


    }


//    public function edit(string $id)
//    {
//        $planRestriction = Auth::user()->planRestrictions->first();
//        $canOverrideLastWeek = $planRestriction ? $planRestriction->can_override_last_week  && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now() : false;
//
//        $currentDate = now();
//        $currentMonth = $currentDate->month;
//        $currentYear = $currentDate->year;
//        $lastWeekOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth()->subWeek();
//        $lastDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth();
//        if ($currentDate < $lastWeekOfMonth || $currentDate > $lastDayOfMonth) {
//            if ($canOverrideLastWeek) {
//                try {
//
//                    // Find the plan by ID and eager load the 'schools' relationship
//                    $plan = Plan::with('schools')->findOrFail($id);
//
//
//                    // Get the date from the plan
//                    $selectedDate = $plan->start;
//                    // Retrieve the selected schools for the plan
//                    $selectedSchoolIds = $plan->schools->pluck('id')->toArray();
//                    $associatedSchoolIds = Plan::where('start', $selectedDate)
//                        ->whereNotIn('school_id', [34])
//                        ->pluck('school_id')
//                        ->toArray();
//
//                    // Retrieve the list of schools that are not selected for the plan on the selected date
//                    $schools = School::whereNotIn('id', $associatedSchoolIds)->get();
//
//                    // Retrieve the list of schools
////            $schools = School::all();
//
//                    // Retrieve the selected schools for the plan
//                    $selectedSchool = $plan->schools->pluck('id')->first();
//
//                    // Pass the plan, schools, and selectedSchools to the view
//                    return view('employee.edit-plan', compact('plan', 'schools', 'selectedSchool'));
//                } catch (\Exception $e) {
//                    // Handle any exceptions that occur during the process
////                    return redirect()->back()->with('error', 'لا يمكنك تعديل الخطة الشهرية في هذا الوقت.');
//                    return redirect()
//                        ->back()
//                        ->withErrors(['error'=>'لا يمكنك تعديل الخطة الشهرية في هذا الوقت.']);
//                }
//
//            }
//        }
//
////        return redirect()->back()->with('success', 'لا يمكنك تعديل الخطة الشهرية في هذا الوقت.');
//        return redirect()
//            ->back()
//            ->withErrors(['error'=>'لا يمكنك تعديل الخطة الشهرية في هذا الوقت.']);
//    }


    /**
     * Update the specified resource in storage.
     */
//    public function update(Request $request, Plan $plan)
//    {
//        // Validate the request data
//        $validatedData = $request->validate([
//            'date' => 'required|date',
//            'schools' => 'nullable|array',
//            'schools.*' => 'exists:schools,id',
//        ]);
//
//        try {
//            // Update the plan's date
//            $plan->start = $validatedData['date'];
//            $plan->save();
//
//            // Update the plan's schools
//            $plan->schools()->sync($validatedData['schools']);
//
//            // Redirect to the view page or any other appropriate action
//            return redirect()->route('employee.view-plan')->with('success', 'تم تحديث الخطة الشهرية بنجاح!');
//        } catch (\Exception $e) {
//            // Handle any exceptions that occur during the update process
//            return back()->with('error', 'حدث خطأ أثناء تحديث الخطة الشهرية. الرجاء المحاولة مرة أخرى.');
//        }
//    }

    public function update(Request $request, Plan $plan)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'date' => 'required|date',
            'school' => 'nullable|exists:schools,id',
        ]);

        try {
            // Update the plan's date
            $plan->start = $validatedData['date'];

            // Update the plan's school
            if ($validatedData['school']) {
                $plan->school_id = $validatedData['school'];
            } else {
                $plan->school_id = null;
            }

            $plan->save();
            //parse $plan->start to get month and year
            $selectedMonth = Carbon::parse($plan->start)->month;
            $selectedYear = Carbon::parse($plan->start)->year;

            // Redirect to the view page or any other appropriate action
          // return redirect()->route('employee.show-plan')->with('success', 'تم تحديث الخطة الشهرية بنجاح!');
            return redirect()->route('employee.show-plan', ['month' => $selectedMonth, 'year' => $selectedYear])->with('success', 'تم تحديث الخطة الشهرية بنجاح!');


        } catch (\Exception $e) {
            // Handle any exceptions that occur during the update process
            return back()->with('error', 'حدث خطأ أثناء تحديث الخطة الشهرية. الرجاء المحاولة مرة أخرى.');
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{

            $plan = Plan::findOrFail($id);

            // Delete the plan
            $plan->delete();

            session()->flash('success', 'تم الحذف بنجاح');

            // Redirect to a success page or return a response
            return redirect()->route('employee.show-plan');

        }
        catch(\Exception $e){

            return back()->with('error', 'حدث خطأ أثناء حذف الخطة الشهرية. الرجاء المحاولة مرة أخرى.');
        }

    }

    //Add single day to plan
    public function addDay_old($date)
    {
        $schools = School::all();
        return view('employee.add-day', compact('date', 'schools'));
    }
    public function addDay($date)
    {

        $errors = collect([]);
        $departmentId = Auth::user()->department->id;
        $planRestriction = Auth::user()->planRestrictions->first();
        $canOverrideDepartment = !$planRestriction || ($planRestriction->can_override_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now());

        $canOverrideMultiDepartment = $planRestriction ? ($planRestriction->can_override_multi_department && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;
        $canOverrideLastWeek = $planRestriction ? ($planRestriction->can_override_last_week && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;
//dd($canOverrideLastWeek);
        $currentDate = now();
        $targetDate=Carbon::parse($date);
        try {

            $existingPlans = Plan::where('start', $targetDate->format('Y-m-d'))
                ->get();


            $planRestriction = Auth::user()->planRestrictions->first();
            $canOverrideLastWeek = $planRestriction ? ($planRestriction->can_override_last_week && $planRestriction->override_start_date <= now() && $planRestriction->override_end_date >= now()) : false;

        } catch (\Exception $e) {
            $errors->push('اختر الشهر والسنة المطلوبة.');
        }

        // Calculate the last day of the month
        $lastDayOfMonth = $currentDate->copy()->endOfMonth();

        // Check if the current date is within the allowed range for entering the plan
        $lastWeekOfMonth = $lastDayOfMonth->copy()->subWeek()->addDay();
//        if ($currentDate->month != $lastDayOfMonth->month || $currentDate < $lastWeekOfMonth || $currentDate > $lastDayOfMonth) {
//            if (!$canOverrideLastWeek) {
////                $validDates = $lastWeekOfMonth->format('d/m/Y') . ' - ' . $lastDayOfMonth->format('d/m/Y');
//                $errorMessage = 'لا يمكنك الاضافة على الخطة الشهرية في هذا الوقت.';
//                $errors->push($errorMessage);
//            }
//        }

        $lastWeekOfMonth->modify('-1 day');

        if (!$canOverrideLastWeek)
        {
            if ($currentDate > $lastDayOfMonth || $currentDate < $lastWeekOfMonth) {
               // dd($currentDate > $lastDayOfMonth , $currentDate < $lastWeekOfMonth,$currentDate,$lastDayOfMonth,$lastWeekOfMonth);
                $errorMessage = 'لا يمكنك الاضافة على الخطة الشهرية في هذا الوقت.';
                $errors->push($errorMessage);
            }
        }

        if ($errors->isNotEmpty()) {
            return redirect()->back()->withErrors($errors);
        }

        $schools = School::orderByRaw("FIELD(id, 34, 35, 3434343404, 3434343405, 34343406, 34343405) DESC, name ASC")->get();
        //dd($date, $schools, $existingPlanSchools, $existingPlans, $canOverrideMultiDepartment);
        return view('employee.add-day', compact('date', 'schools', 'existingPlans','canOverrideDepartment', 'canOverrideMultiDepartment','departmentId'));
    }

    public function storeDay(Request $request)
    {
//dd($request->all());

        $date = $request->input('date');
        $schools = $request->input('schools');
        $user = Auth::user();
        $departmentId = $user->department->id;
        //dd($date, $schools, $user->id, $departmentId);



        foreach ($schools as $schoolId) {
            $plan = new Plan();
            $plan->school_id = $schoolId;
            $plan->user_id = $user->id;
            $plan->department_id = $departmentId;
            $plan->start = $date;
            $plan->end = $date;

            $plan->save();
        }

        session()->flash('success', 'تم الحفظ بنجاح');

        return redirect()->route('employee.show-plan');
    }

    public function storeDay_old(Request $request)
    {

        $date = $request->input('date');
        $schools = $request->input('schools');
        $user = Auth::user();
        $departmentId = $user->department->id;


        foreach ($schools as $schoolId) {
            $plan = new Plan();
            $plan->school_id = $schoolId;
            $plan->user_id = $user->id;
            $plan->department_id = $departmentId;
            $plan->start = $date;
            $plan->end = $date;

            $plan->save();
        }

        session()->flash('success', 'تم الحفظ بنجاح');

        return redirect()->route('employee.show-plan');
    }

    public function monthlyPlan(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        $user = Auth::user()->id;

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

        return view('employee.monthly-plan', compact('plans', 'workingDays', 'date','selectedMonth', 'selectedYear'));
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
