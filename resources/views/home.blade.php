@extends('layouts.app')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-lg-3">
        <label class="form-label">Doctor</label>
        <select id="doctorFilter" class="form-select shadow-none">
            <option value="">All doctors</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-3">
        <label class="form-label">Specialization</label>
        <select id="specializationFilter" class="form-select shadow-none">
            <option value="">All specializations</option>
            @foreach($specializations as $specialization)
                <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2">
        <label class="form-label">Start date</label>
        <input id="startFilter" type="date" class="form-control shadow-none" value="{{ $start->toDateString() }}">
    </div>
    <div class="col-lg-2">
        <label class="form-label">End date</label>
        <input id="endFilter" type="date" class="form-control shadow-none" value="{{ $end?->toDateString() }}">
    </div>
    <div class="col-lg-2 d-flex align-items-end admin-btn">
        <button id="filterBtn" class="bg-primary text-white secondary-hover w-100"><i class="fa-solid fa-floppy-disk"></i> Apply</button>
    </div>
</div>

<div id="scheduleGrid">
    @include('partials.schedule-grid', ['schedules' => $schedules])
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function loadSchedules() {
        $.get('{{ route('schedules.feed') }}', {
            doctor_id: $('#doctorFilter').val(),
            specialization_id: $('#specializationFilter').val(),
            start: $('#startFilter').val(),
            end: $('#endFilter').val()
        }).done(function (res) {
            $('#scheduleGrid').html(res.html);
        }).fail(function () {
            window.showToast('danger', 'Failed to load schedules.');
        });
    }

    $('#filterBtn').on('click', loadSchedules);

    $(document).on('click', '.request-slot-btn', function () {
        const scheduleId = $(this).data('id');
        $.ajax({
            url: '{{ route('schedule-requests.store') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { schedule_id: scheduleId }
        }).done(function (res) {
            window.showToast('success', res.message);
            loadSchedules();
        }).fail(function (xhr) {
            const message = xhr.responseJSON?.message || 'Request failed.';
            window.showToast('danger', message);
        });
    });
});
</script>
@endpush
