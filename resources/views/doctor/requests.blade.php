@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<h3 class="text-secondary mb-3">Doctor Requests Inbox</h3>

<div class="card shadow-sm" id="doctorRequestsTableWrap">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Notes</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->schedule->date->format('F j, Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($request->schedule->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($request->schedule->end_time)->format('g:i A') }}</td>
                    <td>
                        @if($request->status === 'pending')
                            <span class="badge text-bg-warning">{{ ucfirst($request->status) }}</span>
                        @elseif($request->status === 'accepted')
                            <span class="badge text-bg-success">{{ ucfirst($request->status) }}</span>
                        @elseif($request->status === 'cancelled')
                            <span class="badge text-bg-secondary">{{ ucfirst($request->status) }}</span>
                        @elseif($request->status === 'declined')
                            <span class="badge text-bg-danger">{{ ucfirst($request->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $request->notes }}</td>
                    <td class="text-end">
                        @if($request->status === 'pending')
                            <button class="btn btn-sm btn-success accept-btn" data-id="{{ $request->id }}">Accept</button>
                            <button class="btn btn-sm btn-outline-danger decline-btn" data-id="{{ $request->id }}">Decline</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-secondary">No requests found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="confirmRequestActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-secondary" id="confirmRequestActionTitle">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="confirmRequestActionText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmRequestActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const confirmModalEl = document.getElementById('confirmRequestActionModal');
    const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
    let pendingRequestId = null;
    let pendingAction = null;

    function reloadRequestsTable() {
        $.get(window.location.href).done(function (html) {
            const updated = $(html).find('#doctorRequestsTableWrap').html();
            if (updated) {
                $('#doctorRequestsTableWrap').html(updated);
            }
        }).fail(function () {
            window.showToast('danger', 'Failed to refresh requests table.');
        });
    }

    function runRequestAction() {
        if (!pendingRequestId || !pendingAction) return;

        $.ajax({
            url: `/doctor/requests/${pendingRequestId}/${pendingAction}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'PATCH' }
        }).done(function (res) {
            window.showToast('success', res.message);
            reloadRequestsTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Action failed.');
        }).always(function () {
            pendingRequestId = null;
            pendingAction = null;
        });
    }

    $(document).on('click', '.accept-btn, .decline-btn', function () {
        pendingRequestId = $(this).data('id');
        pendingAction = $(this).hasClass('accept-btn') ? 'accept' : 'decline';

        const isAccept = pendingAction === 'accept';
        $('#confirmRequestActionTitle').text(isAccept ? 'Confirm Accept' : 'Confirm Decline');
        $('#confirmRequestActionText').text(isAccept ? 'Are you sure you want to accept this request?' : 'Are you sure you want to decline this request?');
        $('#confirmRequestActionBtn')
            .text(isAccept ? 'Accept Request' : 'Decline Request')
            .toggleClass('btn-primary', isAccept)
            .toggleClass('btn-danger', !isAccept);

        confirmModal?.show();
    });

    $('#confirmRequestActionBtn').on('click', function () {
        confirmModal?.hide();
        runRequestAction();
    });
});
</script>
@endpush
