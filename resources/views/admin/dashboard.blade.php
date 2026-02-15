@extends('layouts.app')

@section('content')
<h1 class="h4 mb-4">Admin Dashboard</h1>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><h2 class="h6">Admins</h2><p class="display-6 mb-0">{{ $totals['admins'] }}</p></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><h2 class="h6">Doctors</h2><p class="display-6 mb-0">{{ $totals['doctors'] }}</p></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><h2 class="h6">Users</h2><p class="display-6 mb-0">{{ $totals['users'] }}</p></div></div></div>
</div>
<div class="d-flex gap-2">
    <a class="btn btn-dark" href="{{ route('admin.admins.index') }}">Manage Admins</a>
    <a class="btn btn-primary" href="{{ route('admin.invites.index') }}">Invite Doctors</a>
    <a class="btn btn-outline-secondary" href="{{ route('admin.schedules.index') }}">Manage Schedules</a>
</div>
@endsection

