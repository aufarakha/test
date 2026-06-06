<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($std_code)
    {
        $user = User::where('std_code', $std_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'std_code' => $user->std_code,
                'std_name' => $user->std_name,
                'std_nisn' => $user->std_nisn,
                'std_gender' => $user->std_gender,
                'std_dob' => $user->std_dob?->format('Y-m-d'),
                'std_npsn' => $user->std_npsn,
                'sch_code' => $user->sch_code,
                'std_school' => $user->std_school,
                'std_class' => $user->std_class,
                'std_email' => $user->std_email,
                'std_phone' => $user->std_phone,
                'kompetensi_keahlian' => $user->kompetensi_keahlian,
                'program_keahlian' => $user->program_keahlian,
                'bidang_keahlian' => $user->bidang_keahlian,
            ],
        ]);
    }

    public function update(Request $request, $std_code)
    {
        $user = User::where('std_code', $std_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'std_name' => 'sometimes|string',
            'std_gender' => 'sometimes|string',
            'std_dob' => 'sometimes|date',
            'std_school' => 'sometimes|string',
            'std_class' => 'sometimes|string',
            'std_email' => 'sometimes|email',
            'std_phone' => 'sometimes|string',
            'kompetensi_keahlian' => 'sometimes|string',
            'program_keahlian' => 'sometimes|string',
            'bidang_keahlian' => 'sometimes|string',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
            'data' => $user,
        ]);
    }
}
