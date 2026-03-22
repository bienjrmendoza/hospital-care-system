<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vital Signs Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
        }

        h3 {
            text-align: center;
        }

        th {
            text-align: left;
        }

        th, td, .notes {
            font-size: 14px;
        }

        .header {
            text-align: center;
        }

        .header img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 0;
            font-size: 14px;
            color: #5c5c5c;
        }

        .section h3 {
            background-color: #f2f2f2;
            padding: 5px 10px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table td, table th {
            border: 1px solid #ccc;
            padding: 8px;
        }

        table th {
            background-color: #e9e9e9;
        }

        .notes {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: auto;
        }

        .profile-initials {
            display: block;
            width: 150px;
            height: 150px;
            background-color: #5c5c5c;
            color: #fff;
            font-weight: bold;
            font-size: 32px;
            text-align: center;
            line-height: 80px;
            text-transform: uppercase;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    @php
        $user = $vital->user;
        $parts = preg_split('/\s+/', trim($user->name)) ?: [];
        $initials = collect($parts)->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
    @endphp

    <div class="header">
        @if($user->profile_image)
            <img src="{{ asset('profile_images/' . basename($user->profile_image)) }}" 
                 alt="{{ $initials ?: 'U' }}">
        @else
            <span class="profile-initials">{{ $initials ?: 'U' }}</span>
        @endif
        <h2>{{ $user->name }}</h2>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Birthday:</strong> {{ \Carbon\Carbon::parse($user->birthday)->format('F j, Y') }}</p>
    </div>

    <div class="section">
        <h3>Chief Complaint</h3>
        <div class="notes">
            {!! nl2br(e($user->chief_complaint ?? 'No complaint provided.')) !!}
        </div>
    </div>

    <div class="section">
        <h3>Initial Assessment</h3>
        <div class="notes">
            {!! nl2br(e($vital->initial_assessment ?? 'No assessment provided.')) !!}
        </div>
    </div>

    <div class="section">
        <h3>History of Patient</h3>
        <div class="notes">
            {!! nl2br(e($vital->notes ?? 'No history provided.')) !!}
        </div>
    </div>

    <div class="section">
        <h3>Vitals Report</h3>
        <table>
            <tr><th>Blood Pressure</th><td>{{ $vital->blood_pressure ?? '-' }}</td></tr>
            <tr><th>Heart Rate</th><td>{{ $vital->heart_rate ?? '-' }}</td></tr>
            <tr><th>Temperature</th><td>{{ $vital->temperature ?? '-' }}</td></tr>
            <tr><th>Respiratory Rate</th><td>{{ $vital->respiratory_rate ?? '-' }}</td></tr>
            <tr><th>Oxygen Saturation</th><td>{{ $vital->oxygen_saturation ?? '-' }}</td></tr>
            <tr><th>Weight</th><td>{{ $vital->weight ?? '-' }}</td></tr>
            <tr><th>Height</th><td>{{ $vital->height ?? '-' }}</td></tr>
            <tr><th>BMI</th><td>{{ $vital->bmi ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="section" style="text-align:center; margin-top:30px; font-size:12px; color:#999;">
        Generated on {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}
        <p>To All Beneficiaries of Hospital Care</p>
    </div>
</body>
</html>