<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TryoutQuota;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function edit($userId)
    {
        $user = User::with('tryoutQuota')->findOrFail($userId);
        
        return view('admin.quota.edit', compact('user'));
    }

    public function update(Request $request, $userId)
    {
        $request->validate([
            'quota' => 'required|integer|min:0',
        ]);

        $user = User::findOrFail($userId);
        
        $quota = $user->tryoutQuota;
        if (!$quota) {
            $quota = TryoutQuota::create([
                'user_id' => $user->id,
                'quota' => 0,
            ]);
        }

        if ($request->action === 'set') {
            $quota->update(['quota' => $request->quota]);
        } elseif ($request->action === 'add') {
            $quota->increment('quota', $request->quota);
        }

        return redirect()->route('admin.users.show', $userId)
            ->with('success', 'Kuota berhasil diupdate.');
    }
}
