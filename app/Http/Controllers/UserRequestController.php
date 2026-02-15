<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserRequestController extends Controller
{
    public function dashboard(): View
    {
        $requests = ScheduleRequest::query()
            ->with(['schedule.doctor'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.dashboard', compact('requests'));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', ScheduleRequest::class);

        $data = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $result = DB::transaction(function () use ($data) {
            $schedule = Schedule::query()->lockForUpdate()->findOrFail($data['schedule_id']);

            if ($schedule->status !== Schedule::STATUS_AVAILABLE) {
                return ['ok' => false, 'message' => 'This slot is no longer available.'];
            }

            $hasConflict = ScheduleRequest::query()
                ->where('schedule_id', $schedule->id)
                ->where('user_id', auth()->id())
                ->whereIn('status', [ScheduleRequest::STATUS_PENDING, ScheduleRequest::STATUS_ACCEPTED])
                ->exists();

            if ($hasConflict) {
                return ['ok' => false, 'message' => 'You already have an active request for this slot.'];
            }

            ScheduleRequest::create([
                'schedule_id' => $schedule->id,
                'user_id' => auth()->id(),
                'status' => ScheduleRequest::STATUS_PENDING,
                'notes' => $data['notes'] ?? null,
            ]);

            return ['ok' => true, 'message' => 'Schedule request submitted.'];
        });

        return $result['ok']
            ? response()->json($result)
            : response()->json($result, 422);
    }

    public function cancel(ScheduleRequest $scheduleRequest): RedirectResponse
    {
        $this->authorize('update', $scheduleRequest);

        if ($scheduleRequest->status !== ScheduleRequest::STATUS_PENDING) {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $scheduleRequest->update([
            'status' => ScheduleRequest::STATUS_CANCELLED,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Request cancelled.');
    }
}
