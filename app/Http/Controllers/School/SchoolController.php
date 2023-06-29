<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function usersSearch(Request $request)
    {
        $name = $request->input('name');

        $users = User::where('name', 'LIKE', "%$name%")->get(['id', 'name', 'job_title']);

        return response()->json($users);
    }
    public function index()
    {
       $schoolvisits=Plan::where('school_id',auth()->user()->id)->get();
         $schoolvisitscount=Plan::where('school_id',auth()->user()->id)->count();
             dd($schoolvisits);
       return view('school.show-school-visitors',compact('schoolvisits','schoolvisitscount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Get the school visits for today (from plans table) if any in this day.
        $plans = Plan::where('school_id', auth()->user()->id)
            ->whereDate('start', now())
            ->get();


        return view('school.create-school-visits',compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        $this->validate($request, [
            'purpose.*' => 'required',
            'activities.*' => 'required',
        ]);

        $visitsData = [];

        // Prepare the data for storing school visits
        foreach ($request->input('purpose') as $index => $purpose) {
            $visitsData[] = [
                'visit_date' => $request->input('visit_date')[$index],
                'coming_time' => $request->input('coming_time')[$index],
                'leaving_time' => $request->input('leaving_time')[$index],
                'user_id' => $request->input('user_id')[$index],
                'school_id' => $request->input('school_id')[$index],
                'job_title' => $request->input('job_title')[$index],
                'purpose' => $purpose,
                'activities' => $request->input('activities')[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the school visits into the database
        SchoolVisit::insert($visitsData);

        return redirect()->back()->with('success', 'School visits stored successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
