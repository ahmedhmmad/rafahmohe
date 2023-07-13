<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Mail\TicketCreated;
use App\Events\TicketCreatedEvent;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\User;
use App\Notifications\SMSNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Config;
use Pusher\Pusher;



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
    public function getTicketDetails(Request $request)
    {
        $ticketId = $request->input('ticketId');
        // Retrieve the ticket details based on the ticket ID
        $ticket = Ticket::find($ticketId);

        // Process and prepare the ticket details as needed

        // Render the ticket details HTML
        $ticketDetailsHtml = view('admin.ticket-details', compact('ticket'))->render();

        // Return the ticket details HTML as a JSON response
        return response()->json([
            'ticketDetails' => $ticketDetailsHtml
        ]);
    }

    // app/Http/Controllers/TicketController.php

    public function getComments(Request $request)
    {
        $ticketId = $request->input('ticketId');
        $ticket = Ticket::find($ticketId);

        // Retrieve the comments for the ticket
        $comments = $ticket->ticketAssignments->pluck('comments')->filter()->toArray();

        return response()->json(['comments' => $comments]);
    }



    public function showTicketsAdmin()
    {
        // Retrieve the tickets belonging to the user
        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(10);
        $departments = Department::all();

        // Calculate ticket counts
        $openTicketsCount = Ticket::where('status', 'open')->count();
        $assignedTicketsCount = Ticket::where('status', 'assigned')->count();
        $onProgressTicketsCount = Ticket::where('status', 'on-progress')->count();
        $closedTicketsCount = Ticket::where('status', 'closed')->count();

        return view('admin.show-tickets', [
            'tickets' => $tickets,
            'departments' => $departments,
            'openTicketsCount' => $openTicketsCount,
            'assignedTicketsCount' => $assignedTicketsCount,
            'onProgressTicketsCount' => $onProgressTicketsCount,
            'closedTicketsCount' => $closedTicketsCount,
        ]);
    }
    public function showTicketsDep()
    {
        // Retrieve the tickets belonging to the user department
        $user = Auth::user();
        $department_id = $user->department_id;

        $tickets = Ticket::where('department_id', $department_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

         $assignedUserNames = [];
        foreach ($tickets as $ticket) {
            $assignedUserId = $ticket->assigned_to;
            $assignedUser = User::find($assignedUserId);

            $assignedUserName = $assignedUser ? $assignedUser->name : '';
            $parts = explode(' ', $assignedUserName);
            $firstName = $parts[0] ?? '';
            $lastName = end($parts) ?? '';
            $fullName = $firstName . ' ' . $lastName;
            $assignedUserNames[$ticket->id] = $fullName;
        }


        // Calculate ticket counts for the department
        $openTicketsCount = Ticket::where('department_id', $department_id)
            ->where('status', 'open')->count();
        $assignedTicketsCount = Ticket::where('department_id', $department_id)
            ->where('status', 'assigned')->count();
        $onProgressTicketsCount = Ticket::where('department_id', $department_id)
            ->where('status', 'on-progress')->count();
        $closedTicketsCount = Ticket::where('department_id', $department_id)
            ->where('status', 'closed')->count();

        return view('head.show-assigned-tickets', [
            'tickets' => $tickets,
            'assignedUserNames' => $assignedUserNames,
            'openTicketsCount' => $openTicketsCount,
            'assignedTicketsCount' => $assignedTicketsCount,
            'onProgressTicketsCount' => $onProgressTicketsCount,
            'closedTicketsCount' => $closedTicketsCount,

        ]);
    }


    public function showAssignedTickets()
    {

        $user = Auth::user();
        $assignedTickets = TicketAssignment::join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->where('ticket_assignments.user_id', $user->id)
            ->orderBy('ticket_assignments.created_at', 'desc')
            ->select('ticket_assignments.*', 'tickets.subject', 'tickets.status')
            ->paginate(10);



        return view('employee.show-assigned-tickets', ['tickets' => $assignedTickets]);




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

        $assignedUserNames = [];
        foreach ($tickets as $ticket) {
            $assignedUserId = $ticket->assigned_to;
            $assignedUser = User::find($assignedUserId);

            $assignedUserName = $assignedUser ? $assignedUser->name : '';
            $parts = explode(' ', $assignedUserName);
            $firstName = $parts[0] ?? '';
            $lastName = end($parts) ?? '';
            $fullName = $firstName . ' ' . $lastName;
            $assignedUserNames[$ticket->id] = $fullName;
        }
       // dd($assignedUserNames);

        return view('head.show-tickets', [
            'tickets' => $tickets,
            'users' => $users,
            'assignedUserNames' => $assignedUserNames,
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

        // Send email to the selected department
        $department = Department::find($request->input('department'));
        $departmentemail= $department->email;
        if ($department) {
            $emailData = [
                'ticket' => $ticket,
                // Add any additional data you want to pass to the email view
            ];

            if ($departmentemail) {
               // Mail::to($departmentemail)->send(new TicketCreated($emailData));
            }
            event(new TicketCreatedEvent($ticket));

        }


            //Send SMS notification to the department head
            // Get the department head user
//            $departmentHead = User::where('department_id', $request->input('department'))
//                ->where('role_id', 2)
//                ->first();
//            if ($departmentHead) {
//                $mobileNumber = $departmentHead->phone;
//
//                // Send the SMS notification using Twilio
//                if ($mobileNumber) {
//                    $accountSid = Config::get('services.twilio.sid');
//                    $authToken = Config::get('services.twilio.token');
//                    $twilioPhoneNumber = Config::get('services.twilio.phone_number');
//                   // dd($accountSid, $authToken, $twilioPhoneNumber);
//
//                    $client = new Client($accountSid, $authToken);
//
//                    $countryCode = '+970'; // Replace with the appropriate country code
//
//                    // Add the country code to the mobile number
//                    $formattedMobileNumber = $countryCode . $mobileNumber;
//
//
//                    $message = $client->messages->create(
//                        $formattedMobileNumber,
//                        [
//                            'from' => $twilioPhoneNumber,
//                            'body' => 'A new ticket has been created. Please login to your account to view the ticket details.'
//                        ]
//                    );
//
//                }
//            }


//            // Retrieve the department head user
//            $departmentHead = User::where('department_id', $request->input('department'))
//                ->where('role_id', 2)
//                ->first();
//
//            if ($departmentHead) {
//                $departmentHeadChannel = 'department-head-' . $departmentHead->id;
//
//                // Trigger a Pusher event to notify the department head
//                $pusher = new Pusher(
//                    env('PUSHER_APP_KEY'),
//                    env('PUSHER_APP_SECRET'),
//                    env('PUSHER_APP_ID'),
//                    [
//                        'cluster' => env('PUSHER_APP_CLUSTER'),
//                        'useTLS' => true,
//                    ]
//                );
//
//                $pusher->trigger($departmentHeadChannel, 'ticket-created', $ticket);
//            }
//
//        }



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

    public function viewTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        if (!$ticket) {
            // Handle the case where the ticket is not found
            // For example, you can redirect back with an error message
            return redirect()->back()->with('error', 'Ticket not found.');
        }



        return view('employee.view-ticket', compact('ticket'));
    }

    public function changeStatus(Request $request, $ticketId)
    {

        $ticket = Ticket::findOrFail($ticketId);

        $status = $request->input('status');

        // Perform necessary validation and logic here

        // Update the status of the ticket
        $ticket->status = $status;
        $ticket->save();

        // Redirect back or to another route as needed
        return redirect()->back()->with('success', 'تم تغيير حالة التذكرة بنجاح.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $assignedTicket = TicketAssignment::where('ticket_id', $ticket->id)->first();

        // Append the new comment to the existing comments using the .= concatenation operator
        $assignedTicket->comments .= "\n" . $request->input('comment');

        $assignedTicket->save();
        return redirect()->back()->with('success', 'Comment added successfully.');
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
        //return employee name who has been assigned

        return redirect()->back()->with('success', 'تم تعيين المهمة بنجاح');

    }
    public function assignTicketDepAdmin(Request $request, $ticketId)
    {
        $department_id = $request->input('department_id');

        $ticket = Ticket::find($ticketId);
        $ticket->department_id = $department_id;

        $ticket->save();


        return redirect()->back()->with('success', 'تم تعيين المهمة بنجاح');
    }





}
