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
            width: 30%;
        }

        th, td, tr {
            padding: 2px 10px!important;
            margin: 0!important;
        }

        th, td, .notes {
            font-size: 12px;
        }

        .header {
            /* text-align: center; */
        }

        .header img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            display: block;
            background-color: #5c5c5c;
            color: #fff;
            font-weight: bold;
            font-size: 25px;
            text-align: center;
            line-height: 70px;
            text-transform: uppercase;
            padding:22px 19px;
        }

        .header h2 {
            margin: 0;
            font-size: 14px;
        }

        .header p {
            margin: 0;
            font-size: 10px;
            color: #5c5c5c;
        }

        .section h3 {
            background-color: #e9e9e9;
            padding: 3px!important;
            font-size: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
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
            width: 25%;
        }

        .notes {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: auto;
            margin-bottom:0;
        }

        .profile-initials {
            display: block;
            width: 70px;
            height: 70px;
            background-color: #5c5c5c;
            color: #fff;
            font-weight: bold;
            font-size: 25px;
            text-align: center;
            line-height: 55px;
            text-transform: uppercase;
            /* margin: 0 auto; */
        }

        .page-break {
            page-break-after: always;
        }

        .prevent-break {
            page-break-inside: avoid!important;
            page-break-after: avoid!important;
            page-break-before: avoid!important;
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
        <div style="width: 12%; display: inline-block;">
            @if($user->profile_image)
                <!-- <img src="{{ public_path('storage/'.$user->profile_image) }}" alt="Profile"> -->
                <img src="{{ asset('profile_images/' . basename($user->profile_image)) }}" 
                alt="{{ $initials !== '' ? $initials : 'U' }}">
            @else
                <span class="profile-initials">{{ $initials !== '' ? $initials : 'U' }}</span>
            @endif
        </div>
        <div style="width: 85%; display: inline-block; vertical-align: top;">
            <h2>{{ $user->name }}</h2>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Birthday:</strong> {{ \Carbon\Carbon::parse($user->birthday)->format('F j, Y') }}</p>
        </div>
    </div>
    <hr style="color:#fafafa;"/>
    <div class="section">
        <h3>Chief Complaint</h3>
        <div class="notes">
            {!! nl2br(e($user->chief_complaint ?? 'No complaint provided.')) !!}
        </div>
    </div>
    <div class="section">
        <h3>Vitals Report</h3>
        <table style="margin-bottom: 20px;">
            <tr>
                <th>Date</th>
                <td>{{ \Carbon\Carbon::parse($vital->date)->format('F d, Y') }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <th>Temperature (°C)</th>
                <td>{{ $vital->temperature ?? '-' }}</td>

                <th>Heart Rate (bpm)</th>
                <td>{{ $vital->heart_rate ?? '-' }}</td>

                <th>Respiratory Rate</th>
                <td>{{ $vital->respiratory_rate ?? '-' }}</td>
            </tr>

            <tr>
                <th>Blood Pressure</th>
                <td>{{ $vital->blood_pressure ?? '-' }}</td>

                <th>Oxygen Saturation (%)</th>
                <td>{{ $vital->oxygen_saturation ?? '-' }}</td>

                <!-- Empty space to balance -->
                <th style="background-color:#fff; border: none; border-bottom: 1px solid #ccc!important;"></th>
                <td style="background-color:#fff; border: none; border-bottom: 1px solid #ccc!important;"></td>
            </tr>

            <tr>
                <th>Weight (kg)</th>
                <td>{{ $vital->weight ?? '-' }}</td>

                <th>Height (cm)</th>
                <td>{{ $vital->height ?? '-' }}</td>

                <th>BMI</th>
                <td>{{ $vital->bmi ?? '-' }}</td>
            </tr>
        </table>
    </div>
    <!-- <hr style="color:#fafafa;"/> -->
    <div style="width:49.5%; display: inline-block; vertical-align: top;" class="section">
        <h3>Initial Assessment</h3>
        <div class="notes">
            {!! nl2br(e($vital->initial_assessment ?? 'No assessment provided.')) !!}
        </div>
    </div>
    <div style="width:49.8%; display: inline-block; vertical-align: top;" class="section">
        <h3>History of Patient</h3>
        <div class="notes">
            {!! nl2br(e($vital->notes ?? 'No history provided.')) !!}
        </div>
    </div>
    <div style="width:49.5%; display: inline-block; vertical-align: top;" class="section">
        <h3>Laboratory Examination / Diagnostic</h3>
        <div class="notes">
            {!! nl2br(e($vital->diagnostic ?? 'No diagnostic provided.')) !!}
        </div>
    </div>
    <div style="width:49.8%; display: inline-block; vertical-align: top;" class="section">
        <h3>Medication</h3>
        <div class="notes">
            {!! nl2br(e($vital->medication ?? 'No medication provided.')) !!}
        </div>
    </div>
    <div style="width:49.5%; display: inline-block; vertical-align: top;" class="section">
        <h3>Treatment</h3>
        <div class="notes">
            {!! nl2br(e($vital->treatment ?? 'No treatment provided.')) !!}
        </div>
    </div>
    <div style="width:49.8%; display: inline-block; vertical-align: top;" class="section">
        <h3>Diet</h3>
        <div class="notes">
            {!! nl2br(e($vital->diet ?? 'No diet provided.')) !!}
        </div>
    </div>
    <div class="section prevent-break">
        <h3>Recommendation / Remarks</h3>
        <div class="notes">
            {!! nl2br(e($vital->remarks ?? 'No recommendation/remarks provided.')) !!}
        </div>
    </div>
    <div class="section" style="text-align:center; margin-top:10px; font-size:10px; color:#999;">
        Generated on {{ \Carbon\Carbon::parse($vital->date)->format('F d, Y') }}
        <p>To All Beneficiaries of Hospital Care</p>
    </div>
</body>
</html>