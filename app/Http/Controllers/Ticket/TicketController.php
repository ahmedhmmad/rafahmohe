<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

            $user = Auth::user();
            $departments = Department::all();

            // Retrieve the tickets belonging to the user
            $tickets = Ticket::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('ticket.show-tickets', [
                'tickets' => $tickets,
                'departments' => $departments
            ]);



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = \App\Models\Department::all();
        return view('ticket.create-ticket')->with('departments',$departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request->all());

        $ticket = new Ticket();
        $ticket->subject = $request->input('subject');
        $ticket->department_id = $request->input('department');
        $ticket->description = $request->input('description');
        $ticket->user_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {

            $attachment = $request->file('attachment');
            $filename = $attachment->getClientOriginalName();
            $attachment->storeAs('attachments', $filename); // Store the attachment in a directory
            $ticket->attachment = $filename;
        }
        //dd($ticket);

        $ticket->save();


        // Redirect to a success page or ticket details page
        return redirect()->route('school.show-tickets')->with('success', 'Ticket created successfully.');

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
