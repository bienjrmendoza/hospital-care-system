<?php

namespace App\Http\Controllers;

use App\Models\DoctorInvite;
use App\Models\DoctorProfile;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminInviteController extends Controller
{
    public function index(): View
    {
        $specializations = Specialization::query()->orderBy('name')->get();

        return view('admin.invites', compact('specializations'));
    }

    public function feed(): JsonResponse
    {
        $invites = DoctorInvite::query()
            ->with('specializationRef')
            ->latest()
            ->get()
            ->map(function (DoctorInvite $invite): array {
                return [
                    'email' => $invite->email,
                    'specialization' => $invite->specializationRef?->name ?? 'N/A',
                    'share_url' => route('doctor.invites.accept', $invite->token),
                    'expires_at' => $invite->expires_at?->format('F j, Y g:i A') ?? 'N/A',
                    'expires_at_sort' => $invite->expires_at?->timestamp ?? 0,
                    'used_at' => $invite->used_at?->format('F j, Y g:i A') ?? 'No',
                    'used_at_sort' => $invite->used_at?->timestamp ?? 0,
                    'is_used' => $invite->used_at !== null,
                ];
            })
            ->values();

        return response()->json([
            'data' => $invites,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'specialization_id' => ['required', 'integer', 'exists:specializations,id'],
            'expires_in_days' => ['required', 'integer', 'min:1', 'max:14'],
        ]);

        $invite = DoctorInvite::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'specialization_id' => $data['specialization_id'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays((int) $data['expires_in_days']),
            'created_by_admin_id' => auth()->id(),
        ]);
        $invite->load('specializationRef');

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Invite created successfully.',
                'invite' => [
                    'email' => $invite->email,
                    'specialization' => $invite->specializationRef?->name ?? 'N/A',
                    'share_url' => route('doctor.invites.accept', $invite->token),
                    'expires_at' => $invite->expires_at?->format('F j, Y g:i A') ?? 'N/A',
                    'expires_at_sort' => $invite->expires_at?->timestamp ?? 0,
                    'used_at' => $invite->used_at?->format('F j, Y g:i A') ?? 'No',
                    'used_at_sort' => $invite->used_at?->timestamp ?? 0,
                    'is_used' => false,
                ],
            ]);
        }

        return back()->with('success', 'Invite created successfully.');
    }

    public function showAccept(string $token): View
    {
        $invite = DoctorInvite::query()
            ->with('specializationRef')
            ->where('token', $token)
            ->firstOrFail();

        abort_unless($invite->isValid(), 410, 'Invite is invalid or expired.');

        return view('auth.doctor-invite-accept', compact('invite'));
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $invite = DoctorInvite::query()->where('token', $token)->firstOrFail();
        abort_unless($invite->isValid(), 410, 'Invite is invalid or expired.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::transaction(function () use ($invite, $data): void {
            $user = User::create([
                'name' => $data['name'],
                'email' => $invite->email,
                'password' => $data['password'],
                'role' => User::ROLE_DOCTOR,
            ]);

            DoctorProfile::create([
                'user_id' => $user->id,
                'specialization_id' => $invite->specialization_id,
            ]);

            $invite->update(['used_at' => now()]);
        });

        return redirect()->route('login')->with('success', 'Doctor account created. Please log in.');
    }
}
