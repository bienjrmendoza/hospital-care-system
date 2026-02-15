<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Specialization</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->date->format('Y-m-d') }}</td>
                    <td>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                    <td>{{ $schedule->doctor->name }}</td>
                    <td>{{ $schedule->doctor->doctorProfile?->specializationRef?->name ?? 'General' }}</td>
                    <td class="text-end">
                        @auth
                            @if(auth()->user()->isUser())
                                <button class="btn btn-sm btn-primary request-slot-btn" data-id="{{ $schedule->id }}">Request</button>
                            @endif
                        @endauth
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-secondary py-4">No available schedules for selected filters.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

