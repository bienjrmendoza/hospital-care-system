<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ScheduleRequestResponded extends Mailable
{
    public $scheduleRequest;

    public function __construct($scheduleRequest)
    {
        $this->scheduleRequest = $scheduleRequest;
    }

    public function build()
    {
        $status = $this->scheduleRequest->status === 'accepted' ? 'Approved' : 'Declined';
        return $this->subject("Your Schedule Request has been {$status}")
                    ->view('emails.schedule-request-responded');
    }
}