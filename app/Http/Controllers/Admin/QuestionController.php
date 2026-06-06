<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::query();

        if ($request->search) {
            $query->where('question_id', 'like', "%{$request->search}%")
                  ->orWhere('question', 'like', "%{$request->search}%");
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter questions without answer keys
        if ($request->missing_answer == '1') {
            $query->where(function($q) {
                $q->whereNull('answer')
                  ->orWhere('answer', '');
            });
        }

        $questions = $query->orderBy('question_id')->paginate(50);

        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|string|unique:questions,question_id',
            'type' => 'required|in:listening,reading',
            'question' => 'required|string',
            'options' => 'nullable|json',
            'answer' => 'required|string',
            'score' => 'required|integer|min:1',
            'audio_url' => 'nullable|string',
        ]);

        // Decode JSON options
        if ($validated['options']) {
            $validated['options'] = json_decode($validated['options'], true);
        }

        Question::create($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $validated = $request->validate([
            'question_id' => 'required|string|unique:questions,question_id,' . $id,
            'type' => 'required|in:listening,reading',
            'question' => 'required|string',
            'options' => 'nullable|json',
            'answer' => 'required|string',
            'score' => 'required|integer|min:1',
            'audio_url' => 'nullable|string',
        ]);

        // Decode JSON options
        if ($validated['options']) {
            $validated['options'] = json_decode($validated['options'], true);
        }

        $question->update($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question berhasil diupdate.');
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question berhasil dihapus.');
    }

    public function bulkUpdateAnswers(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.id' => 'required|exists:questions,id',
            'answers.*.answer' => 'required|string',
        ]);

        foreach ($request->answers as $answer) {
            Question::where('id', $answer['id'])
                ->update(['answer' => $answer['answer']]);
        }

        return redirect()->back()
            ->with('success', count($request->answers) . ' answer keys berhasil diupdate.');
    }

    public function uploadJson(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
            'with_answers' => 'nullable|boolean',
        ]);

        $file = $request->file('json_file');
        $jsonContent = file_get_contents($file->getRealPath());
        $questions = json_decode($jsonContent, true);

        if (!$questions || !is_array($questions)) {
            return redirect()->back()->with('error', 'Format JSON tidak valid.');
        }

        $imported = 0;
        $updated = 0;

        foreach ($questions as $questionData) {
            // Skip direction questions
            if (isset($questionData['type']) && $questionData['type'] === 'direction') {
                continue;
            }

            $data = [
                'question_id' => $questionData['id'] ?? null,
                'type' => $questionData['type'] ?? 'reading',
                'question' => $questionData['question'] ?? '',
                'options' => isset($questionData['options']) ? $questionData['options'] : null,
                'score' => $questionData['score'] ?? 1,
                'audio_url' => $questionData['audio'] ?? null,
            ];

            // Only add answer if checkbox is checked AND answer exists
            if ($request->with_answers && isset($questionData['answer'])) {
                $data['answer'] = $questionData['answer'];
            } else {
                $data['answer'] = '';
            }

            $existing = Question::where('question_id', $data['question_id'])->first();

            if ($existing) {
                $existing->update($data);
                $updated++;
            } else {
                Question::create($data);
                $imported++;
            }
        }

        return redirect()->route('admin.questions.index')
            ->with('success', "Import berhasil! {$imported} soal baru ditambahkan, {$updated} soal diupdate.");
    }
}
