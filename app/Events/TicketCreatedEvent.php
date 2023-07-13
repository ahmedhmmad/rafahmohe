<?php
namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TicketCreatedEvent implements ShouldBroadcast
{
    public $ticket;

    /**
     * Create a new event instance.
     *
     * @param  Ticket  $ticket
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {


        return new Channel('department-head-channel');
    }
}
