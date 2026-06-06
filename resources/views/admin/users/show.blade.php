@extends('admin.layout')

@section('title', 'Detail User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detail User: {{ $user->std_name }}</h1>
    <div>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
            <i class="fas fa-trash"></i> Hapus
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi User</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Username:</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Nama:</th>
                        <td>{{ $user->std_name }}</td>
                    </tr>
                    <tr>
                        <th>STD Code:</th>
                        <td>{{ $user->std_code }}</td>
                    </tr>
                    <tr>
                        <th>NISN:</th>
                        <td>{{ $user->std_nisn }}</td>
                    </tr>
                    <tr>
                        <th>Sekolah:</th>
                        <td>{{ $user->std_school }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $user->std_email }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($user->is_banned)
                                <span class="badge bg-danger">Banned</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="mt-3">
                    @if($user->is_banned)
                        <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Unban user ini?')">
                                <i class="fas fa-unlock"></i> Unban User
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Ban user ini?')">
                                <i class="fas fa-ban"></i> Ban User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Kuota Tryout</h5>
            </div>
            <div class="card-body">
                <h2 class="text-center">{{ $user->tryoutQuota->quota ?? 0 }} Kuota</h2>
                
                <form action="{{ route('admin.quota.update', $user->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Jumlah Kuota:</label>
                        <input type="number" name="quota" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Aksi:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" value="set" id="set" checked>
                            <label class="form-check-label" for="set">Set (Ganti)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" value="add" id="add">
                            <label class="form-check-label" for="add">Tambah</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Kuota</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Sesi Login Aktif</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Device Type</th>
                        <th>Device Name</th>
                        <th>Device ID</th>
                        <th>IP Address</th>
                        <th>Last Activity</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->sessions as $session)
                        <tr>
                            <td>
                                <span class="badge {{ $session->device_type == 'phone' ? 'bg-primary' : 'bg-info' }}">
                                    {{ $session->device_type }}
                                </span>
                            </td>
                            <td>{{ $session->device_name }}</td>
                            <td><code>{{ substr($session->device_id, 0, 16) }}...</code></td>
                            <td>{{ $session->ip_address }}</td>
                            <td>{{ $session->last_activity->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <form action="{{ route('admin.users.sessions.delete', [$user->id, $session->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Logout device ini?')">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada sesi aktif</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Ujian</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Listening</th>
                        <th>Reading</th>
                        <th>Total</th>
                        <th>Device</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->examResults as $exam)
                        <tr>
                            <td>{{ $exam->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $exam->listening_score }}</td>
                            <td>{{ $exam->reading_score }}</td>
                            <td><strong>{{ $exam->total_score }}</strong></td>
                            <td>{{ $exam->device }}</td>
                            <td>
                                @if($exam->is_view_only)
                                    <span class="badge bg-info">View Only</span>
                                @else
                                    <span class="badge bg-success">Full Exam</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat ujian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus user "{{ $user->username }}"?\n\nSemua data terkait (kuota, sesi, hasil ujian) akan ikut terhapus.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
