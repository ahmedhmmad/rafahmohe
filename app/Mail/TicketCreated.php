<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class TicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    /**
     * Create a new message instance.
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        $schoolName =Auth::user()->name;
        return $this->view('emails.ticket-created')
            ->subject('تم تقديم طلب صيانة جديد')
            ->from('ahammad@moegaza.edu.ps', $schoolName);
    }
}
