<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RbacAccessTest extends TestCase
{
    public function test_user_cannot_access_doctor_area(): void
    {
        $user = new User([
            'id' => 101,
            'name' => 'Sample User',
            'email' => 'user@example.test',
            'role' => User::ROLE_USER,
        ]);

        $this->actingAs($user)
            ->get(route('doctor.dashboard'))
            ->assertForbidden();
    }

    public function test_user_cannot_access_admin_area(): void
    {
        $user = new User([
            'id' => 102,
            'name' => 'Sample User',
            'email' => 'user2@example.test',
            'role' => User::ROLE_USER,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_dashboard_redirects_doctor_to_doctor_home(): void
    {
        $doctor = new User([
            'id' => 201,
            'name' => 'Doctor',
            'email' => 'doctor@example.test',
            'role' => User::ROLE_DOCTOR,
        ]);

        $this->actingAs($doctor)
            ->get(route('dashboard'))
            ->assertRedirect(route('doctor.dashboard'));
    }

    public function test_dashboard_redirects_admin_to_admin_home(): void
    {
        $admin = new User([
            'id' => 301,
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
