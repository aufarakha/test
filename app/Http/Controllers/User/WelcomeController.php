<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Validate session
        if (!session('api_token') || !session('user_data')) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Refresh quota from database
        $user = \App\Models\User::with('tryoutQuota')->find(session('user_data.id'));
        if ($user && $user->tryoutQuota) {
            session(['user_data.quota' => $user->tryoutQuota->quota]);
        }

        return view('user.welcome');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        
        if ($user) {
            $deviceId = md5($request->userAgent() . $request->ip());
            \App\Models\UserSession::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->delete();
        }

        auth()->logout();
        session()->flush();
        
        // Regenerate CSRF token to prevent 419 error
        $request->session()->regenerateToken();

        return redirect()->route('user.login')
            ->with('success', 'Logout berhasil')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * User Profile Page
     */
    public function profile(Request $request)
    {
        // Validate session
        if (!session('api_token') || !session('user_data')) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = \App\Models\User::with(['sessions', 'tryoutQuota', 'examResults'])
            ->find(session('user_data.id'));

        if (!$user) {
            return redirect()->route('user.login')->with('error', 'User tidak ditemukan');
        }

        return view('user.profile', compact('user'));
    }

    /**
     * Delete user session (logout device)
     */
    public function deleteSession(Request $request, $sessionId)
    {
        if (!session('user_data')) {
            return back()->with('error', 'Sesi tidak valid');
        }

        $session = \App\Models\UserSession::where('id', $sessionId)
            ->where('user_id', session('user_data.id'))
            ->first();

        if (!$session) {
            return back()->with('error', 'Sesi tidak ditemukan');
        }

        $session->delete();

        return back()->with('success', 'Device berhasil di-logout');
    }

    /**
     * Exam Dashboard - validates session and redirects to appropriate page
     */
    public function examDashboard(Request $request)
    {
        // Validate user session
        if (!session('api_token') || !session('user_data')) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('user.exam-dashboard');
    }

    /**
     * Exam Opening Page
     */
    public function examOpening(Request $request)
    {
        if (!session('api_token') || !session('user_data')) {
            return redirect()->route('user.login')->with('error', 'Sesi tidak valid');
        }

        // Check if opening2.html exists, otherwise use opening.html
        return view('user.exam-opening');
    }

    /**
     * Exam Test Page - Main exam interface
     */
    public function examTest(Request $request)
    {
        if (!session('api_token') || !session('user_data')) {
            return redirect()->route('user.login')->with('error', 'Sesi tidak valid');
        }

        return view('user.exam-test');
    }

    /**
     * Exam Submit Page - Submit answers
     */
    public function examSubmit(Request $request)
    {
        if (!session('api_token') || !session('user_data')) {
            return redirect()->route('user.login')->with('error', 'Sesi tidak valid');
        }

        return view('user.exam-submit');
    }

    /**
     * Exam Review - View submitted answers
     */
    public function examReview(Request $request, $resultId)
    {
        if (!session('api_token') || !session('user_data')) {
            return redirect()->route('user.login')->with('error', 'Sesi tidak valid');
        }

        $result = \App\Models\ExamResult::findOrFail($resultId);
        
        // Verify user owns this result
        if ($result->user_id !== session('user_data.id')) {
            abort(403, 'Unauthorized');
        }

        // Check if review is locked
        if ($result->is_locked) {
            return view('user.exam-review-locked', compact('result'));
        }

        // Get questions data
        $questions = \App\Models\Question::all()->keyBy('question_id');

        return view('user.exam-review', compact('result', 'questions'));
    }
}
