@extends('admin.layout')

@section('title', 'Manajemen Soal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Soal</h1>
    <div>
        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#uploadJsonModal">
            <i class="fas fa-upload"></i> Upload JSON
        </button>
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Soal
        </a>
    </div>
</div>

<!-- Modal Upload JSON -->
<div class="modal fade" id="uploadJsonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.questions.uploadJson') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Questions dari JSON</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="json_file" class="form-label">File JSON <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="json_file" name="json_file" accept=".json" required>
                        <small class="text-muted">Upload file test.json dari viera_tomigrate</small>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="with_answers" name="with_answers" value="1">
                        <label class="form-check-label" for="with_answers">
                            Import dengan Answer Keys
                        </label>
                        <small class="text-muted d-block">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            Centang jika JSON sudah berisi answer keys. Jika tidak, answer keys harus diisi manual.
                        </small>
                    </div>

                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            <strong>Format JSON:</strong><br>
                            File harus array of objects dengan struktur:<br>
                            <code>{"id":"q1", "type":"listening", "question":"...", "options":[], "answer":"A", "score":1}</code>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('admin.questions.index') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Cari question_id atau pertanyaan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="type">
                        <option value="">Semua Type</option>
                        <option value="listening" {{ request('type') == 'listening' ? 'selected' : '' }}>Listening</option>
                        <option value="reading" {{ request('type') == 'reading' ? 'selected' : '' }}>Reading</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="missing_answer" value="1" id="missing_answer" {{ request('missing_answer') ? 'checked' : '' }}>
                        <label class="form-check-label" for="missing_answer">
                            Hanya yang belum ada answer key
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 80px;">Type</th>
                        <th>Question</th>
                        <th style="width: 100px;">Answer</th>
                        <th style="width: 60px;">Score</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $question)
                        <tr>
                            <td><code>{{ $question->question_id }}</code></td>
                            <td>
                                <span class="badge {{ $question->type == 'listening' ? 'bg-info' : 'bg-success' }}">
                                    {{ ucfirst($question->type) }}
                                </span>
                            </td>
                            <td>
                                <small>{{ Str::limit($question->question, 80) }}</small>
                            </td>
                            <td>
                                @if($question->answer)
                                    <strong class="text-success">{{ $question->answer }}</strong>
                                @else
                                    <span class="badge bg-danger">Belum diisi</span>
                                @endif
                            </td>
                            <td>{{ $question->score }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" title="Hapus" 
                                            onclick="confirmDelete({{ $question->id }}, '{{ $question->question_id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $question->id }}" 
                                      action="{{ route('admin.questions.destroy', $question->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data soal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    Total: {{ $questions->total() }} soal
                </small>
            </div>
            <div>
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(questionId, questionCode) {
    if (confirm(`Apakah Anda yakin ingin menghapus soal "${questionCode}"?`)) {
        document.getElementById('delete-form-' + questionId).submit();
    }
}
</script>
@endpush
