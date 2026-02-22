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
        $date = Carbon::parse($request->query('start', now()->toDateString()));

        $availableDoctors = $this->availableDoctors($request, $date->toDateString());

        return view('home', [
            'doctors' => $doctors,
            'specializations' => $specializations,
            'start' => $date,
            'availableDoctors' => $availableDoctors,
        ]);
    }

    public function feed(Request $request): JsonResponse
    {
        $data = $request->validate([
            'doctor_id' => ['nullable', 'integer', 'exists:users,id'],
            'specialization_id' => ['nullable', 'integer', 'exists:specializations,id'],
            'start' => ['required', 'date'],
        ]);

        $availableDoctors = $this->availableDoctors($request, $data['start']);

        return response()->json([
            'html' => view('partials.doctor-availability-cards', [
                'availableDoctors' => $availableDoctors,
                'date' => Carbon::parse($data['start']),
            ])->render(),
        ]);
    }

    public function doctorSchedules(Request $request, User $doctor): View
    {
        abort_unless($doctor->isDoctor(), 404);

        $date = Carbon::parse($request->query('date', now()->toDateString()));

        $schedules = Schedule::query()
            ->where('doctor_id', $doctor->id)
            ->whereDate('date', $date->toDateString())
            ->where('status', Schedule::STATUS_AVAILABLE)
            ->orderBy('start_time')
            ->get();

        return view('doctor.public-schedules', [
            'doctor' => $doctor->load('doctorProfile.specializationRef'),
            'date' => $date,
            'schedules' => $schedules,
        ]);
    }

    private function availableDoctors(Request $request, string $date)
    {
        return User::query()
            ->where('role', User::ROLE_DOCTOR)
            ->with([
                'doctorProfile.specializationRef',
                'schedules' => function ($query) use ($date): void {
                    $query->whereDate('date', $date)
                        ->where('status', Schedule::STATUS_AVAILABLE)
                        ->orderBy('start_time');
                },
            ])
            ->whereHas('schedules', function (Builder $query) use ($date): void {
                $query->whereDate('date', $date)
                    ->where('status', Schedule::STATUS_AVAILABLE);
            })
            ->when($request->filled('doctor_id'), function (Builder $query) use ($request): void {
                $query->where('id', (int) $request->query('doctor_id'));
            })
            ->when($request->filled('specialization_id'), function (Builder $query) use ($request): void {
                $query->whereHas('doctorProfile', function (Builder $profileQuery) use ($request): void {
                    $profileQuery->where('specialization_id', (int) $request->query('specialization_id'));
                });
            })
            ->orderBy('name')
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
                'schedules',
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
