@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="text-secondary">Announcements</h2>
        <p>Thank you for choosing TABH Care. Please fill out the detailed inquiry form below. Our dedicated medical team will review your information and reach out shortly to coordinate your consultation.</p>
        <ul>
            <li>Consultation scheduling available Monday–Friday, 6AM–6PM (except holidays).</li>
            <li>No walk-in appointments allowed — registration must be done online.</li>
            <li>Ensure patient details are complete during registration.</li>
            <li>One account per patient only.</li>
            <li>Patients 18 years old and below must have a parent or guardian present.</li>
            <li>System is for non-emergency outpatient consultations only.</li>
            <li>Limit of 500 consultation requests per day.</li>
        </ul>
        <p>Reminder: Kindly make sure to bring your PhilHealth ID and Member Data Record (MDR) for identification during your visit.</p>
    </div>
    <div class="col-md-6">
        <div class="contact-form card">
            <h4 class="text-secondary mb-3">Register</h4>
            <p class="mb-2">This web portal is for non-emergency outpatient consultation requests only. Please log in below.</p>
            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" id="submit-disable">
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
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control shadow-none" value="{{ old('phone') }}" pattern="^(09|\+639)\d{9}$" required>
                </div>
                <div class="form-group f-group">
                    <div class="mb-3 position-relative">
                        <label class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control shadow-none" required>
                        <span onclick="togglePassword('password', 'toggleIcon1')" ><i class="fa-solid fa-eye toggle-password text-secondary" id="toggleIcon1"></i></span>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control shadow-none" required>
                        <span onclick="togglePassword('password_confirmation', 'toggleIcon2')" ><i class="fa-solid fa-eye toggle-password text-secondary" id="toggleIcon2"></i></span>
                    </div>
                </div>
                <div class="form-group f-group">
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control shadow-none" accept="image/*">
                        <small class="text-muted" style="font-size:10px;">Optional. JPG, PNG, AVIF, WEBP JPEG max 2MB.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthday" class="form-control shadow-none" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Chief Complaint</label>
                    <!-- <input type="text" name="chief_complaint" class="form-control shadow-none" placeholder="Short description"> -->
                     <textarea name="chief_complaint" placeholder="Short description of patient complaint" rows="3" class="form-control shadow-none" required></textarea>
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

