<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Vital;
use Barryvdh\DomPDF\Facade\Pdf;

class DoctorVitalPdfController extends Controller
{
    public function index()
    {
        $doctor = auth()->user();

        $users = User::whereHas('scheduleRequests.schedule', function($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->get();

        $vitals = Vital::with('user')
            ->whereHas('user.scheduleRequests.schedule', function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->latest()
            ->take(20)
            ->get();

        return view('doctor.vitals', compact('users', 'vitals'));
    }

    public function export(Request $request)
    {
        $doctor = auth()->user();

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'blood_pressure' => 'nullable|string',
            'heart_rate' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'respiratory_rate' => 'nullable|numeric',
            'oxygen_saturation' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'initial_assessment' => 'nullable|string',
            'diagnostic' => 'nullable|string',
            'medication' => 'nullable|string',
            'treatment' => 'nullable|string',
            'diet' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $user = User::findOrFail($data['user_id']);

        if (!$user->scheduleRequests()->whereHas('schedule', function($q) use ($doctor){
            $q->where('doctor_id', $doctor->id);
        })->exists()) {
            abort(403, 'Unauthorized');
        }

        $bmi = null;
        if (!empty($data['weight']) && !empty($data['height'])) {
            $height = $data['height'] / 100;
            $bmi = round($data['weight'] / ($height * $height), 2);
        }

        $vital = Vital::create([
            'user_id' => $user->id,
            'date' => $data['date'],
            'blood_pressure' => $data['blood_pressure'] ?? null,
            'heart_rate' => $data['heart_rate'] ?? null,
            'temperature' => $data['temperature'] ?? null,
            'respiratory_rate' => $data['respiratory_rate'] ?? null,
            'oxygen_saturation' => $data['oxygen_saturation'] ?? null,
            'weight' => $data['weight'] ?? null,
            'height' => $data['height'] ?? null,
            'bmi' => $bmi,
            'notes' => $data['notes'] ?? null,
            'initial_assessment' => $data['initial_assessment'] ?? null,
            'diagnostic' => $data['diagnostic'] ?? null,
            'medication' => $data['medication'] ?? null,
            'treatment' => $data['treatment'] ?? null,
            'diet' => $data['diet'] ?? null,
            'remarks' => $data['remarks'] ?? null,
        ]);

        $pdf = Pdf::loadView('doctor.pdf.vitals', [
            'user' => $user,
            'vital' => $vital,
        ]);

        return $pdf->stream('vitals-'.$user->name.'-'.$data['date'].'.pdf');
    }

    public function view(Request $request)
    {
        $doctor = auth()->user();

        $request->validate([
            'vital_id' => 'required|exists:vitals,id',
        ]);

        $vital = Vital::with('user')->findOrFail($request->vital_id);
        $user = $vital->user;

        if (!$user->scheduleRequests()->whereHas('schedule', function($q) use ($doctor){
            $q->where('doctor_id', $doctor->id);
        })->exists()) {
            abort(403, 'Unauthorized');
        }

        $pdf = Pdf::loadView('doctor.pdf.vitals', [
            'user' => $user,
            'vital' => $vital,
        ]);

        return $pdf->stream('vital-report-'.$user->name.'.pdf');
    }

    public function destroy($id)
    {
        $doctor = auth()->user();

        $vital = Vital::findOrFail($id);
        $user = $vital->user;

        if (!$user->scheduleRequests()->whereHas('schedule', function($q) use ($doctor){
            $q->where('doctor_id', $doctor->id);
        })->exists()) {
            abort(403, 'Unauthorized');
        }

        $vital->delete();

        return redirect()->back()->with('success', 'Deleted successfully');
    }
}