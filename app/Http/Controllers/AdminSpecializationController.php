<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSpecializationController extends Controller
{
    public function index(): View
    {
        return view('admin.specializations');
    }

    public function feed(): JsonResponse
    {
        $specializations = Specialization::query()
            ->orderBy('name')
            ->get()
            ->map(fn (Specialization $specialization): array => [
                'id' => $specialization->id,
                'name' => $specialization->name,
                'created_at' => $specialization->created_at->format('F j, Y g:i A'),
                'created_at_sort' => $specialization->created_at->timestamp,
            ])
            ->values();

        return response()->json([
            'data' => $specializations,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $exists = Specialization::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim($data['name']))])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Specialization name already exists.',
            ], 422);
        }

        $specialization = Specialization::create([
            'name' => trim($data['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Specialization created successfully.',
            'specialization' => $specialization,
        ]);
    }

    public function update(Request $request, Specialization $specialization): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $exists = Specialization::query()
            ->where('id', '!=', $specialization->id)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim($data['name']))])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Specialization name already exists.',
            ], 422);
        }

        $specialization->update([
            'name' => trim($data['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Specialization updated successfully.',
        ]);
    }

    public function destroy(Specialization $specialization): JsonResponse
    {
        $isUsed = $specialization->doctorProfiles()->exists();

        if ($isUsed) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete specialization. It is currently assigned to one or more doctors.',
            ], 422);
        }

        $specialization->delete();

        return response()->json([
            'success' => true,
            'message' => 'Specialization deleted successfully.',
        ]);
    }
}
