<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\School;
use App\Models\SchoolVisit;
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


        $users = User::where('name', 'LIKE',  '%' . $name . '%')->get(['id', 'name', 'job_title']);

        return response()->json($users);
    }
    public function index(Request $request)
    {

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $month = $request->input('month', $currentMonth);
        $year = $request->input('year', $currentYear);

        $schoolPlannedVisits = Plan::where('school_id', auth()->user()->id)
            ->whereMonth('start', $month)
            ->whereYear('start', $year)
            ->get();
       // dd($schoolPlannedVisits);

        return view('school.show-school-visitors', compact('schoolPlannedVisits'));
    }

//    public function viewDepVisits()
//
//    {
//        //Get Department id for Logged user
//        $departmentId = auth()->user()->department_id;
//        //get all users for this department
//        $users = User::where('department_id', $departmentId)->get();
//        //get all school visits for this department using their ids
//        $schoolVisits = SchoolVisit::whereIn('user_id', $users->pluck('id'))->paginate(10);
//        $schools=School::all();
//
//        return view('head.show-schools-visits',compact('schoolVisits','schools'));
//    }
    public function viewDepVisits(Request $request)
    {
//        dd($request->all());
        // Get Department id for Logged user
        $departmentId = auth()->user()->department_id;
        // Get all users for this department
        $users = User::where('department_id', $departmentId)->get();

        // Apply filters based on selected school and month/year
        $selectedSchool = $request->input('school');
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        $query = SchoolVisit::whereIn('user_id', $users->pluck('id'));

        if ($selectedSchool) {
            $query->where('school_id', $selectedSchool);
        }
        if($selectedMonth && !$selectedYear){
            $query->whereMonth('visit_date', $selectedMonth);

        }
        if($selectedYear && !$selectedMonth){
            $query->whereYear('visit_date', $selectedYear);
        }

        if ($selectedMonth && $selectedYear) {
            $query->whereMonth('visit_date', $selectedMonth)->whereYear('visit_date', $selectedYear);

        }



        // Get the filtered school visits and paginate the results
        $schoolVisits = $query->paginate(10);
        $schools = School::all();

        return view('head.show-schools-visits', compact('schoolVisits', 'schools'));
    }


    public function showSchoolsVisits()
    {

        $schoolVisits = SchoolVisit::paginate(10);
        $schools=School::all();



        return view('admin.show-schools-visits',compact('schoolVisits','schools'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Get all school visits from schoolvisits table and paginate them if there is any.
        $schoolVisits = SchoolVisit::where('school_id', auth()->user()->id)
            ->paginate(10);



        return view('school.create-school-visits',compact('schoolVisits'));
    }

    public function addvisits()
    {
        return view('school.add-school-visits');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'visitorName' => 'required|string',
            'visitDate' => 'required|date',
            'comingTime' => 'required',
            'leavingTime' => 'required',
            'purpose' => 'required|string',
            'activities' => 'nullable|string',
        ]);

        //Get job title for user_id from users table
        $user=User::where('id',$validatedData['user_id'])->first();

        // Store the validated data in the database or perform other actions



        $storeVisit= new SchoolVisit();
        $storeVisit->school_id=auth()->user()->id;
        $storeVisit->user_id=$validatedData['user_id'];
        $storeVisit->job_title=$user->job_title;
        $storeVisit->visit_date=$validatedData['visitDate'];
        $storeVisit->coming_time=$validatedData['comingTime'];
        $storeVisit->leaving_time=$validatedData['leavingTime'];
        $storeVisit->purpose=$validatedData['purpose'];
        $storeVisit->activities=$validatedData['activities'];
        $storeVisit->save();

        //Return a JSON response indicating success
        return response()->json(['message' => 'تم الحفظ بنجاح']);

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
    public function destroy($id)
    {
        // Find the record in the database
        $schoolvisit = SchoolVisit::findOrFail($id);

        $schoolvisit->delete();

        // Optionally, you can redirect the user after deleting the record
        return redirect()->back()->with('success', 'تم حذف الزيارة بنجاح.');
    }

}
