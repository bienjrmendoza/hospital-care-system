<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminScheduleController extends Controller
{
    public function index(): View
    {
        $doctors = User::query()->where('role', User::ROLE_DOCTOR)->orderBy('name')->get();

        return view('admin.schedules', compact('doctors'));
    }

    public function feed(Request $request): JsonResponse
    {
        $schedules = Schedule::query()
            ->with('doctor')
            ->when($request->filled('doctor_id'), fn (Builder $query) => $query->where('doctor_id', $request->query('doctor_id')))
            ->when($request->filled('date'), fn (Builder $query) => $query->whereDate('date', $request->query('date')))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(fn (Schedule $schedule): array => [
                'id' => $schedule->id,
                'doctor_name' => $schedule->doctor->name,
                'date' => $schedule->date->format('Y-m-d'),
                'date_sort' => $schedule->date->timestamp,
                'time' => substr($schedule->start_time, 0, 5) . ' - ' . substr($schedule->end_time, 0, 5),
                'start_time' => substr($schedule->start_time, 0, 5),
                'end_time' => substr($schedule->end_time, 0, 5),
                'status' => $schedule->status,
            ])
            ->values();

        return response()->json([
            'data' => $schedules,
        ]);
    }

    public function update(Request $request, Schedule $schedule): JsonResponse
    {
        $this->authorize('update', $schedule);

        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:available,booked'],
        ]);

        if ($data['end_time'] <= $data['start_time']) {
            return response()->json(['message' => 'End time must be after start time.'], 422);
        }

        $hasOverlap = Schedule::query()
            ->where('doctor_id', $schedule->doctor_id)
            ->whereDate('date', $data['date'])
            ->where('id', '!=', $schedule->id)
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();

        if ($hasOverlap) {
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
}
