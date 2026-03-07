<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vital;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminVitalController extends Controller
{
    public function index()
    {
        $users = User::where('role', User::ROLE_USER)->get();

        $vitals = Vital::with('user')->latest()->take(20)->get();

        return view('admin.vitals', compact('users', 'vitals'));
    }

    public function export(Request $request)
    {
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
        ]);

        $user = User::findOrFail($data['user_id']);

        $bmi = null;
        if (!empty($data['weight']) && !empty($data['height'])) {
            $heightInMeters = $data['height'] / 100;
            $bmi = $data['weight'] / ($heightInMeters * $heightInMeters);
            $bmi = round($bmi, 2);
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
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf.vitals', [
            'user' => $user,
            'vital' => $vital,
        ]);

        return $pdf->stream('vitals-'.$user->name.'-'.$data['date'].'.pdf');
    }

    public function view(Request $request)
    {
        $request->validate([
            'vital_id' => 'required|exists:vitals,id',
        ]);

        $vital = Vital::with('user')->findOrFail($request->vital_id);
        $user = $vital->user;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf.vitals', [
            'user' => $user,
            'vital' => $vital,
        ]);

        return $pdf->stream('vital-report-'.$user->name.'.pdf');
    }

    public function destroy($id)
    {
        $vital = Vital::findOrFail($id);
        $vital->delete();

        return redirect()->back()->with('success','Report deleted successfully.');
    }
}
