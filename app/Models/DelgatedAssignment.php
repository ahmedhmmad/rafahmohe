<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelgatedAssignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_assignment_id',
        'assigned_by',
        'assigned_to',
        'comments',
        'attachment',
    ];

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(TempEmployee::class, 'assigned_to');
    }

    public function ticketAssignment()
    {
        return $this->belongsTo(TicketAssignment::class);
    }



}
