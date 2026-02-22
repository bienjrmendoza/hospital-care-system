@extends('layouts.app')

@push('scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admins.css') }}">
@endpush

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<h3 class="text-secondary mb-3">Manage Admins</h3>

<div class="card shadow-sm mb-4">
    <div class="card-body admin-btn">
        <form id="adminForm" class="row g-2">
            @csrf
            <div class="col-md-3"><input class="form-control shadow-none" name="name" placeholder="Name" required></div>
            <div class="col-md-3"><input class="form-control shadow-none" type="email" name="email" placeholder="Email" required></div>
            <div class="col-md-2"><input class="form-control shadow-none" type="password" name="password" placeholder="Password" required></div>
            <div class="col-md-2"><input class="form-control shadow-none" type="password" name="password_confirmation" placeholder="Confirm" required></div>
            <div class="col-md-2"><button id="createAdminBtn" class="bg-primary text-white secondary-hover w-100" type="submit"><i class="fa-solid fa-plus"></i> Create Admin</button></div>
        </form>
    </div>
</div>

<div class="card shadow-sm" id="adminsTableCard">
    <div class="card-body pb-2">
        <div class="table-responsive">
            <table id="adminsTable" class="table table-striped mb-0 w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const $adminForm = $('#adminForm');
    const $createAdminBtn = $('#createAdminBtn');

    const table = $('#adminsTable').DataTable({
        ajax: {
            url: '{{ route('admin.admins.feed') }}',
            dataSrc: 'data'
        },
        dom: "<'row g-3 align-items-center mb-3'<'col-md-6'l><'col-md-6'f>>" +
             "t" +
             "<'row g-3 align-items-center mt-2'<'col-md-6'i><'col-md-6'p>>",
        order: [],
        columns: [
            { data: 'name' },
            { data: 'email' },
            {
                data: 'created_at',
                render: function (data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return row.created_at_sort;
                    }
                    return data;
                }
            }
        ]
    });

    $adminForm.on('submit', function (e) {
        e.preventDefault();

        $createAdminBtn.prop('disabled', true);

        $.ajax({
            url: '{{ route('admin.admins.store') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: $adminForm.serialize()
        }).done(function (res) {
            window.showToast('success', res.message || 'Admin created.');
            $adminForm[0].reset();
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            const errors = xhr.responseJSON?.errors;

            if (errors) {
                Object.values(errors).flat().forEach(function (message) {
                    window.showToast('danger', message);
                });
                return;
            }

            window.showToast('danger', xhr.responseJSON?.message || 'Admin creation failed.');
        }).always(function () {
            $createAdminBtn.prop('disabled', false);
        });
    });
});
</script>
@endpush
