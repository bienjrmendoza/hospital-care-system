@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<h3 class="text-secondary mb-3">Manage Doctor Schedules</h3>

<div class="card shadow-sm mb-3">
    <div class="card-body admin-btn">
        <form id="adminScheduleFilterForm" class="row g-2">
            <div class="col-md-5">
                <select class="form-select shadow-none" name="doctor_id" id="filterDoctorId">
                    <option value="">All doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5"><input type="date" class="form-control shadow-none" name="date" id="filterDate"></div>
            <div class="col-md-2"><button class="bg-primary text-white secondary-hover w-100" type="submit"><i class="fa-solid fa-filter"></i> Filter</button></div>
        </form>
    </div>
</div>

<div class="card shadow-sm" id="adminSchedulesTableCard">
    <div class="card-body pb-2">
        <div class="table-responsive">
            <table id="adminSchedulesTable" class="table table-striped mb-0 w-100">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
#adminSchedulesTableCard .dataTables_wrapper .row {
    margin-left: 0;
    margin-right: 0;
}

#adminSchedulesTableCard .dataTables_length label,
#adminSchedulesTableCard .dataTables_filter label {
    margin-bottom: 0;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
}

#adminSchedulesTableCard .dataTables_filter {
    text-align: right;
}

#adminSchedulesTableCard .dataTables_filter input,
#adminSchedulesTableCard .dataTables_length select {
    width: auto;
    min-width: 90px;
    box-shadow: none !important;
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    height: 34px;
    border-radius: 0.375rem;
}

#adminSchedulesTableCard .dataTables_filter input:focus,
#adminSchedulesTableCard .dataTables_length select:focus {
    box-shadow: none !important;
}

#adminSchedulesTableCard .dataTables_info,
#adminSchedulesTableCard .dataTables_paginate {
    margin-top: .75rem;
}

@media (max-width: 768px) {
    #adminSchedulesTableCard .dataTables_length,
    #adminSchedulesTableCard .dataTables_filter,
    #adminSchedulesTableCard .dataTables_info,
    #adminSchedulesTableCard .dataTables_paginate {
        text-align: left;
    }
}
</style>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let deleteId = null;

    const editModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteScheduleModal'));

    const table = $('#adminSchedulesTable').DataTable({
        ajax: {
            url: '{{ route('admin.schedules.feed') }}',
            data: function (d) {
                d.doctor_id = $('#filterDoctorId').val();
                d.date = $('#filterDate').val();
            },
            dataSrc: 'data'
        },
        dom: "<'row g-3 align-items-center mb-3'<'col-md-6'l><'col-md-6'f>>" +
             "t" +
             "<'row g-3 align-items-center mt-2'<'col-md-6'i><'col-md-6'p>>",
        order: [],
        columns: [
            { data: 'doctor_name' },
            {
                data: 'date',
                render: function (data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return row.date_sort;
                    }
                    return data;
                }
            },
            { data: 'time' },
            { data: 'status' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: function (_, __, row) {
                    return `
                        <button class="btn btn-sm btn-outline-primary admin-edit"
                                data-id="${row.id}"
                                data-date="${row.date}"
                                data-start="${row.start_time}"
                                data-end="${row.end_time}"
                                data-status="${row.status}">Edit</button>
                        <button class="btn btn-sm btn-outline-danger admin-delete" data-id="${row.id}" data-date="${row.date}">Delete</button>
                    `;
                }
            }
        ]
    });

    $('#adminScheduleFilterForm').on('submit', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });

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
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            const errors = xhr.responseJSON?.errors;

            if (errors) {
                Object.values(errors).flat().forEach(function (message) {
                    window.showToast('danger', message);
                });
                return;
            }

            window.showToast('danger', xhr.responseJSON?.message || 'Update failed.');
        });
    });

    $(document).on('click', '.admin-delete', function () {
        deleteId = $(this).data('id');
        $('#deleteScheduleDate').text($(this).data('date'));
        deleteModal.show();
    });

    $('#confirmDeleteScheduleBtn').on('click', function () {
        if (!deleteId) return;

        $.ajax({
            url: "{{ route('admin.schedules.destroy', ':id') }}".replace(':id', deleteId),
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'DELETE' }
        }).done(function (res) {
            deleteModal.hide();
            window.showToast('success', res.message || 'Schedule deleted successfully.');
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Delete failed.');
        });
    });
});
</script>
@endpush
