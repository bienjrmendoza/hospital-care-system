@extends('layouts.app')

@section('content')
<div class="back-btn admin-btn mb-3">
    <button class="bg-primary text-white secondary-hover text-center px-5" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
<h3 class="text-secondary mb-3">Invite Doctors</h3>

<div class="card shadow-sm mb-4">
    <div class="card-body admin-btn">
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
            <div class="col-md-1"><input class="form-control shadow-none" type="number" name="expires_in_days" min="1" max="14" value="7" required></div>
            <div class="col-md-2"><button class="bg-primary text-white secondary-hover w-100" type="submit"><i class="fa-solid fa-plus"></i> Send</button></div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0 admin-btn">
            <thead><tr><th>Email</th><th>Specialization</th><th>Share</th><th>Expires</th><th>Used</th></tr></thead>
            <tbody>
            @forelse($invites as $invite)
                <tr>
                    <td>{{ $invite->email }}</td>
                    <td>{{ $invite->specializationRef?->name ?? 'N/A' }}</td>
                    <td>
                        <button
                            type="button"
                            class="bg-primary text-white secondary-hover w-100 share-invite-btn"
                            data-url="{{ route('doctor.invites.accept', $invite->token) }}"
                            title="Copy invite link"
                            aria-label="Copy invite link"
                        >
                        <i class="fa-solid fa-link"></i>
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
