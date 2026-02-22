@extends('layouts.app')

@push('scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/invites.css') }}">
@endpush

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<h3 class="text-secondary mb-3">Invite Doctors</h3>

<div class="card shadow-sm mb-4">
    <div class="card-body admin-btn">
        <form id="inviteForm" class="row g-2">
            @csrf
            <div class="col-md-3"><input class="form-control shadow-none" name="name" placeholder="Doctor name" required></div>
            <div class="col-md-3"><input class="form-control shadow-none" type="email" name="email" placeholder="Doctor email" required></div>
            <div class="col-md-3">
                <select class="form-select shadow-none" name="specialization_id" required>
                    <option value="">Select specialization</option>
                    @foreach($specializations as $specialization)
                        <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1"><input class="form-control shadow-none" type="number" name="expires_in_days" min="1" max="14" value="7" required></div>
            <div class="col-md-2"><button id="sendInviteBtn" class="bg-primary text-white secondary-hover w-100" type="submit"><i class="fa-solid fa-plus"></i> Send</button></div>
        </form>
    </div>
</div>

<div class="card shadow-sm" id="invitesTableCard">
    <div class="card-body pb-2">
        <div class="table-responsive">
            <table id="invitesTable" class="table table-striped mb-0 admin-btn w-100">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Share</th>
                        <th>Expires</th>
                        <th>Used</th>
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
    const $inviteForm = $('#inviteForm');
    const $sendInviteBtn = $('#sendInviteBtn');

    const table = $('#invitesTable').DataTable({
        ajax: {
            url: '{{ route('admin.invites.feed') }}',
            dataSrc: 'data'
        },
        dom: "<'row g-3 align-items-center mb-3'<'col-md-6'l><'col-md-6'f>>" +
             "t" +
             "<'row g-3 align-items-center mt-2'<'col-md-6'i><'col-md-6'p>>",
        order: [],
        columns: [
            { data: 'email' },
            { data: 'specialization' },
            {
                data: 'expires_at',
                render: function (data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return row.expires_at_sort;
                    }
                    return data;
                }
            },
            {
                data: 'used_at',
                render: function (data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return row.used_at_sort;
                    }
                    return data;
                }
            },
            {
                data: 'share_url',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    const isUsed = Boolean(row.is_used);
                    const disabledAttr = isUsed ? 'disabled aria-disabled="true"' : '';
                    const title = isUsed ? 'Invite already used' : 'Copy invite link';

                    return `
                        <button
                            type="button"
                            class="bg-primary text-white secondary-hover w-100 share-invite-btn"
                            data-url="${data}"
                            ${disabledAttr}
                            title="${title}"
                            aria-label="${title}"
                        >
                            <i class="fa-solid fa-link"></i>
                        </button>
                    `;
                }
            },
        ]
    });

    $inviteForm.on('submit', function (e) {
        e.preventDefault();

        $sendInviteBtn.prop('disabled', true);

        $.ajax({
            url: '{{ route('admin.invites.store') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: $inviteForm.serialize()
        }).done(function (res) {
            window.showToast('success', res.message || 'Invite created successfully.');
            $inviteForm[0].reset();
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            const errors = xhr.responseJSON?.errors;

            if (errors) {
                Object.values(errors).flat().forEach(function (message) {
                    window.showToast('danger', message);
                });
                return;
            }

            window.showToast('danger', xhr.responseJSON?.message || 'Invite creation failed.');
        }).always(function () {
            $sendInviteBtn.prop('disabled', false);
        });
    });

    $(document).on('click', '.share-invite-btn', async function () {
        if ($(this).is(':disabled')) {
            return;
        }

        const url = $(this).data('url');

        try {
            await navigator.clipboard.writeText(url);
            window.showToast('success', 'Invite URL copied. Share it with the doctor.');
        } catch (e) {
            window.showToast('danger', 'Copy failed. Please try again.');
        }
    });
});
</script>
@endpush
