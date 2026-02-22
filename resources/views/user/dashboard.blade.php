@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h3 class="text-secondary mb-3">My Schedule Requests</h3>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Status</th>
                <th>Notes</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->schedule?->date?->format('Y-m-d') }}</td>
                    <td>{{ substr($request->schedule?->start_time ?? '', 0, 5) }} - {{ substr($request->schedule?->end_time ?? '', 0, 5) }}</td>
                    <td>{{ $request->schedule?->doctor?->name }}</td>
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
                            <form method="POST" action="{{ route('schedule-requests.cancel', $request) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-secondary">No requests yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
