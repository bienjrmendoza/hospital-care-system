<?php

namespace Database\Seeders;

use App\Models\DoctorInvite;
use App\Models\DoctorProfile;
use App\Models\Schedule;
use App\Models\ScheduleRequest;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class NonAdminDataSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = collect([
            'General Medicine',
            'Cardiology',
            'Pediatrics',
            'Orthopedics',
            'Dermatology',
            'Neurology',
        ])->map(fn (string $name) => Specialization::query()->firstOrCreate(['name' => $name]));

        $doctorsData = [
            ['name' => 'Dr. Ava Mitchell', 'email' => 'ava.mitchell@hospital.test'],
            ['name' => 'Dr. Liam Carter', 'email' => 'liam.carter@hospital.test'],
            ['name' => 'Dr. Noah Bennett', 'email' => 'noah.bennett@hospital.test'],
            ['name' => 'Dr. Emma Collins', 'email' => 'emma.collins@hospital.test'],
            ['name' => 'Dr. Mia Brooks', 'email' => 'mia.brooks@hospital.test'],
        ];

        $doctors = collect($doctorsData)->values()->map(function (array $doctorData, int $index) use ($specializations): User {
            $doctor = User::query()->updateOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'password' => 'password123',
                    'role' => User::ROLE_DOCTOR,
                ]
            );

            DoctorProfile::query()->updateOrCreate(
                ['user_id' => $doctor->id],
                ['specialization_id' => $specializations[$index % $specializations->count()]->id]
            );

            return $doctor;
        });

        $patientsData = [
            ['name' => 'Sophia Reyes', 'email' => 'sophia.reyes@hospital.test'],
            ['name' => 'Ethan Hall', 'email' => 'ethan.hall@hospital.test'],
            ['name' => 'Olivia Stone', 'email' => 'olivia.stone@hospital.test'],
            ['name' => 'Lucas Gray', 'email' => 'lucas.gray@hospital.test'],
            ['name' => 'Isabella Cruz', 'email' => 'isabella.cruz@hospital.test'],
            ['name' => 'Mason Lee', 'email' => 'mason.lee@hospital.test'],
            ['name' => 'Amelia Scott', 'email' => 'amelia.scott@hospital.test'],
            ['name' => 'James Ward', 'email' => 'james.ward@hospital.test'],
        ];

        $patients = collect($patientsData)->map(fn (array $patientData) => User::query()->updateOrCreate(
            ['email' => $patientData['email']],
            [
                'name' => $patientData['name'],
                'password' => 'password123',
                'role' => User::ROLE_USER,
            ]
        ));

        $startDate = Carbon::today();

        $slots = [
            ['08:00:00', '09:00:00'],
            ['10:00:00', '11:00:00'],
            ['13:00:00', '14:00:00'],
            ['15:00:00', '16:00:00'],
        ];

        $allSchedules = collect();

        foreach ($doctors as $doctorIndex => $doctor) {
            for ($dayOffset = 0; $dayOffset < 10; $dayOffset++) {
                $date = $startDate->copy()->addDays($dayOffset)->toDateString();

                foreach ($slots as $slotIndex => [$start, $end]) {
                    $status = (($doctorIndex + $dayOffset + $slotIndex) % 4 === 0)
                        ? Schedule::STATUS_BOOKED
                        : Schedule::STATUS_AVAILABLE;

                    $schedule = Schedule::query()->updateOrCreate(
                        [
                            'doctor_id' => $doctor->id,
                            'date' => $date,
                            'start_time' => $start,
                            'end_time' => $end,
                        ],
                        ['status' => $status]
                    );

                    $allSchedules->push($schedule);
                }
            }
        }

        $requestStatuses = [
            ScheduleRequest::STATUS_PENDING,
            ScheduleRequest::STATUS_ACCEPTED,
            ScheduleRequest::STATUS_DECLINED,
            ScheduleRequest::STATUS_CANCELLED,
        ];

        $schedulesForRequests = $allSchedules->shuffle()->take(28)->values();

        foreach ($schedulesForRequests as $index => $schedule) {
            $patient = $patients[$index % $patients->count()];
            $status = $requestStatuses[$index % count($requestStatuses)];

            ScheduleRequest::query()->updateOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'user_id' => $patient->id,
                ],
                [
                    'status' => $status,
                    'notes' => 'Seeder-generated sample request.',
                    'responded_at' => $status === ScheduleRequest::STATUS_PENDING ? null : now()->subDays(random_int(0, 3)),
                ]
            );
        }

        $admin = User::query()->where('role', User::ROLE_ADMIN)->first();

        if ($admin) {
            foreach ($specializations->values() as $index => $specialization) {
                $email = 'doctor-invite-' . ($index + 1) . '@hospital.test';
                $used = $index % 3 === 0;

                DoctorInvite::query()->updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => 'Invited ' . $specialization->name . ' Doctor',
                        'specialization_id' => $specialization->id,
                        'token' => Str::random(64),
                        'expires_at' => now()->addDays(7 + ($index % 5)),
                        'created_by_admin_id' => $admin->id,
                        'used_at' => $used ? now()->subDays(1) : null,
                    ]
                );
            }
        }
    }
}
