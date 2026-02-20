@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="text-secondary">Announcements</h2>
        <p>Thank you for choosing Outpatient Care Center. Please fill out the detailed inquiry form below. Our dedicated medical team will review your information and reach out shortly to coordinate your consultation.</p>
        <ul>
            <li>Consultation scheduling available Monday–Friday, 6AM–6PM (except holidays).</li>
            <li>No walk-in appointments allowed — registration must be done online.</li>
            <li>Ensure patient details are complete during registration.</li>
            <li>One account per patient only.</li>
            <li>Patients 18 years old and below must have a parent or guardian present.</li>
            <li>System is for non-emergency outpatient consultations only.</li>
            <li>Limit of 500 consultation requests per day.</li>
        </ul>
        <p>Reminder: Please be sure to bring your PhilHealth ID and Member Data Record (MDR) for identification during your visit.</p>
    </div>
    <div class="col-md-6">
        <div class="contact-form card">
            <h4 class="text-secondary mb-3">Register</h4>
            <p class="mb-2">This web portal is for non-emergency outpatient consultation requests only. Please log in below.</p>
            <form method="POST" action="{{ route('register.store') }}" id="submit-disable">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control shadow-none" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control shadow-none" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control shadow-none" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control shadow-none" required>
                </div>
                <button class="bg-primary text-white button secondary-hover" type="submit" id="submit-btn">Create account <i class="fa-solid fa-arrow-right"></i></button>
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
</script>
@endsection

