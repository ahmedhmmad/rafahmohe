<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Plan;
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
    public function index()
    {
       $schoolvisits=Plan::where('school_id',auth()->user()->id)->get();
         $schoolvisitscount=Plan::where('school_id',auth()->user()->id)->count();
                  return view('school.show-school-visitors',compact('schoolvisits','schoolvisitscount'));
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
