<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To All Beneficiaries Of Hospital Care</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/home/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    @endpush

    @stack('styles')
</head>
    <body>
        <main>
            @include('include.header')      
                <section class="hero">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <p class="text-tertiary mb-0 description">The Health Care Philosophy</p>
                                <h1 class="text-white">We Provide Best Medical Service</h1>
                                <p class="text-white description">At outpatient care center, our mission is to provide comprehensive and compassionate medical support. We combine advanced digital health technology with professional excellence to ensure every outpatient consultation is delivered with the highest standards of trust, quality, and care.</p>
                                <button class="bg-primary text-white button secondary-hover"><a href="/">Free Consultation ➝</a></button>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="specialists">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Our Medical Specialists</h2>
                                <p class="fs-25">Meet our team of world-class healthcare professionals dedicated to providing the best medical service for your well-being.</p>
                                <div class="row">
                                    @foreach($doctors as $doctor)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="profile">
                                                    <i class="fa-solid fa-user-doctor text-primary"></i>
                                                </div>
                                                <h3 class="card-title text-secondary">{{ $doctor->name }}</h3>
                                                    @if($doctor->doctorProfile)
                                                        <p class="card-subtitle text-muted mb-2">
                                                            {{ $doctor->doctorProfile->specialization ?? 'General' }}
                                                        </p>
                                                    @else
                                                        <p class="card-subtitle text-muted mb-2">General</p>
                                                    @endif
                                                <p class="mb-0"><strong>Schedule:</strong></p>
                                                @if($doctor->schedules->count())
                                                    <ul>
                                                        @foreach($doctor->schedules as $schedule)
                                                            <li>
                                                                {{ \Carbon\Carbon::parse($schedule->date)->format('l, F j') }} 
                                                                from {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} 
                                                                to {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-muted">No schedule available</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @include('include.footer')
        </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
