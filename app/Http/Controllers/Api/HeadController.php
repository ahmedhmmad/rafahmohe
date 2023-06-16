<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Department;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class HeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();

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
            if ($currentDay->isWeekday()) {
                $workingDays[] = $currentDay->format('Y-m-d');
            }

            $currentDay->addDay();
        }
        return response()->json([
            'plans' => $plans,
            'workingDays' => $workingDays,
            'message' => 'success'
        ]);
    }

    public function showDepartmentEmp()
    {
        $users = User::where('department_id', auth()->user()->department_id)
            ->where('id', '!=', auth()->id())
            ->get();
        return UserResource::collection($users);
//        return response()->json([
//            'users' => $users,
//            'message' => 'success'
//        ]);
    }



    public function showDepEmpPlan($id)
    {
        $user = User::findorfail($id);

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
        return response()->json([
            'plans' => $plans,
            'workingDays' => $workingDays,
            'message' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
