<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To All Beneficiaries Of Hospital Care</title>
    <!-- <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
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
                                <button class="bg-primary text-white button secondary-hover"><a href="/">Free Consultation <i class="fa-solid fa-arrow-right"></i></a></button>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="specialists">
                    <div class="container">
                        <h2 class="text-secondary">Our Medical Specialists</h2>
                        <p>Meet our team of world-class healthcare professionals dedicated to providing the best medical service for your well-being.</p>
                        @if($doctors->count())
                            <div class="swiper doctorSwiper">
                                <div class="swiper-wrapper">
                                    @foreach($doctors as $doctor)
                                        <div class="swiper-slide">
                                            <div class="card text-center p-4">
                                                <div class="profile mb-3">
                                                    <i class="fa-solid fa-user-doctor text-primary fa-3x"></i>
                                                </div>
                                                <h4 class="text-secondary">
                                                    {{ $doctor->name }}
                                                </h4>
                                                <p class="mb-0">
                                                    {{ $doctor->doctorProfile?->specializationRef?->name ?? 'General' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                No available specialist.
                            </div>
                        @endif
                    </div>
                </section>
                <section class="inpatient" id="inpatient">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5">
                                <h2 class="text-secondary">Inpatient & Consultation Inquiry</h2>
                                <p>Thank you for choosing Outpatient Care Center. Please fill out the detailed inquiry form below. Our dedicated medical team will review your information and reach out shortly to coordinate your consultation.</p>
                                <div class="contact">
                                    <h5 class="text-secondary">Appointment Hotline</h5>
                                    <p><i class="fa-solid fa-phone text-secondary"></i> Emergency: <a href="tel:09914946036">09914946036</a></p>
                                    <h5 class="text-secondary">Medical Records Inquiry</h5>
                                    <p><i class="fa-solid fa-envelope text-secondary"></i><a href="mailto:care@outpatientcare.com"> care@outpatientcare.com</a></p>
                                    <h5 class="text-secondary">Outpatient Clinic Address</h5>
                                    <p><i class="fa-solid fa-location-dot text-secondary"></i> Maharlika Highway, Brgy. Ibabang Dupay, Lucena City, Philippines, 4301</p>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="contact-form card">
                                    @if(session('success'))
                                        <div class="thank-you-box text-center">
                                            <i class="fa-regular fa-circle-check text-secondary fs-40 mt-2"></i>
                                            <h3 class="mt-3 text-secondary">Thank You!</h3>
                                            <p class="mt-3">Your inquiry has been successfully submitted. Our medical team will contact you shortly.</p>
                                            <button class="bg-primary text-white button secondary-hover" type="submit">
                                                <a href="#inpatient" onclick="window.location.reload();">
                                                    Submit Another Inquiry <i class="fa-solid fa-arrow-right"></i>
                                                </a>
                                            </button>
                                        </div>
                                    @else
                                        <form action="{{ route('contact.send') }}" method="POST" id="submit-disable">
                                            @csrf
                                            <div class="form-group f-group">
                                                <div class="inner-group">
                                                    <label>First Name</label>
                                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required>
                                                </div>
                                                <div class="inner-group">
                                                    <label>Last Name</label>
                                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group f-group">
                                                <div class="inner-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email" value="{{ old('email') }}" required>
                                                </div>
                                                <div class="inner-group">
                                                    <label>Phone</label>
                                                    <input type="text" name="phone" value="{{ old('phone') }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="address" value="{{ old('address') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Message</label>
                                                <textarea name="message" rows="5" required>{{ old('message') }}</textarea>
                                            </div>
                                            <button class="bg-primary text-white button secondary-hover" type="submit" id="submit-btn">Submit <i class="fa-solid fa-arrow-right"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @include('include.footer')
        </main>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    const swiper = new Swiper(".doctorSwiper", {
        loop: true,
        slidesPerView: 'auto',
        spaceBetween: 30,
        speed: 8000,
        allowTouchMove: true,
        grabCursor: true,

        autoplay: {
            delay: 0,
            disableOnInteraction: false,
        },

        breakpoints: {
            0: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 }
        }
    });
    
    document.getElementById('submit-disable').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = 'SUBMITTING... <span class="spinner-border spinner-border-sm"></span>';
    });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
