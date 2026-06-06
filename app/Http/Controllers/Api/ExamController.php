<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\TryoutPricing;
use App\Models\TryoutQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'std_code' => 'required|string',
        ]);

        $user = User::where('std_code', $request->std_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // Check if user has quota
        $pricing = TryoutPricing::first();
        $quotaCost = $pricing->tryout_quota_cost ?? 1;

        $quota = $user->tryoutQuota;
        if (!$quota || $quota->quota < $quotaCost) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota tryout Anda tidak mencukupi.',
            ], 403);
        }

        // Create locked exam result placeholder
        $examResult = ExamResult::create([
            'user_id' => $user->id,
            'full_name' => $user->std_name,
            'std_code' => $user->std_code,
            'sch_code' => $user->sch_code,
            'listening_score' => 0,
            'reading_score' => 0,
            'total_score' => 0,
            'jawaban_peserta' => [],
            'device' => $request->device ?? 'unknown',
            'is_view_only' => false,
            'is_locked' => true, // Locked until exam is submitted
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Exam started successfully',
            'data' => [
                'exam_result_id' => $examResult->id,
            ],
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'exam_result_id' => 'nullable|integer',
            'full_name' => 'required|string',
            'std_code' => 'required|string',
            'sch_code' => 'nullable|string',
            'jawaban_peserta' => 'required|array',
            'device' => 'nullable|string',
            'is_view_only' => 'boolean',
        ]);

        $user = User::where('std_code', $request->std_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // Get pricing configuration
        $pricing = TryoutPricing::first();
        $quotaCost = $request->is_view_only 
            ? ($pricing->view_answer_quota_cost ?? 1)
            : ($pricing->tryout_quota_cost ?? 1);

        // Check quota
        $quota = $user->tryoutQuota;
        if (!$quota || $quota->quota < $quotaCost) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota tryout Anda tidak mencukupi.',
            ], 403);
        }

        // Jika hanya view, kurangi kuota dan return hasil sebelumnya
        if ($request->is_view_only) {
            DB::transaction(function () use ($quota, $quotaCost) {
                $quota->decrement('quota', $quotaCost);
            });

            $lastResult = $user->examResults()->where('is_view_only', false)->latest()->first();

            return response()->json([
                'success' => true,
                'message' => 'Akses jawaban berhasil.',
                'data' => [
                    'listening_score' => $lastResult->listening_score ?? 0,
                    'reading_score' => $lastResult->reading_score ?? 0,
                    'total_score' => $lastResult->total_score ?? 0,
                    'jawaban_peserta' => $lastResult->jawaban_peserta ?? [],
                    'quota_remaining' => $quota->quota - $quotaCost,
                ],
            ]);
        }

        // SKIP SCORING - Just save user answers without calculation
        $listeningScore = 0;
        $readingScore = 0;
        $totalScore = 0;

        // Try to find and update existing locked exam result
        $examResult = null;
        if ($request->exam_result_id) {
            $examResult = ExamResult::where('id', $request->exam_result_id)
                ->where('user_id', $user->id)
                ->where('is_locked', true)
                ->first();
            
            \Log::info('Looking for locked exam result', [
                'exam_result_id' => $request->exam_result_id,
                'user_id' => $user->id,
                'found' => $examResult ? 'yes' : 'no'
            ]);
        }

        // Update exam result and KEEP IT LOCKED, then deduct quota
        if ($examResult) {
            // Update existing locked result
            \Log::info('Updating existing locked result', ['result_id' => $examResult->id]);
            
            DB::transaction(function () use ($examResult, $request, $listeningScore, $readingScore, $totalScore, $quota, $quotaCost) {
                $examResult->update([
                    'listening_score' => $listeningScore,
                    'reading_score' => $readingScore,
                    'total_score' => $totalScore,
                    'jawaban_peserta' => $request->jawaban_peserta,
                    'device' => $request->device,
                    'is_locked' => true, // KEEP LOCKED - user must pay to unlock
                ]);

                $quota->decrement('quota', $quotaCost);
            });
        } else {
            // Fallback: Create new LOCKED result
            \Log::info('Creating new result (fallback mode)', [
                'user_id' => $user->id,
                'answers_count' => count($request->jawaban_peserta)
            ]);
            
            DB::transaction(function () use ($user, $request, $listeningScore, $readingScore, $totalScore, $quota, $quotaCost) {
                ExamResult::create([
                    'user_id' => $user->id,
                    'full_name' => $request->full_name,
                    'std_code' => $request->std_code,
                    'sch_code' => $request->sch_code,
                    'listening_score' => $listeningScore,
                    'reading_score' => $readingScore,
                    'total_score' => $totalScore,
                    'jawaban_peserta' => $request->jawaban_peserta,
                    'device' => $request->device,
                    'is_view_only' => false,
                    'is_locked' => true, // LOCKED - user must pay to unlock
                ]);

                $quota->decrement('quota', $quotaCost);
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Skor berhasil disimpan',
            'data' => [
                'listening_score' => $listeningScore,
                'reading_score' => $readingScore,
                'total_score' => $totalScore,
                'quota_remaining' => $quota->quota - $quotaCost,
            ],
        ]);
    }

    public function unlockReview(Request $request)
    {
        $request->validate([
            'exam_result_id' => 'required|integer',
            'std_code' => 'required|string',
        ]);

        $user = User::where('std_code', $request->std_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // Get the locked exam result
        $examResult = ExamResult::where('id', $request->exam_result_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$examResult) {
            return response()->json([
                'success' => false,
                'message' => 'Exam result tidak ditemukan.',
            ], 404);
        }

        // Check if already unlocked
        if (!$examResult->is_locked) {
            return response()->json([
                'success' => true,
                'message' => 'Review sudah terbuka.',
            ]);
        }

        // Get pricing configuration
        $pricing = TryoutPricing::first();
        $quotaCost = $pricing->view_answer_quota_cost ?? 1;

        // Check quota
        $quota = $user->tryoutQuota;
        if (!$quota || $quota->quota < $quotaCost) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota Anda tidak mencukupi untuk membuka review.',
                'quota_needed' => $quotaCost,
                'quota_available' => $quota ? $quota->quota : 0,
            ], 403);
        }

        // Unlock review and deduct quota
        DB::transaction(function () use ($examResult, $quota, $quotaCost) {
            $examResult->update(['is_locked' => false]);
            $quota->decrement('quota', $quotaCost);
        });

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dibuka!',
            'quota_remaining' => $quota->quota - $quotaCost,
        ]);
    }

    public function getQuestions()
    {
        // Return questions WITHOUT answers untuk security
        $questions = Question::all(['id', 'question_id', 'type', 'question', 'options', 'audio_url']);

        return response()->json([
            'success' => true,
            'data' => $questions,
        ]);
    }
}
