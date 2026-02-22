<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorScheduleController extends Controller
{
    public function dashboard(): View
    {
        return view('doctor.dashboard');
    }

    public function index(Request $request): View
    {
        $schedules = Schedule::query()
            ->where('doctor_id', auth()->id())
            ->when($request->filled('date'), fn (Builder $query) => $query->whereDate('date', $request->query('date')))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedules', compact('schedules'));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Schedule::class);

        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(14)->toDateString()],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*.start_time' => ['required', 'date_format:H:i'],
            'slots.*.end_time' => ['required', 'date_format:H:i'],
        ]);

        foreach ($data['slots'] as $slot) {
            if ($slot['end_time'] <= $slot['start_time']) {
                return response()->json(['message' => 'End time must be after start time.'], 422);
            }

            if ($this->hasOverlap(auth()->id(), $data['date'], $slot['start_time'], $slot['end_time'])) {
                return response()->json(['message' => 'Overlapping schedule detected.'], 422);
            }

            Schedule::create([
                'doctor_id' => auth()->id(),
                'date' => $data['date'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'status' => Schedule::STATUS_AVAILABLE,
            ]);
        }

        return response()->json(['message' => 'Schedules added successfully.']);
    }

    public function update(Request $request, Schedule $schedule): JsonResponse
    {
        $this->authorize('update', $schedule);

        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(14)->toDateString()],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:available,booked'],
        ]);

        if ($data['end_time'] <= $data['start_time']) {
            return response()->json(['message' => 'End time must be after start time.'], 422);
        }

        if ($this->hasOverlap($schedule->doctor_id, $data['date'], $data['start_time'], $data['end_time'], $schedule->id)) {
            return response()->json(['message' => 'Overlapping schedule detected.'], 422);
        }

        $schedule->update($data);

        return response()->json(['message' => 'Schedule updated.']);
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $this->authorize('delete', $schedule);

        $hasAcceptedRequest = $schedule->requests()
            ->where('status', ScheduleRequest::STATUS_ACCEPTED)
            ->exists();

        if ($hasAcceptedRequest) {
            return response()->json([
                'message' => 'This schedule already has an accepted booking and cannot be deleted.',
            ], 422);
        }

        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted.']);
    }

    private function hasOverlap(int $doctorId, string $date, string $start, string $end, ?int $ignoreId = null): bool
    {
        return Schedule::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('date', $date)
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->where(function (Builder $query) use ($start, $end): void {
                $query->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();
    }
}
