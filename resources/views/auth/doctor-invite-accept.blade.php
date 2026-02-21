@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="contact-form card">
            <h4 class="text-secondary mb-3">Doctor Invitation</h4>
            <p>Invitation for <strong class="text-primary">{{ $invite->email }}</strong>. Set your account password to continue.</p>
            <form method="POST" action="{{ route('doctor.invites.complete', $invite->token) }}" id="submit-disable">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Specialization</label>
                    <input class="form-control shadow-none" value="{{ $invite->specializationRef?->name }}" readonly>
                </div>
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
                <button class="bg-primary text-white button secondary-hover" type="submit" id="submit-btn">Create account  <i class="fa-solid fa-arrow-right"></i></button>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('submit-disable').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = 'CREATING ACCOUNT... <span class="spinner-border spinner-border-sm"></span>';
    });
</script>
@endsection

