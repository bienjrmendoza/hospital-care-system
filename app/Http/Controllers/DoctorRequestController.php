<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DoctorRequestController extends Controller
{
    public function index(): View
    {
        $requests = ScheduleRequest::query()
            ->with(['user', 'schedule'])
            ->whereHas('schedule', fn ($query) => $query->where('doctor_id', auth()->id()))
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('doctor.requests', compact('requests'));
    }

    public function accept(ScheduleRequest $scheduleRequest): JsonResponse
    {
        $scheduleRequest->load('schedule');
        $this->authorize('update', $scheduleRequest);

        $result = DB::transaction(function () use ($scheduleRequest) {
            $locked = ScheduleRequest::query()->lockForUpdate()->findOrFail($scheduleRequest->id);
            $schedule = Schedule::query()->lockForUpdate()->findOrFail($locked->schedule_id);

            if ($locked->status !== ScheduleRequest::STATUS_PENDING) {
                return ['ok' => false, 'message' => 'Only pending requests can be accepted.'];
            }

            if ($schedule->status !== Schedule::STATUS_AVAILABLE) {
                return ['ok' => false, 'message' => 'Schedule is no longer available.'];
            }

            $locked->update([
                'status' => ScheduleRequest::STATUS_ACCEPTED,
                'responded_at' => now(),
            ]);

            $schedule->update(['status' => Schedule::STATUS_BOOKED]);

            ScheduleRequest::query()
                ->where('schedule_id', $schedule->id)
                ->where('id', '!=', $locked->id)
                ->where('status', ScheduleRequest::STATUS_PENDING)
                ->update([
                    'status' => ScheduleRequest::STATUS_DECLINED,
                    'responded_at' => now(),
                ]);

            return ['ok' => true, 'message' => 'Request accepted.'];
        });

        return $result['ok']
            ? response()->json($result)
            : response()->json($result, 422);
    }

    public function decline(ScheduleRequest $scheduleRequest): JsonResponse
    {
        $scheduleRequest->load('schedule');
        $this->authorize('update', $scheduleRequest);

        if ($scheduleRequest->status !== ScheduleRequest::STATUS_PENDING) {
            return response()->json(['message' => 'Only pending requests can be declined.'], 422);
        }

        $scheduleRequest->update([
            'status' => ScheduleRequest::STATUS_DECLINED,
            'responded_at' => now(),
        ]);

        return response()->json(['message' => 'Request declined.']);
    }
}
