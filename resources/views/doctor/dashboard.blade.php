@extends('layouts.app')

@section('content')
<h3 class="text-secondary mb-4">Admin Dashboard</h3>
<div class="row g-3 admin-dashboard">
    <div class="col-md-4">
        <a class="card text-decoration-none shadow-sm card-primary-hover" href="{{ route('doctor.schedules.index') }}">
            <div class="card-body">
                <h5 class="text-secondary"><i class="fa-solid fa-business-time"></i> Weekly Schedule Manager</h5>
                <p class="mb-0">Add, edit, and delete your own availability slots.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="card text-decoration-none shadow-sm card-primary-hover" href="{{ route('doctor.requests.index') }}">
            <div class="card-body">
                <h5 class="text-secondary"><i class="fa-solid fa-bell"></i> Requests Inbox</h5>
                <p class="mb-0">Accept or decline pending user requests.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="card text-decoration-none shadow-sm card-primary-hover" href="{{ route('doctor.vitals.index') }}">
            <div class="card-body">
                <h5 class="text-secondary"><i class="fa-solid fa-file-export"></i> Vital Signs Report</h5>
                <p class="mb-0">Review patient requests and generate detailed vital signs reports in PDF format.</p>
            </div>
        </a>
    </div>
</div>
@endsection

