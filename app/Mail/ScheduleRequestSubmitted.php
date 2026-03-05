<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ScheduleRequestSubmitted extends Mailable
{
    public $scheduleRequest;

    public function __construct($scheduleRequest)
    {
        $this->scheduleRequest = $scheduleRequest;
    }

    public function build()
    {
        $subject = $this->scheduleRequest->status === 'cancelled'
            ? 'Schedule Request Cancelled'
            : 'New Schedule Request';

        return $this->subject($subject)
                    ->view('emails.schedule-request');
    }
}