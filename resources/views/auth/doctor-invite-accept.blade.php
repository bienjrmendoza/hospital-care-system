@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-2">Doctor Invitation</h1>
                <p class="text-secondary">Invitation for <strong>{{ $invite->email }}</strong>. Set your account password to continue.</p>
                <form method="POST" action="{{ route('doctor.invites.complete', $invite->token) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control shadow-none" value="{{ old('name', $invite->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control shadow-none" required>
                    </div>
                    <button class="btn btn-success" type="submit">Create doctor account</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

