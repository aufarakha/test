@extends('admin.layout')

@section('title', 'Tambah User Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Tambah User Baru</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" value="{{ old('username') }}" required autofocus>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Username untuk login</small>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="initial_quota" class="form-label">Kuota Awal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('initial_quota') is-invalid @enderror" 
                               id="initial_quota" name="initial_quota" value="{{ old('initial_quota', 5) }}" min="0" required>
                        @error('initial_quota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jumlah kuota tryout yang diberikan</small>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            <strong>Informasi:</strong><br>
                            Data lainnya (STD Code, NISN, NPSN) akan dibuat otomatis oleh sistem.
                            Anda dapat mengeditnya nanti jika diperlukan.
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h5>
            </div>
            <div class="card-body">
                <h6>Quick Add User</h6>
                <p>Form ini disederhanakan untuk menambah user dengan cepat. Anda hanya perlu mengisi:</p>
                <ul>
                    <li><strong>Username</strong> - Untuk login</li>
                    <li><strong>Password</strong> - Minimal 6 karakter</li>
                    <li><strong>Kuota</strong> - Berapa kali user bisa tryout</li>
                </ul>
                
                <hr>
                
                <h6>Data Auto-Generated:</h6>
                <ul class="mb-0">
                    <li>STD Code (NISN)</li>
                    <li>NPSN</li>
                    <li>Nama siswa (sama dengan username)</li>
                </ul>
                
                <div class="alert alert-warning mt-3">
                    <small>
                        <i class="fas fa-exclamation-triangle"></i>
                        Setelah user dibuat, Anda dapat mengedit data lengkapnya melalui tombol Edit.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
