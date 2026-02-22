@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<div class="d-flex justify-content-between align-items-center ">
    <h3 class="text-secondary mb-3">Doctor Schedule Manager</h3>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h6">Add schedule slots</h2>
        <form id="addScheduleForm">
            <div class="row g-2 mb-2">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control shadow-none" name="date" required>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Time slots</label>
                    <div id="slotRows" class="vstack gap-2">
                        <div class="row g-2 slot-row">
                            <div class="col-md-5"><input type="time" class="form-control shadow-none" name="slots[0][start_time]" required></div>
                            <div class="col-md-5"><input type="time" class="form-control shadow-none" name="slots[0][end_time]" required></div>
                            <div class="col-md-2"><button class="btn btn-outline-danger w-100 remove-slot" type="button"><i class="fa-solid fa-trash"></i> Remove</button></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button id="addSlotRowBtn" class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-plus"></i> Add slot </button>
                <button class="btn bg-primary text-white secondary-hover" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save slots</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm" id="doctorSchedulesTableWrap">
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
                    <td>{{ $schedule->date->format('F j, Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('g:i A') }}</td>
                    <td><span class="badge text-bg-secondary">{{ ucfirst($schedule->status) }}</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary edit-schedule"
                                data-id="{{ $schedule->id }}"
                                data-date="{{ $schedule->date->toDateString() }}"
                                data-start="{{ substr($schedule->start_time, 0, 5) }}"
                                data-end="{{ substr($schedule->end_time, 0, 5) }}"
                                data-status="{{ $schedule->status }}">Edit</button>
                        <button class="btn btn-sm btn-outline-danger delete-schedule"
                                data-id="{{ $schedule->id }}"
                                data-summary="{{ $schedule->date->format('F j, Y') }} | {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('g:i A') }}"
                        >Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-secondary">No schedules yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editScheduleForm">
                <div class="modal-header">
                    <h5 class="modal-title text-secondary">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <h5 class="modal-title text-secondary">Delete Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete <strong id="deleteScheduleSummary"></strong>?</p>
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
    let slotIndex = 1;
    let deleteScheduleId = null;
    const editModalEl = document.getElementById('editScheduleModal');
    const deleteModalEl = document.getElementById('deleteScheduleModal');
    const editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;
    const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

    function reloadSchedulesTable() {
        $.get(window.location.href).done(function (html) {
            const updated = $(html).find('#doctorSchedulesTableWrap').html();
            if (updated) {
                $('#doctorSchedulesTableWrap').html(updated);
            }
        }).fail(function () {
            window.showToast('danger', 'Failed to refresh schedules table.');
        });
    }

    $('#addSlotRowBtn').on('click', function () {
        $('#slotRows').append(`
            <div class="row g-2 slot-row">
                <div class="col-md-5"><input type="time" class="form-control shadow-none" name="slots[${slotIndex}][start_time]" required></div>
                <div class="col-md-5"><input type="time" class="form-control shadow-none" name="slots[${slotIndex}][end_time]" required></div>
                <div class="col-md-2"><button class="btn btn-outline-danger w-100 remove-slot" type="button">Remove</button></div>
            </div>
        `);
        slotIndex++;
    });

    $(document).on('click', '.remove-slot', function () {
        if ($('.slot-row').length > 1) {
            $(this).closest('.slot-row').remove();
        }
    });

    $('#addScheduleForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('doctor.schedules.store') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: $(this).serialize()
        }).done(function (res) {
            window.showToast('success', res.message);
            reloadSchedulesTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Failed to save schedules.');
        });
    });

    $(document).on('click', '.edit-schedule', function () {
        $('#editScheduleId').val($(this).data('id'));
        $('#editDate').val($(this).data('date'));
        $('#editStart').val($(this).data('start'));
        $('#editEnd').val($(this).data('end'));
        $('#editStatus').val($(this).data('status'));
        editModal?.show();
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
            url: `/doctor/schedules/${id}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'PUT', date: date, start_time: start, end_time: end, status: status }
        }).done(function (res) {
            window.showToast('success', res.message);
            editModal?.hide();
            reloadSchedulesTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Update failed.');
        });
    });

    $(document).on('click', '.delete-schedule', function () {
        deleteScheduleId = $(this).data('id');
        $('#deleteScheduleSummary').text($(this).data('summary'));
        deleteModal?.show();
    });

    $('#confirmDeleteScheduleBtn').on('click', function () {
        if (!deleteScheduleId) return;

        $.ajax({
            url: `/doctor/schedules/${deleteScheduleId}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'DELETE' }
        }).done(function (res) {
            window.showToast('success', res.message);
            deleteModal?.hide();
            deleteScheduleId = null;
            reloadSchedulesTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Delete failed.');
        });
    });
});
</script>
@endpush
