<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_assignment_id', 'user_id', 'role_id', 'comment'];

    public function ticketAssignment()
    {
        return $this->belongsTo(TicketAssignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
