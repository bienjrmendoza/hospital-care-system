<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<div style="background-color:#155edb; text-align:center; padding:20px;">
    <a href="https://tabhcare.online/" target="_blank">
        <img src="https://tabhcare.online/assets/images/white-logo.png" alt="TabhCare" style="max-width:250px; height:auto;">
    </a>
</div>

<div style="padding:20px; text-align:left;">

    @if($scheduleRequest->status === 'cancelled')
        <h2 style="color:#d9534f; font-size:24px; margin:0;">Schedule Request Cancelled</h2>
        <p style="font-size:16px; line-height:1.5; margin-top:10px;">
            The patient has cancelled their previously submitted schedule request. 
            This appointment slot is now available again for other patients.
        </p>
    @else
        <h2 style="color:#155edb; font-size:24px; margin:0;">New Schedule Request</h2>
        <p style="font-size:16px; line-height:1.5; margin-top:10px;">
            A patient has submitted a new schedule request.
        </p>
    @endif

    <p style="font-size:16px; font-weight:600; margin:15px 0 5px 0;">Request Details:</p>
    <ul style="font-size:16px; line-height:1.5; padding-left:20px; margin:0;">
        <li><strong>Patient:</strong> {{ $scheduleRequest->user->name }}</li>
        <li><strong>Date:</strong> {{ $scheduleRequest->schedule->date->format('F j, Y') }}</li>
        <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($scheduleRequest->schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($scheduleRequest->schedule->end_time)->format('g:i A') }}</li>
    </ul>

</div>

<div style="background-color:#155edb; height:10px;"></div>

</div>
