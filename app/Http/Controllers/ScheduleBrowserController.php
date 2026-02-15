<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ScheduleBrowserController extends Controller
{
    public function index(Request $request): View
    {
        $doctors = User::query()
            ->where('role', User::ROLE_DOCTOR)
            ->with('doctorProfile')
            ->orderBy('name')
            ->get();

        $start = Carbon::parse($request->query('start', now()->toDateString()));
        $end = $request->filled('end') ? Carbon::parse($request->query('end')) : null;

        $schedules = $this->filteredSchedules(
            $request,
            $start->toDateString(),
            $end?->toDateString()
        );

        return view('home', [
            'doctors' => $doctors,
            'start' => $start,
            'end' => $end,
            'schedules' => $schedules,
        ]);
    }

    public function feed(Request $request): JsonResponse
    {
        $data = $request->validate([
            'doctor_id' => ['nullable', 'integer', 'exists:users,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
        ]);

        $schedules = $this->filteredSchedules($request, $data['start'], $data['end'] ?? null);

        return response()->json([
            'html' => view('partials.schedule-grid', [
                'schedules' => $schedules,
            ])->render(),
        ]);
    }

    private function filteredSchedules(Request $request, string $startDate, ?string $endDate)
    {
        return Schedule::query()
            ->with(['doctor.doctorProfile'])
            ->whereDate('date', '>=', $startDate)
            ->when($endDate, function (Builder $query) use ($endDate): void {
                $query->whereDate('date', '<=', $endDate);
            })
            ->where('status', Schedule::STATUS_AVAILABLE)
            ->when($request->filled('doctor_id'), function (Builder $query) use ($request): void {
                $query->where('doctor_id', (int) $request->query('doctor_id'));
            })
            ->when($request->filled('specialization'), function (Builder $query) use ($request): void {
                $query->whereHas('doctor.doctorProfile', function (Builder $profileQuery) use ($request): void {
                    $profileQuery->where('specialization', 'like', '%' . $request->query('specialization') . '%');
                });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }
}
