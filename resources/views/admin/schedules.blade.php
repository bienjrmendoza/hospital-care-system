@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
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
            <div class="col-md-2"><button class="bg-primary text-white secondary-hover w-100" type="submit"><i class="fa-solid fa-filter"></i> Filter</button></div>
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

<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editScheduleForm">
                <div class="modal-header">
                    <h5 class="modal-title text-secondary">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editScheduleId">

                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" id="editDate" class="form-control shadow-none" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" id="editStart" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time</label>
                            <input type="time" id="editEnd" class="form-control shadow-none" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="editStatus" class="form-select shadow-none" required>
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn bg-primary text-white secondary-hover">Update Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5 text-secondary">Delete Schedule</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete <strong id="deleteScheduleDate"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteScheduleBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {

    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let deleteId = null;

    const editModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteScheduleModal'));

    function reloadSchedulesView() {
        $.get(window.location.href).done(function (html) {
            $('#adminSchedulesTableWrap').html(
                $(html).find('#adminSchedulesTableWrap').html()
            );

            $('#adminSchedulesPaginationWrap').html(
                $(html).find('#adminSchedulesPaginationWrap').html()
            );
        }).fail(function () {
            window.showToast('danger', 'Failed to refresh schedules table.');
        });
    }

    $(document).on('click', '.admin-edit', function () {

        $('#editScheduleId').val($(this).data('id'));
        $('#editDate').val($(this).data('date'));
        $('#editStart').val($(this).data('start'));
        $('#editEnd').val($(this).data('end'));
        $('#editStatus').val($(this).data('status'));

        editModal.show();
    });

    $('#editScheduleForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#editScheduleId').val();
        const date = $('#editDate').val();
        const start = $('#editStart').val();
        const end = $('#editEnd').val();
        const status = $('#editStatus').val();

        if (start >= end) {
            window.showToast('danger', 'End time must be after start time.');
            return;
        }

        $.ajax({
            url: `/admin/schedules/${id}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: {
                _method: 'PUT',
                date: date,
                start_time: start,
                end_time: end,
                status: status
            }
        }).done(function (res) {
            editModal.hide();
            window.showToast('success', res.message || 'Schedule updated successfully.');
            reloadSchedulesView();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Update failed.');
        });
    });

    $(document).on('click', '.admin-delete', function () {
        deleteId = $(this).data('id');
        $('#deleteScheduleDate').text($(this).closest('tr').find('td:nth-child(2)').text());
        deleteModal.show();
    });

    $(document).on('click', '#confirmDeleteScheduleBtn', function () {

        if (!deleteId) return;

        $.ajax({
            url: "{{ route('admin.schedules.destroy', ':id') }}".replace(':id', deleteId),
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'DELETE' }
        }).done(function (res) {
            deleteModal.hide();
            window.showToast('success', res.message || 'Schedule deleted successfully.');
            reloadSchedulesView();
        }).fail(function () {
            window.showToast('danger', 'Delete failed.');
        });
    });
});
</script>
@endpush

