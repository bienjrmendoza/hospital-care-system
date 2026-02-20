@extends('layouts.app')

@section('content')
<h3 class="text-secondary mb-4">Admin Dashboard</h3>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><h5 class="h6 text-secondary"><i class="fa-solid fa-user-tie"></i> Admins</h5><h2 class="mb-0 text-secondary">{{ $totals['admins'] }}</h2></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><h5 class="h6 text-secondary"><i class="fa-solid fa-user-doctor"></i> Doctors</h5><h2 class="mb-0 text-secondary">{{ $totals['doctors'] }}</h2></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><h5 class="h6 text-secondary"><i class="fa-solid fa-users"></i> Users</h5><h2 class="mb-0 text-secondary">{{ $totals['users'] }}</h2></div></div></div>
</div>
<div class="admin-btn d-flex gap-3 flex-column flex-lg-row">
    <a class="bg-primary text-white secondary-hover w-100 text-center" href="{{ route('admin.admins.index') }}">Manage Admins <i class="fa-solid fa-user-tie text"></i></a>
    <a class="bg-primary text-white secondary-hover w-100 text-center" href="{{ route('admin.invites.index') }}">Invite Doctors <i class="fa-solid fa-user-doctor"></i></a>
    <a class="bg-primary text-white secondary-hover w-100 text-center" href="{{ route('admin.specializations.index') }}">Manage Specializations <i class="fa-solid fa-user-tag"></i></a>
    <a class="bg-primary text-white secondary-hover w-100 text-center" href="{{ route('admin.schedules.index') }}">Manage Schedules <i class="fa-solid fa-business-time"></i></a>
</div>
@endsection

