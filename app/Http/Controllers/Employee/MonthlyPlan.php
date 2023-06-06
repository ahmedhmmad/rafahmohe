<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Auth;


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
    public function create($month, $year)
    {
        $schools = School::all();
        return view('employee.create-plan',compact('schools','month','year'));

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

        // Get the start and end dates of the recent month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Retrieve the plans for the recent month and order them by date
        $plans = Plan::whereBetween('start', [$startOfMonth, $endOfMonth])
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



        return view('employee.show-plan', compact('plans', 'workingDays'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plan = Plan::findOrFail($id);

       return view('employee.edit-plan',compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'date' => 'required|date',
            'schools' => 'nullable|array',
            'schools.*' => 'exists:schools,id',
        ]);

        try {
            // Find the plan by ID
            $plan = Plan::findOrFail($id);

            // Update the plan's date
            $plan->start = $validatedData['date'];
            $plan->save();

            // Update the plan's schools
            $plan->schools()->sync($validatedData['schools']);

            // Redirect to the view page or any other appropriate action
            return redirect()->route('employee.view-plan')->with('success', 'تم تحديث الخطة الشهرية بنجاح!');
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
        $plan = Plan::findOrFail($id);

        // Delete the plan
        $plan->delete();

        session()->flash('success', 'تم الحذف بنجاح');

        // Redirect to a success page or return a response
        return redirect()->route('employee.show-plan');
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
}
