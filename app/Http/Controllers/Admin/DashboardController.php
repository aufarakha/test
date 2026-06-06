<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExamResult;
use App\Models\TryoutPricing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalExams = ExamResult::where('is_view_only', false)->count();
        $totalViews = ExamResult::where('is_view_only', true)->count();
        $pricing = TryoutPricing::first();

        $recentExams = ExamResult::with('user')
            ->where('is_view_only', false)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalExams',
            'totalViews',
            'pricing',
            'recentExams'
        ));
    }
}
