<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['subject', 'user_id','department_id', 'description', 'attachment', 'status'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketAssignments()
    {
        return $this->hasMany(TicketAssignment::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function ticketComments()
    {
        return $this->hasManyThrough(TicketComment::class, TicketAssignment::class, 'ticket_id', 'ticket_assignment_id');
    }

    public function tempEmployees()
    {
        return $this->belongsToMany(TempEmployee::class, 'delegated_assignments', 'ticket_assignment_id', 'assigned_to');
    }




}
