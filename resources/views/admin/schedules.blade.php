@extends('layouts.app')

@section('content')
<h3 class="text-secondary mb-3">Manage Doctor Schedules</h3>

<div class="card shadow-sm mb-3">
    <div class="card-body admin-btn">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <select class="form-select shadow-none" name="doctor_id">
                    <option value="">All doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(request('doctor_id') == $doctor->id)>{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5"><input type="date" class="form-control shadow-none" name="date" value="{{ request('date') }}"></div>
            <div class="col-md-2"><button class="bg-primary text-white secondary-hover w-100" type="submit">Filter <i class="fa-solid fa-filter"></i></button></div>
        </form>
    </div>
</div>

<div class="card shadow-sm" id="adminSchedulesTableWrap">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->doctor->name }}</td>
                    <td>{{ $schedule->date->format('Y-m-d') }}</td>
                    <td>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                    <td>{{ $schedule->status }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary admin-edit"
                                data-id="{{ $schedule->id }}"
                                data-date="{{ $schedule->date->toDateString() }}"
                                data-start="{{ substr($schedule->start_time, 0, 5) }}"
                                data-end="{{ substr($schedule->end_time, 0, 5) }}"
                                data-status="{{ $schedule->status }}">Edit</button>
                        <button class="btn btn-sm btn-outline-danger admin-delete" data-id="{{ $schedule->id }}">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4 text-secondary">No schedules found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3" id="adminSchedulesPaginationWrap">{{ $schedules->links() }}</div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function reloadSchedulesView() {
        $.get(window.location.href).done(function (html) {
            const updatedTable = $(html).find('#adminSchedulesTableWrap').html();
            const updatedPagination = $(html).find('#adminSchedulesPaginationWrap').html();

            if (updatedTable) {
                $('#adminSchedulesTableWrap').html(updatedTable);
            }
            if (updatedPagination !== undefined) {
                $('#adminSchedulesPaginationWrap').html(updatedPagination);
            }
        }).fail(function () {
            window.showToast('danger', 'Failed to refresh schedules table.');
        });
    }

    $(document).on('click', '.admin-edit', function () {
        const id = $(this).data('id');
        const date = prompt('Date (YYYY-MM-DD)', $(this).data('date'));
        const start = prompt('Start (HH:MM)', $(this).data('start'));
        const end = prompt('End (HH:MM)', $(this).data('end'));
        const status = prompt('Status (available/booked)', $(this).data('status'));

        if (!date || !start || !end || !status) return;

        $.ajax({
            url: `/admin/schedules/${id}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'PUT', date: date, start_time: start, end_time: end, status: status }
        }).done(function (res) {
            window.showToast('success', res.message);
            reloadSchedulesView();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Update failed.');
        });
    });

    $(document).on('click', '.admin-delete', function () {
        if (!confirm('Delete this schedule?')) return;
        const id = $(this).data('id');

        $.ajax({
            url: `/admin/schedules/${id}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'DELETE' }
        }).done(function (res) {
            window.showToast('success', res.message);
            reloadSchedulesView();
        }).fail(function () {
            window.showToast('danger', 'Delete failed.');
        });
    });
});
</script>
@endpush

