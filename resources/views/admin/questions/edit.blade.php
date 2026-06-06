@extends('admin.layout')

@section('title', 'Edit Soal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Soal: {{ $question->question_id }}</h1>
    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="question_id" class="form-label">Question ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('question_id') is-invalid @enderror" 
                               id="question_id" name="question_id" value="{{ old('question_id', $question->question_id) }}" required>
                        @error('question_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="listening" {{ old('type', $question->type) == 'listening' ? 'selected' : '' }}>Listening</option>
                            <option value="reading" {{ old('type', $question->type) == 'reading' ? 'selected' : '' }}>Reading</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="score" class="form-label">Score <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('score') is-invalid @enderror" 
                               id="score" name="score" value="{{ old('score', $question->score) }}" min="1" required>
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="question" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                <textarea class="form-control @error('question') is-invalid @enderror" 
                          id="question" name="question" rows="3" required>{{ old('question', $question->question) }}</textarea>
                @error('question')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="options" class="form-label">Options (JSON Array)</label>
                <textarea class="form-control @error('options') is-invalid @enderror font-monospace" 
                          id="options" name="options" rows="4" placeholder='["A) Option 1", "B) Option 2", "C) Option 3", "D) Option 4"]'>{{ old('options', json_encode($question->options, JSON_PRETTY_PRINT)) }}</textarea>
                @error('options')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Format: JSON array. Contoh: ["A) Option 1", "B) Option 2"]</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('answer') is-invalid @enderror" 
                               id="answer" name="answer" value="{{ old('answer', $question->answer) }}" 
                               placeholder="Contoh: A, B, C, atau D" required>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jawaban yang benar (contoh: A, B, C, D, atau A), B), dll)</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="audio_url" class="form-label">Audio URL</label>
                        <input type="text" class="form-control @error('audio_url') is-invalid @enderror" 
                               id="audio_url" name="audio_url" value="{{ old('audio_url', $question->audio_url) }}" 
                               placeholder="../assets/audio/1.mp3">
                        @error('audio_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hanya untuk soal listening</small>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Soal
                </button>
                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
