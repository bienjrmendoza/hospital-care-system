@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Doctor Requests Inbox</h1>

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
                    <td>{{ $request->schedule->date->format('Y-m-d') }}</td>
                    <td>{{ substr($request->schedule->start_time, 0, 5) }} - {{ substr($request->schedule->end_time, 0, 5) }}</td>
                    <td><span class="badge text-bg-secondary">{{ $request->status }}</span></td>
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
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

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

    $(document).on('click', '.accept-btn, .decline-btn', function () {
        const id = $(this).data('id');
        const action = $(this).hasClass('accept-btn') ? 'accept' : 'decline';

        $.ajax({
            url: `/doctor/requests/${id}/${action}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { _method: 'PATCH' }
        }).done(function (res) {
            window.showToast('success', res.message);
            reloadRequestsTable();
        }).fail(function (xhr) {
            window.showToast('danger', xhr.responseJSON?.message || 'Action failed.');
        });
    });
});
</script>
@endpush

