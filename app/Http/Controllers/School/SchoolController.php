<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Plan;
use App\Models\School;
use App\Models\SchoolVisit;
use App\Models\User;
use App\Notifications\CreateNewSchoolVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
    public function fetchDepartmentUsers(Request $request)
    {
        $userId = $request->input('user_id');

        $user = optional(User::find($userId));

        // Get the department ID or set it to null if the user is not found
        $departmentId = $user->department_id ?? null;


        $departmentMembers = User::where('department_id', $departmentId)
            ->where('id', '!=', $userId) // Exclude the selected user from the list
            ->get(['id', 'name']);

        // Return the department members as JSON
        return response()->json($departmentMembers);
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


    public function showSchoolsVisits(Request $request)
    {
        $selectedSchool = $request->input('school');
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');
        $selecteddepartment=$request->input('selected_department_id');
        $selectedUser=$request->input('selected_user_id');

        $query = SchoolVisit::query();

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
        if ($selecteddepartment) {

            $query->where('department_id', $selecteddepartment);
        }
        if ($selectedUser) {

            $query->where('user_id', $selectedUser);
        }

        $schoolVisits = $query->orderBy('visit_date', 'desc')->paginate(10); // Order by visit_date in descending order


        $schools=School::all();
        $departments=Department::all();
        $users=User::all();



        return view('admin.show-schools-visits',compact('schoolVisits','schools','departments','users'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Get all school visits from schoolvisits table and paginate them if there is any.
        $schoolVisits = SchoolVisit::where('school_id', auth()->user()->id)
            ->orderBy(DB::raw('DATE(visit_date)'), 'desc') // Order by the date (newest to oldest)
            ->orderBy('coming_time', 'desc') // Order by the time (latest to earliest)
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
            'companions' => 'nullable|array', // Add validation for companions field as an array
        ]);

        // Get job title for user_id from users table
        $user = User::where('id', $validatedData['user_id'])->first();
        $mainVisitor = $user->name;
        $departmentId = $user->department_id;

        // Store the validated data in the database or perform other actions

        $storeVisit = new SchoolVisit();
        $storeVisit->school_id = auth()->user()->id;
        $storeVisit->user_id = $validatedData['user_id'];
        $storeVisit->job_title = $user->job_title;
        $storeVisit->visit_date = $validatedData['visitDate'];
        $storeVisit->coming_time = $validatedData['comingTime'];
        $storeVisit->leaving_time = $validatedData['leavingTime'];
        $storeVisit->purpose = $validatedData['purpose'];
        $storeVisit->activities = $validatedData['activities'];
        $storeVisit->department_id = $departmentId;
        $storeVisit->save();

        // Store the companions' data
        if (isset($validatedData['companions']) && is_array($validatedData['companions'])) {
            foreach ($validatedData['companions'] as $companionId) {
                // Check if the companion ID exists in the users table
                $companionUser = User::find($companionId);
                if ($companionUser) {
                    // Store the companion's data as a new row in the school_visits table
                    $companionVisit = new SchoolVisit();
                    $companionVisit->school_id = auth()->user()->id;
                    $companionVisit->user_id = $companionId;
                    $companionVisit->job_title = $companionUser->job_title;
                    $companionVisit->visit_date = $validatedData['visitDate'];
                    $companionVisit->coming_time = $validatedData['comingTime'];
                    $companionVisit->leaving_time = $validatedData['leavingTime'];
                    $companionVisit->purpose = $validatedData['purpose'];
                    $companionVisit->activities = $validatedData['activities'];
                    $companionVisit->department_id = $departmentId;
                    $companionVisit->save();
                }
            }
        }
        $manager=User::where('role_id',1)->get();
        $data=[
            'ticketSubject'=>auth()->user()->name,
            'ticketSchoolName'=>$mainVisitor,
            'ticketId'=>$storeVisit->id,
        ];
        Notification::send($manager,new CreateNewSchoolVisit($data));

        // Return a JSON response indicating success
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
