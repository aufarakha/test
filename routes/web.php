<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\QuotaController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\User\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('user.login');
});

// User Routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', function () {
        return view('user.login');
    })->name('login');
    
    // Web-based login (creates session)
    Route::post('/login', function (Illuminate\Http\Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('username', $request->username)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username atau password salah.');
        }

        if ($user->is_banned) {
            return back()->with('error', 'Akun Anda telah diblokir.');
        }

        // Create session tracking
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($request->userAgent());
        
        $deviceType = $agent->isMobile() || $agent->isTablet() ? 'phone' : 'laptop';
        $deviceName = $agent->platform() . ' - ' . $agent->browser();
        $deviceId = md5($request->userAgent() . $request->ip());

        $existingSessions = $user->sessions()->where('device_type', $deviceType)->get();

        if ($existingSessions->count() >= 1) {
            $existingSession = $existingSessions->first();
            
            if ($existingSession->device_id !== $deviceId) {
                return back()->with('error', "Anda sudah login di device {$deviceType} lain.");
            }
            
            $existingSession->update([
                'last_activity' => now(),
                'ip_address' => $request->ip(),
            ]);
        } else {
            \App\Models\UserSession::create([
                'user_id' => $user->id,
                'device_type' => $deviceType,
                'device_name' => $deviceName,
                'device_id' => $deviceId,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'last_activity' => now(),
            ]);
        }

        // Get quota
        $quota = $user->tryoutQuota;
        if (!$quota) {
            $quota = \App\Models\TryoutQuota::create([
                'user_id' => $user->id,
                'quota' => 0,
            ]);
        }

        // Login user & create session
        auth()->login($user);
        
        $apiToken = bin2hex(random_bytes(32));
        
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

        return redirect()->route('user.welcome');
    })->name('login.post');
    
    Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');
    Route::get('/profile', [WelcomeController::class, 'profile'])->name('profile');
    Route::post('/logout', [WelcomeController::class, 'logout'])->name('logout');
    Route::delete('/sessions/{sessionId}', [WelcomeController::class, 'deleteSession'])->name('sessions.delete');
    
    // Exam routes - protected by session token validation
    Route::middleware(['web'])->group(function () {
        Route::get('/exam', [WelcomeController::class, 'examDashboard'])->name('exam.dashboard');
        Route::get('/exam/opening', [WelcomeController::class, 'examOpening'])->name('exam.opening');
        Route::get('/exam/test', [WelcomeController::class, 'examTest'])->name('exam.test');
        Route::get('/exam/submit', [WelcomeController::class, 'examSubmit'])->name('exam.submit');
        Route::get('/exam/review/{resultId}', [WelcomeController::class, 'examReview'])->name('exam.review');
    });
});

// Admin Authentication
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/ban', [UserManagementController::class, 'ban'])->name('users.ban');
        Route::post('/users/{id}/unban', [UserManagementController::class, 'unban'])->name('users.unban');
        Route::delete('/users/{userId}/sessions/{sessionId}', [UserManagementController::class, 'deleteSession'])->name('users.sessions.delete');
        
        // Quota Management
        Route::get('/users/{userId}/quota/edit', [QuotaController::class, 'edit'])->name('quota.edit');
        Route::post('/users/{userId}/quota', [QuotaController::class, 'update'])->name('quota.update');
        
        // Pricing Configuration
        Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
        Route::post('/pricing', [PricingController::class, 'update'])->name('pricing.update');
        
        // Pricing Configuration
        Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
        Route::post('/pricing', [PricingController::class, 'update'])->name('pricing.update');
    });
});

