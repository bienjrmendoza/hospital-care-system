@extends('layouts.app')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <label class="form-label">Specialization</label>
        <select id="specializationFilter" class="form-select shadow-none">
            <option value="">All specializations</option>
            @foreach($specializations as $specialization)
                <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-4">
        <label class="form-label">Doctor</label>
        <select id="doctorFilter" class="form-select shadow-none">
            <option value="">All doctors</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-4">
        <label class="form-label">Date</label>
        <input id="startFilter" type="date" class="form-control shadow-none" value="{{ $start->toDateString() }}">
    </div>
</div>

<div id="doctorAvailabilityGrid">
    @include('partials.doctor-availability-cards', ['availableDoctors' => $availableDoctors, 'date' => $start])
</div>
@endsection

@push('scripts')
<script>
$(function () {
    function loadDoctors() {
        $.get('{{ route('schedules.feed') }}', {
            doctor_id: $('#doctorFilter').val(),
            specialization_id: $('#specializationFilter').val(),
            start: $('#startFilter').val()
        }).done(function (res) {
            $('#doctorAvailabilityGrid').html(res.html);
        }).fail(function () {
            window.showToast('danger', 'Failed to load doctors.');
        });
    }

    $('#specializationFilter, #doctorFilter, #startFilter').on('change', loadDoctors);
});
</script>
@endpush
