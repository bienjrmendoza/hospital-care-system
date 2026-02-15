# To All Beneficiaries of Hospital Care

Laravel 10 web application for hospital schedule availability and appointment requests.

## Stack
- Laravel 10
- Bootstrap 5 (CDN)
- jQuery (CDN)
- AJAX for schedule filtering, request submission, and doctor/admin actions

## Roles
- `admin`: create admins, invite doctors, manage all doctor schedules
- `doctor`: manage own schedules, accept/decline own incoming requests
- `user`: browse schedules, request slots, track own requests

## Features Implemented
- Role-based route protection via `role` middleware
- Authorization policies for schedules and schedule requests
- Token-based doctor invitation flow with expiration and one-time use
- Doctor weekly schedule management (create/update/delete own slots)
- Overlap prevention per doctor/day and time-range validation
- Public schedule browser with doctor/specialization/date filters (AJAX)
- User schedule requests (pending/accepted/declined/cancelled)
- Doctor request inbox with AJAX accept/decline
- Admin schedule management for all doctors
- Initial admin seeder
- RBAC feature tests for key access boundaries

## Database Tables
- `users` (`role` column added)
- `doctor_profiles`
- `doctor_invites`
- `schedules`
- `schedule_requests`

## Setup
1. Install dependencies:
   - `composer install`
2. Configure env:
   - `copy .env.example .env`
   - update DB settings in `.env`
3. Generate app key:
   - `php artisan key:generate`
4. Migrate and seed:
   - `php artisan migrate --seed`
5. Run app:
   - `php artisan serve`

## Default Admin (Development)
- Email: `admin@hospital.test`
- Password: `admin12345`

## Main Routes
- Public schedule browser: `/`
- Login: `/login`
- Register: `/register`
- User dashboard: `/user/dashboard`
- Doctor dashboard: `/doctor/dashboard`
- Admin dashboard: `/admin/dashboard`
- Doctor invite acceptance: `/doctor/invites/{token}`

## Notes
- AJAX endpoints are protected by middleware and policy checks.
- Doctors cannot update/delete other doctors' schedules or process others' requests.
- Users cannot manage doctor schedules.
