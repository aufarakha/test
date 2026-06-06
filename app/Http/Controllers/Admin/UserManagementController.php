<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use App\Models\TryoutQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['tryoutQuota', 'sessions']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                  ->orWhere('std_name', 'like', "%{$request->search}%")
                  ->orWhere('std_code', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'initial_quota' => 'required|integer|min:0',
        ]);

        // Auto generate data
        $stdCode = 'STD' . time() . rand(100, 999);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['std_code'] = $stdCode;
        $validated['std_name'] = $validated['username'];
        $validated['std_nisn'] = $stdCode;
        $validated['std_npsn'] = 'NPSN' . rand(10000000, 99999999);
        
        $initialQuota = $validated['initial_quota'];
        unset($validated['initial_quota']);

        $user = User::create($validated);

        // Create quota
        TryoutQuota::create([
            'user_id' => $user->id,
            'quota' => $initialQuota,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::with(['tryoutQuota', 'sessions', 'examResults'])->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'std_code' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'std_name' => 'required|string',
            'std_nisn' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'std_npsn' => 'required|string',
            'std_gender' => 'nullable|string',
            'std_dob' => 'nullable|date',
            'sch_code' => 'nullable|string',
            'std_school' => 'nullable|string',
            'std_class' => 'nullable|string',
            'std_email' => 'nullable|email',
            'std_phone' => 'nullable|string',
            'kompetensi_keahlian' => 'nullable|string',
            'program_keahlian' => 'nullable|string',
            'bidang_keahlian' => 'nullable|string',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Delete all related data
        $user->sessions()->delete();
        $user->tryoutQuota()->delete();
        $user->examResults()->delete();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => true]);

        // Delete all sessions
        $user->sessions()->delete();

        return redirect()->back()->with('success', 'User berhasil di-ban.');
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => false]);

        return redirect()->back()->with('success', 'User berhasil di-unban.');
    }

    public function deleteSession($userId, $sessionId)
    {
        UserSession::where('id', $sessionId)
            ->where('user_id', $userId)
            ->delete();

        return redirect()->back()->with('success', 'Sesi berhasil dihapus.');
    }
}
