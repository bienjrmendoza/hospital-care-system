<?php

namespace App\Policies;

use App\Models\ScheduleRequest;
use App\Models\User;

class ScheduleRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_DOCTOR, User::ROLE_USER], true);
    }

    public function view(User $user, ScheduleRequest $scheduleRequest): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor()) {
            return $scheduleRequest->schedule?->doctor_id === $user->id;
        }

        return $scheduleRequest->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isUser();
    }

    public function update(User $user, ScheduleRequest $scheduleRequest): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor()) {
            return $scheduleRequest->schedule?->doctor_id === $user->id;
        }

        return $scheduleRequest->user_id === $user->id;
    }
}
