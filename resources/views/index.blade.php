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
                            <h1 class="text-white">We Provide Best Medical Service</h1>
                            <p class="text-white description">Dedicated to excellence in healthcare. To all beneficiaries of our hospital care, we bring the future of medicine to your doorstep.</p>
                            <button class="bg-primary text-white button secondary-hover"><a href="/">Free Consultation ➝</a></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-description text-center">
                                <div class="card bg-primary secondary-hover">
                                    <h3 class="text-white">Appointment Booking</h3>
                                    <p class="text-white">Consult our specialists easily with our advanced online booking system.</p>
                                </div>
                                <div class="card bg-primary secondary-hover">
                                    <h3 class="text-white">Doctor's Schedule</h3>
                                    <p class="text-white">View real-time availability and find the right time for your consultation.</p>
                                </div>
                                <div class="card bg-primary secondary-hover">
                                    <h3 class="text-white">Emergency Cases</h3>
                                    <p class="text-white">Call: <a href="tel:09914946036">09914946036</a>. Dedicated 24/7 emergency support available.</p>
                                </div>
                                <div class="card bg-primary secondary-hover">
                                    <h3 class="text-white">Opening Hours</h3>
                                    <p class="text-white">Monday - Saturday: 7am - 5pm - Sunday: 8pm - 4pm</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="consult-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <img src="{{ asset('assets/images/second-image.png') }}" />
                        </div>
                        <div class="col-lg-6">
                            <h2 class="text-secondary">Consult A Doctor Anywhere, Anytime with TABHC</h2>
                            <p>Schedule and request free consultations easily at  TBAHC with this online website - your convenient online appointment system.</p>
                            <button class="bg-primary text-white button secondary-hover"><a href="/">Free Consultation ➝</a></button>
                        </div>
                    </div>
                </div>
            </section>
            <section class="specialized-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="specialized-description text-center">
                                <h2 class="text-secondary">Comprehensive Specialized Medical Care</h2>
                                <p class="description">We utilize the latest healthcare technologies and professional expertise to ensure the highest standards of medical service for our patients.</p>
                                <button class="bg-primary text-white button secondary-hover"><a href="/">Book Appointment Now ➝</a></button>
                            </div>
                            <div class="specialized-card">
                                <div class="card">
                                    <i class="fa-solid fa-heart-pulse text-primary"></i>
                                    <h3 class="text-secondary">Cardiology Center</h3>
                                    <p>Advanced heart diagnostics and specialized treatments delivered by leading cardiologists in a high-tech hospital setup.</p>
                                    <button class="bg-primary text-white button secondary-hover"><a href="/about">Learn More ➝</a></button>
                                </div>
                                <div class="card">
                                    <i class="fa-solid fa-stethoscope text-primary"></i>
                                    <h3 class="text-secondary">Pediatric Services</h3>
                                    <p>Comprehensive healthcare solutions for infants and children, managed by specialized pediatricians in a friendly clinic environment.</p>
                                    <button class="bg-primary text-white button secondary-hover"><a href="/about">Learn More ➝</a></button>
                                </div>
                                <div class="card">
                                    <i class="fa-solid fa-dna text-primary"></i>
                                    <h3 class="text-secondary">Diagnostic Lab</h3>
                                    <p>High-precision laboratory testing and imaging services using the latest medical equipment for accurate clinical insights.</p>
                                    <button class="bg-primary text-white button secondary-hover"><a href="/about">Learn More ➝</a></button>
                                </div>
                                <div class="card">
                                    <i class="fa-solid fa-truck-medical text-primary"></i>
                                    <h3 class="text-secondary">Emergency Unit</h3>
                                    <p>Professional 24/7 critical care and immediate response services for all medical emergencies and urgent healthcare needs.</p>
                                    <button class="bg-primary text-white button secondary-hover"><a href="/about">Learn More ➝</a></button>
                                </div>
                                <div class="card">
                                    <i class="fa-solid fa-brain text-primary"></i>
                                    <h3 class="text-secondary">Neurology Care</h3>
                                    <p>Expert treatment for nervous system disorders, coordinating care with a team of professional brain health specialists.</p>
                                    <button class="bg-primary text-white button secondary-hover"><a href="/about">Learn More ➝</a></button>
                                </div>
                                <div class="card">
                                    <i class="fa-solid fa-mask-face text-primary"></i>
                                    <h3 class="text-secondary">Surgical Services</h3>
                                    <p>Leading surgical procedures and post-operative care performed by highly skilled surgeons in a modular, sterile facility.</p>
                                    <button class="bg-primary text-white button secondary-hover"><a href="/about">Learn More ➝</a></button>
                                </div>
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
