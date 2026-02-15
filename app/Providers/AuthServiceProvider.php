<?php

namespace App\Providers;

use App\Models\Schedule;
use App\Models\ScheduleRequest;
use App\Policies\SchedulePolicy;
use App\Policies\ScheduleRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Schedule::class => SchedulePolicy::class,
        ScheduleRequest::class => ScheduleRequestPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
