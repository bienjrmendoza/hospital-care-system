@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div>
        <h3 class="text-secondary mb-1">{{ $doctor->name }}</h3>
        <p class="text-muted mb-0">{{ $doctor->doctorProfile?->specializationRef?->name ?? 'General' }}</p>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body admin-btn">
        <form method="GET" action="{{ route('public.doctor.schedules', $doctor->id) }}" class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" class="form-control shadow-none" name="date" value="{{ $date->toDateString() }}" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="bg-primary text-white secondary-hover w-100" type="submit"><i class="fa-solid fa-filter"></i> Apply</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->date->format('Y-m-d') }}</td>
                        <td>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                        <td><span class="badge text-bg-success">Available</span></td>
                        <td class="text-end">
                            @auth
                                @if(auth()->user()->isUser())
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary request-slot-btn"
                                        data-id="{{ $schedule->id }}"
                                        data-date="{{ $schedule->date->format('Y-m-d') }}"
                                        data-time="{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}"
                                    >
                                        Request
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login to Request</a>
                            @endauth
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">No available slots for this doctor on this date.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="confirmRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-secondary">Confirm Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">You are about to request this schedule:</p>
                <p class="mb-0"><strong id="confirmRequestSummary"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmRequestBtn">Confirm Request</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const confirmModalEl = document.getElementById('confirmRequestModal');

    if (!confirmModalEl) return;

    const confirmModal = new bootstrap.Modal(confirmModalEl);
    let selectedScheduleId = null;

    $(document).on('click', '.request-slot-btn', function () {
        selectedScheduleId = $(this).data('id');
        const date = $(this).data('date');
        const time = $(this).data('time');

        $('#confirmRequestSummary').text(`${date} | ${time}`);
        confirmModal.show();
    });

    $('#confirmRequestBtn').on('click', function () {
        if (!selectedScheduleId) return;

        $.ajax({
            url: '{{ route('schedule-requests.store') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { schedule_id: selectedScheduleId }
        }).done(function (res) {
            confirmModal.hide();
            window.showToast('success', res.message || 'Schedule request submitted.');
            window.location.reload();
        }).fail(function (xhr) {
            confirmModal.hide();
            window.showToast('danger', xhr.responseJSON?.message || 'Request failed.');
        });
    });
});
</script>
@endpush
