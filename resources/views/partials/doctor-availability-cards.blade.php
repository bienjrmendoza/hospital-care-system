<div class="row g-3">
    @forelse($availableDoctors as $doctor)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="mb-1 text-secondary">{{ $doctor->name }}</h5>
                    <p class="text-muted mb-2">
                        {{ $doctor->doctorProfile?->specializationRef?->name ?? 'General' }}
                    </p>
                    <p class="mb-3">
                        <span class="badge text-bg-light border">{{ $doctor->schedules->count() }} slot(s) available</span>
                    </p>

                    <div class="mt-auto">
                        <a
                            href="{{ route('public.doctor.schedules', ['doctor' => $doctor->id, 'date' => $date->toDateString()]) }}"
                            class="btn btn-primary w-100"
                        >
                            View <i class="fa-solid fa-right-to-bracket ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center text-secondary py-4">
                    No doctors have available slots for the selected date.
                </div>
            </div>
        </div>
    @endforelse
</div>
