<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
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
        $errors = collect([]);
        $departmentId = Auth::user()->department->id;
        $planRestriction = Auth::user()->planRestrictions->first();

        $canOverrideDepartment = $planRestriction ? $planRestriction->can_override_department : false;



        // Check if the month is a valid value
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $errors->push('اختر الشهر المطلوب.');
        }

        // Check if the year is a valid value
        if (!is_numeric($year) || $year < 2020 || $year > 2099) {
            $errors->push('اختر السنة المطلوبة.');
        }

        try {
            // Check if the specified month and year create a valid date
            $currentMonth = Carbon::create($year, $month)->month;
            $currentYear = Carbon::create($year, $month)->year;
            $lastDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth();

            $existingPlans = Plan::whereMonth('start', $currentMonth)
                ->whereYear('start', $currentYear)
                ->where('department_id', $departmentId)
                ->get();

            $existingPlanDates = $existingPlans->pluck('start')->toArray();

            $planRestriction = Auth::user()->planRestrictions->first();
            $canOverrideLastWeek = $planRestriction ? $planRestriction->can_override_last_week : false;

        } catch (\Exception $e) {
            $errors->push('اختر الشهر والسنة المطلوبة.');
        }

        // Check if the current date is within the allowed range for entering the plan
        $currentDate = now();
        $lastWeekOfMonth = Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth()->subWeek();

        if ($currentDate->addWeek()->month != $month) {
            if ($currentDate < $lastWeekOfMonth || $currentDate > $lastDayOfMonth) {
                if (!$canOverrideLastWeek) {
                    $errors->push('لا يمكن إدخال الخطة فقط خلال الأسبوع الأخير من الشهر الحالي.');
                }
            }
        }

        if ($errors->isNotEmpty()) {
            return redirect()->back()->withErrors($errors);
        }

        $schools = School::all();

        return view('employee.create-plan', compact('schools', 'month', 'year', 'existingPlanDates', 'existingPlans','canOverrideDepartment'));

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        session()->flash('success', 'تم الحفظ بنجاح');

        return redirect()->route('home');
    }
    public function show()
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
            // Check if the current day is a working day (excluding Fridays and Saturdays)
            if (!$currentDay->isFriday() && !$currentDay->isSaturday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }

            $currentDay->addDay();
        }

//        dd($workingDays);

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Find the plan by ID and eager load the 'schools' relationship
            $plan = Plan::with('schools')->findOrFail($id);

            // Retrieve the list of schools
            $schools = School::all();

            // Retrieve the selected schools for the plan
            $selectedSchool = $plan->schools->pluck('id')->first();

            // Pass the plan, schools, and selectedSchools to the view
            return view('employee.edit-plan', compact('plan', 'schools', 'selectedSchool'));
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            return back()->with('error', 'حدث خطأ أثناء تحميل البيانات. الرجاء المحاولة مرة أخرى.');
        }
    }


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

            // Redirect to the view page or any other appropriate action
            return redirect()->route('employee.show-plan')->with('success', 'تم تحديث الخطة الشهرية بنجاح!');
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
    public function addDay($date)
    {
        $schools = School::all();
        return view('employee.add-day', compact('date', 'schools'));
    }

    public function storeDay(Request $request)
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

        return view('employee.monthly-plan', compact('plans', 'workingDays', 'date'));
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
