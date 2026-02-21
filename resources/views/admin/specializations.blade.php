@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<div class="d-flex justify-content-between align-items-center mb-3 admin-btn">
    <h3 class="text-secondary mb-0">Specialization Management</h3>
    <button type="button" class="bg-primary text-white secondary-hover px-5" data-bs-toggle="modal" data-bs-target="#addSpecializationModal"><i class="fa-solid fa-plus"></i> Add Specialization</button>
</div>

<div class="card shadow-sm" id="specializationsTableWrap">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Created</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($specializations as $specialization)
                <tr>
                    <td>{{ $specialization->name }}</td>
                    <td>{{ $specialization->created_at->format('F j, Y g:i A') }}</td>
                    <td class="text-end">
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary edit-specialization-btn"
                            data-id="{{ $specialization->id }}"
                            data-name="{{ $specialization->name }}"
                        >
                            Edit
                        </button>
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger delete-specialization-btn"
                            data-id="{{ $specialization->id }}"
                            data-name="{{ $specialization->name }}"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-secondary py-4">No specializations found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addSpecializationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSpecializationForm">
                <div class="modal-header">
                    <h2 class="modal-title fs-5">Add Specialization</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Specialization Name</label>
                    <input type="text" name="name" class="form-control shadow-none" maxlength="255" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn bg-primary text-white secondary-hover">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editSpecializationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSpecializationForm">
                <div class="modal-header">
                    <h2 class="modal-title fs-5 text-secondary">Edit Specialization</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editSpecializationId">
                    <label class="form-label">Specialization Name</label>
                    <input type="text" name="name" id="editSpecializationName" class="form-control shadow-none" maxlength="255" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSpecializationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5 text-secondary">Delete Specialization</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete <strong id="deleteSpecializationName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteSpecializationBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const addModalEl = document.getElementById('addSpecializationModal');
    const editModalEl = document.getElementById('editSpecializationModal');
    const deleteModalEl = document.getElementById('deleteSpecializationModal');

    const addModal = new bootstrap.Modal(addModalEl);
    const editModal = new bootstrap.Modal(editModalEl);
    const deleteModal = new bootstrap.Modal(deleteModalEl);

    let deleteId = null;

    function reloadSpecializationsTable() {
        $.get(window.location.href).done(function (html) {
            const updated = $(html).find('#specializationsTableWrap').html();
            if (updated) {
                $('#specializationsTableWrap').html(updated);
            }
        }).fail(function () {
            window.showToast('danger', 'Failed to refresh specializations table.');
        });
    }

    $('#addSpecializationForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route('admin.specializations.store') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: $(this).serialize()
        }).done(function (res) {
            window.showToast('success', res.message);
            addModal.hide();
            $('#addSpecializationForm')[0].reset();
            reloadSpecializationsTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Create failed.');
        });
    });

    $(document).on('click', '.edit-specialization-btn', function () {
        $('#editSpecializationId').val($(this).data('id'));
        $('#editSpecializationName').val($(this).data('name'));
        editModal.show();
    });

    $('#editSpecializationForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#editSpecializationId').val();

        $.ajax({
            url: `/admin/specializations/${id}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'PUT', name: $('#editSpecializationName').val() }
        }).done(function (res) {
            window.showToast('success', res.message);
            editModal.hide();
            reloadSpecializationsTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Update failed.');
        });
    });

    $(document).on('click', '.delete-specialization-btn', function () {
        deleteId = $(this).data('id');
        $('#deleteSpecializationName').text($(this).data('name'));
        deleteModal.show();
    });

    $('#confirmDeleteSpecializationBtn').on('click', function () {
        if (!deleteId) return;

        $.ajax({
            url: `/admin/specializations/${deleteId}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'DELETE' }
        }).done(function (res) {
            window.showToast('success', res.message);
            deleteModal.hide();
            reloadSpecializationsTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Delete failed.');
        });
    });
});
</script>
@endpush
