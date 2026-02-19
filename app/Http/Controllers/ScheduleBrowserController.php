<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Specialization;
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
            ->with('doctorProfile.specializationRef')
            ->orderBy('name')
            ->get();
        $specializations = Specialization::query()->orderBy('name')->get();

        $start = Carbon::parse($request->query('start', now()->toDateString()));
        $end = $request->filled('end') ? Carbon::parse($request->query('end')) : null;

        $schedules = $this->filteredSchedules(
            $request,
            $start->toDateString(),
            $end?->toDateString()
        );

        return view('home', [
            'doctors' => $doctors,
            'specializations' => $specializations,
            'start' => $start,
            'end' => $end,
            'schedules' => $schedules,
        ]);
    }

    public function feed(Request $request): JsonResponse
    {
        $data = $request->validate([
            'doctor_id' => ['nullable', 'integer', 'exists:users,id'],
            'specialization_id' => ['nullable', 'integer', 'exists:specializations,id'],
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
            ->with(['doctor.doctorProfile.specializationRef'])
            ->whereDate('date', '>=', $startDate)
            ->when($endDate, function (Builder $query) use ($endDate): void {
                $query->whereDate('date', '<=', $endDate);
            })
            ->where('status', Schedule::STATUS_AVAILABLE)
            ->when($request->filled('doctor_id'), function (Builder $query) use ($request): void {
                $query->where('doctor_id', (int) $request->query('doctor_id'));
            })
            ->when($request->filled('specialization_id'), function (Builder $query) use ($request): void {
                $query->whereHas('doctor.doctorProfile', function (Builder $profileQuery) use ($request): void {
                    $profileQuery->where('specialization_id', (int) $request->query('specialization_id'));
                });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    public function landingPage(): View
    {
        return view('index');
    }
    
    public function about(Request $request): View
    {
        $doctors = User::query()
            ->where('role', User::ROLE_DOCTOR)
            ->with([
                'doctorProfile.specializationRef',
                'schedules'
            ])
            ->orderBy('name')
            ->get();

        $specializations = Specialization::query()
            ->orderBy('name')
            ->get();

        $start = Carbon::parse(
            $request->query('start', now()->startOfWeek()->toDateString())
        );

        $end = (clone $start)->addDays(6);

        return view('about', [
            'doctors' => $doctors,
            'specializations' => $specializations,
            'start' => $start,
            'end' => $end,
        ]);
    }
}
