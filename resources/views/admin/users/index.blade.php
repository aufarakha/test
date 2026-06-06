@extends('admin.layout')

@section('title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen User</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah User
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Cari username, nama, atau std_code..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>STD Code</th>
                        <th>Sekolah</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Sesi Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->std_name }}</td>
                            <td>{{ $user->std_code }}</td>
                            <td>{{ $user->std_school ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $user->tryoutQuota->quota ?? 0 }}</span>
                            </td>
                            <td>
                                @if($user->is_banned)
                                    <span class="badge bg-danger">Banned</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ $user->sessions->count() }} device(s)</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus" 
                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->username }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $user->id }}" 
                                      action="{{ route('admin.users.destroy', $user->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(userId, username) {
    if (confirm(`Apakah Anda yakin ingin menghapus user "${username}"?\n\nSemua data terkait (kuota, sesi, hasil ujian) akan ikut terhapus.`)) {
        document.getElementById('delete-form-' + userId).submit();
    }
}
</script>
@endpush
