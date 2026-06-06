<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TryoutPricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $pricing = TryoutPricing::first();
        
        if (!$pricing) {
            $pricing = TryoutPricing::create([
                'tryout_quota_cost' => 1,
                'view_answer_quota_cost' => 1,
            ]);
        }

        return view('admin.pricing.index', compact('pricing'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tryout_quota_cost' => 'required|integer|min:1',
            'view_answer_quota_cost' => 'required|integer|min:1',
        ]);

        $pricing = TryoutPricing::first();
        
        if (!$pricing) {
            $pricing = TryoutPricing::create($request->only([
                'tryout_quota_cost',
                'view_answer_quota_cost'
            ]));
        } else {
            $pricing->update($request->only([
                'tryout_quota_cost',
                'view_answer_quota_cost'
            ]));
        }

        return redirect()->back()->with('success', 'Pricing berhasil diupdate.');
    }
}
