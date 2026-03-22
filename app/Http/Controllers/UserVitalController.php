<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vital;
use Barryvdh\DomPDF\Facade\Pdf;

class UserVitalController extends Controller
{
    public function index()
    {
        $vitals = auth()->user()->vitals()->latest()->get();
        return view('user.vitals', compact('vitals'));
    }

    public function view(Vital $vital)
    {
        if ($vital->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $pdf = Pdf::loadView('user.pdf.vitals', compact('vital'))
                  ->setPaper('a4', 'portrait');
        return $pdf->stream("vital_report_{$vital->id}.pdf");
    }
}