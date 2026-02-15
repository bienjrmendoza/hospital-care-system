@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Invite Doctors</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.invites.store') }}" class="row g-2">
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
            <div class="col-md-2"><input class="form-control shadow-none" type="number" name="expires_in_days" min="1" max="14" value="7" required></div>
            <div class="col-md-1"><button class="btn btn-primary w-100" type="submit">Send</button></div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Email</th><th>Specialization</th><th>Share</th><th>Expires</th><th>Used</th></tr></thead>
            <tbody>
            @forelse($invites as $invite)
                <tr>
                    <td>{{ $invite->email }}</td>
                    <td>{{ $invite->specializationRef?->name ?? 'N/A' }}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary share-invite-btn"
                            data-url="{{ route('doctor.invites.accept', $invite->token) }}"
                            title="Copy invite link"
                            aria-label="Copy invite link"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M11 2a3 3 0 0 1 2.995 2.824L14 5v2a3 3 0 0 1-2.824 2.995L11 10H9V8h2a1 1 0 0 0 .117-1.993L11 6H9V4h2Zm-4 8v2H5a3 3 0 0 1-2.995-2.824L2 9V7a3 3 0 0 1 2.824-2.995L5 4h2v2H5a1 1 0 0 0-.117 1.993L5 8h2v2H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h2v2H5a1 1 0 0 0 0 2h2v2Zm2-5v6H7V5h2Z"/>
                            </svg>
                        </button>
                    </td>
                    <td>{{ $invite->expires_at->format('F j, Y g:i A') }}</td>
                    <td>{{ $invite->used_at?->format('F j, Y g:i A') ?? 'No' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4 text-secondary">No invites yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $(document).on('click', '.share-invite-btn', async function () {
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
