<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\ScheduleRequest;
use App\Models\User;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_DOCTOR, User::ROLE_USER], true);
    }

    public function view(User $user, Schedule $schedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor()) {
            return $schedule->doctor_id === $user->id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDoctor();
    }

    public function update(User $user, Schedule $schedule): bool
    {
        return $user->isAdmin() || ($user->isDoctor() && $schedule->doctor_id === $user->id);
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        if (! $this->update($user, $schedule)) {
            return false;
        }

        return ! $schedule->requests()
            ->where('status', ScheduleRequest::STATUS_ACCEPTED)
            ->exists();
    }
}
