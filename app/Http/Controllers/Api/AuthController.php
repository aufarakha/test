<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use App\Models\TryoutQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal. Username atau password salah.',
            ], 401);
        }

        if ($user->is_banned) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda telah diblokir.',
            ], 403);
        }

        // Deteksi device type dari user agent
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        
        $deviceType = $agent->isMobile() || $agent->isTablet() ? 'phone' : 'laptop';
        $deviceName = $agent->platform() . ' - ' . $agent->browser();
        $deviceId = md5($request->userAgent() . $request->ip());

        // Cek sesi yang sudah ada
        $existingSessions = $user->sessions()->where('device_type', $deviceType)->get();

        if ($existingSessions->count() >= 1) {
            // Sudah ada 1 sesi dengan device type yang sama
            $existingSession = $existingSessions->first();
            
            if ($existingSession->device_id !== $deviceId) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda sudah login di device {$deviceType} lain. Maksimal 1 {$deviceType} dan 1 laptop per akun.",
                ], 403);
            }
            
            // Update sesi yang sudah ada
            $existingSession->update([
                'last_activity' => now(),
                'ip_address' => $request->ip(),
            ]);
        } else {
            // Buat sesi baru
            UserSession::create([
                'user_id' => $user->id,
                'device_type' => $deviceType,
                'device_name' => $deviceName,
                'device_id' => $deviceId,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'last_activity' => now(),
            ]);
        }

        // Ambil atau buat quota
        $quota = $user->tryoutQuota;
        if (!$quota) {
            $quota = TryoutQuota::create([
                'user_id' => $user->id,
                'quota' => 0,
            ]);
        }

        // Login user dan create session
        auth()->login($user);
        
        // Generate API token untuk exam pages
        $apiToken = bin2hex(random_bytes(32));
        
        // Store dalam session untuk web routes
        session([
            'api_token' => $apiToken,
            'user_data' => [
                'id' => $user->id,
                'username' => $user->username,
                'std_code' => $user->std_code,
                'std_name' => $user->std_name,
                'sch_code' => $user->sch_code,
                'quota' => $quota->quota,
            ]
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'std_code' => $user->std_code,
                'std_name' => $user->std_name,
                'sch_code' => $user->sch_code,
                'quota' => $quota->quota,
                'api_token' => $apiToken,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        
        if ($user) {
            $deviceId = md5($request->userAgent() . $request->ip());
            UserSession::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->delete();
        }

        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}
