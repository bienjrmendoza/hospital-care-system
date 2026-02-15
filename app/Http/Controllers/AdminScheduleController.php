<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $doctors = User::query()->where('role', User::ROLE_DOCTOR)->orderBy('name')->get();

        $schedules = Schedule::query()
            ->with('doctor')
            ->when($request->filled('doctor_id'), fn (Builder $query) => $query->where('doctor_id', $request->query('doctor_id')))
            ->when($request->filled('date'), fn (Builder $query) => $query->whereDate('date', $request->query('date')))
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('admin.schedules', compact('schedules', 'doctors'));
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
        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted.']);
    }
}
