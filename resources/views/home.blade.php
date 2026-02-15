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
        <input id="specializationFilter" class="form-control shadow-none" placeholder="e.g. Cardiology">
    </div>
    <div class="col-lg-2">
        <label class="form-label">Start date</label>
        <input id="startFilter" type="date" class="form-control shadow-none" value="{{ $start->toDateString() }}">
    </div>
    <div class="col-lg-2">
        <label class="form-label">End date</label>
        <input id="endFilter" type="date" class="form-control shadow-none" value="{{ $end?->toDateString() }}">
    </div>
    <div class="col-lg-2 d-flex align-items-end">
        <button id="filterBtn" class="btn btn-dark w-100">Apply</button>
    </div>
</div>

<div id="ajaxAlert" class="alert d-none" role="alert"></div>

<div id="scheduleGrid">
    @include('partials.schedule-grid', ['schedules' => $schedules])
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function showAlert(type, message) {
        $('#ajaxAlert').removeClass('d-none alert-success alert-danger').addClass('alert-' + type).text(message);
    }

    function loadSchedules() {
        $.get('{{ route('schedules.feed') }}', {
            doctor_id: $('#doctorFilter').val(),
            specialization: $('#specializationFilter').val(),
            start: $('#startFilter').val(),
            end: $('#endFilter').val()
        }).done(function (res) {
            $('#scheduleGrid').html(res.html);
        }).fail(function () {
            showAlert('danger', 'Failed to load schedules.');
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
            showAlert('success', res.message);
            loadSchedules();
        }).fail(function (xhr) {
            const message = xhr.responseJSON?.message || 'Request failed.';
            showAlert('danger', message);
        });
    });
});
</script>
@endpush
