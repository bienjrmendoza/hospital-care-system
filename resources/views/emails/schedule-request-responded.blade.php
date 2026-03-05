<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<div style="font-family: 'Poppins', sans-serif!important; max-width:600px; margin:0 auto; border:1px solid #e0e0e0; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); color:#333;">

    <div style="background-color:#155edb; text-align:center; padding:20px;">
        <a href="https://tabhcare.online/" target="_blank">
            <img src="https://tabhcare.online/assets/images/white-logo.png" alt="TabhCare" style="max-width:250px; height:auto;">
        </a>
    </div>

    <div style="padding:20px; text-align:left;">
        
        @if($scheduleRequest->status === 'declined')
            <h2 style="color:#d9534f; font-size:24px; margin:0;">Your Schedule Request has been {{ ucfirst($scheduleRequest->status) }}</h2>
        @else
            <h2 style="color:#155edb; font-size:24px; margin:0;">Your Schedule Request has been {{ ucfirst($scheduleRequest->status) }}</h2>
        @endif

        <p style="font-size:16px; font-weight:600; margin:10px 0;">Details:</p>
        <ul style="font-size:16px; line-height:1.5; padding-left:20px; margin:0;">
            <li><strong>Doctor:</strong> {{ $scheduleRequest->schedule->doctor->name }}</li>
            <li><strong>Date:</strong> {{ $scheduleRequest->schedule->date->format('F j, Y') }}</li>
            <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($scheduleRequest->schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($scheduleRequest->schedule->end_time)->format('g:i A') }}</li>
            <li><strong>Status:</strong> {{ ucfirst($scheduleRequest->status) }}</li>
        </ul>

        @if($scheduleRequest->status === 'declined')
            <p style="font-size:16px; line-height:1.5; margin-top:15px;">
                The doctor was unable to approve your request. Please try another slot.
            </p>
        @else
            <p style="font-size:16px; line-height:1.5; margin-top:15px;">
                Your request has been approved. Please show up on time. Thank you!
            </p>
        @endif
    </div>

    <div style="background-color:#155edb; height:10px;"></div>
</div>