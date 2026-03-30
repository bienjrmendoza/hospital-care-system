@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="contact-form card">
            <h4 class="text-secondary mb-3">Doctor Invitation</h4>
            <p>Invitation for <strong class="text-primary">{{ $invite->email }}</strong>. Set your account password to continue.</p>
            <form method="POST" action="{{ route('doctor.invites.complete', $invite->token) }}" id="submit-disable" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Specialization</label>
                    <input class="form-control shadow-none" value="{{ $invite->specializationRef?->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control shadow-none" accept="image/*">
                    <small class="text-muted">Optional. JPG, PNG, AVIF, WEBP JPEG max 2MB.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control shadow-none" value="{{ old('name', $invite->name) }}" required>
                </div>
                <div class="mb-3 position-relative">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" name="password" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$" title="Password must be at least 8 characters and include 1 uppercase letter, 1 number, and 1 special character" class="form-control shadow-none" required>
                    <span onclick="togglePassword('password', 'toggleIcon1')" ><i class="fa-solid fa-eye toggle-password text-secondary" id="toggleIcon1"></i></span>
                    <div id="passwordPopup" class="password-popup">
                        <small id="length" class="text-danger d-block">✖ At least 8 characters</small>
                        <small id="uppercase" class="text-danger d-block">✖ At least 1 uppercase letter</small>
                        <small id="number" class="text-danger d-block">✖ At least 1 number</small>
                        <small id="symbol" class="text-danger d-block">✖ At least 1 special character</small>
                    </div>
                </div>
                <div class="mb-3 position-relative">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control shadow-none" required>
                    <span onclick="togglePassword('password_confirmation', 'toggleIcon2')" ><i class="fa-solid fa-eye toggle-password text-secondary" id="toggleIcon2"></i></span>
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

    const passwordInput = document.getElementById('password');
    const popup = document.getElementById('passwordPopup');

    const lengthCheck = document.getElementById('length');
    const uppercaseCheck = document.getElementById('uppercase');
    const numberCheck = document.getElementById('number');
    const symbolCheck = document.getElementById('symbol');

    passwordInput.addEventListener('focus', () => {
        popup.classList.add('show');
    });

    passwordInput.addEventListener('input', () => {
        popup.classList.add('show');

        const value = passwordInput.value;

        toggleCheck(value.length >= 8, lengthCheck, "At least 8 characters");
        toggleCheck(/[A-Z]/.test(value), uppercaseCheck, "At least 1 uppercase letter");
        toggleCheck(/\d/.test(value), numberCheck, "At least 1 number");
        toggleCheck(/[^A-Za-z0-9]/.test(value), symbolCheck, "At least 1 special character");
    });

    passwordInput.addEventListener('blur', () => {
        setTimeout(() => popup.classList.remove('show'), 150);
    });

    function toggleCheck(condition, element, text) {
        if (condition) {
            element.textContent = "✔ " + text;
            element.classList.replace("text-danger", "text-success");
        } else {
            element.textContent = "✖ " + text;
            element.classList.replace("text-success", "text-danger");
        }
    }
</script>
@endsection

