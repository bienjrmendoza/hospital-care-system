@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<h3 class="text-secondary mb-3">Manage Admins</h3>

<div class="card shadow-sm mb-4">
    <div class="card-body admin-btn">
        <form method="POST" action="{{ route('admin.admins.store') }}" class="row g-2">
            @csrf
            <div class="col-md-3"><input class="form-control shadow-none" name="name" placeholder="Name" required></div>
            <div class="col-md-3"><input class="form-control shadow-none" type="email" name="email" placeholder="Email" required></div>
            <div class="col-md-2"><input class="form-control shadow-none" type="password" name="password" placeholder="Password" required></div>
            <div class="col-md-2"><input class="form-control shadow-none" type="password" name="password_confirmation" placeholder="Confirm" required></div>
            <div class="col-md-2"><button class="bg-primary text-white secondary-hover w-100" type="submit"><i class="fa-solid fa-plus"></i> Create Admin</button></div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Created</th></tr></thead>
            <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->created_at->format('F j, Y g:i A') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

