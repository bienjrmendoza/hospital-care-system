@extends('layouts.app')

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <a class="card text-decoration-none shadow-sm" href="{{ route('doctor.schedules.index') }}">
            <div class="card-body">
                <h2 class="h5">Weekly Schedule Manager</h2>
                <p class="mb-0 text-secondary">Add, edit, and delete your own availability slots.</p>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a class="card text-decoration-none shadow-sm" href="{{ route('doctor.requests.index') }}">
            <div class="card-body">
                <h2 class="h5">Requests Inbox</h2>
                <p class="mb-0 text-secondary">Accept or decline pending user requests.</p>
            </div>
        </a>
    </div>
</div>
@endsection

