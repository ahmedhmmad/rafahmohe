<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\User;
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
    public function showDepTickets()
    {

        $user = Auth::user();
        $department_id = $user->department_id;


        // Retrieve the tickets belonging to the user
        $tickets = Ticket::where('department_id', $department_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $users = User::where('department_id', $department_id)
            ->where('id', '!=', $user->id)
            ->get();


        return view('head.show-tickets', [
            'tickets' => $tickets,
            'users'=>$users
        ]);



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = \App\Models\Department::all();
        return view('ticket.create-ticket', [
            'departments' => $departments,

        ]);
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
    public function assignTicket(Request $request, $ticketId)
    {

        $selectedValue = $request->input('assign_to');
        if ($selectedValue === 'self') {

            $assignedUserId = auth()->user()->id;

        } else {
            $assignedUserId = $request->input('user_id');
        }

        $ticket = Ticket::find($ticketId);
        $ticket->assigned_to = $assignedUserId;
        $ticket->status = 'assigned';
        $ticket->save();


        // Create a new TicketAssignment record
        $ticketAssignment = new TicketAssignment();
        $ticketAssignment->ticket_id = $ticketId;
        $ticketAssignment->user_id = $assignedUserId;

        $ticketAssignment->save();
        //dd($ticketAssignment);

        return redirect()->back()->with('success', 'تم تعيين المهمة بنجاح');



    }

}
