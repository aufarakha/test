@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit User: {{ $user->std_name }}</h1>
    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-user"></i> Informasi Login</h5>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-id-card"></i> Data Identitas</h5>
                    
                    <div class="mb-3">
                        <label for="std_code" class="form-label">STD Code (NISN) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('std_code') is-invalid @enderror" 
                               id="std_code" name="std_code" value="{{ old('std_code', $user->std_code) }}" required>
                        @error('std_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="std_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('std_name') is-invalid @enderror" 
                               id="std_name" name="std_name" value="{{ old('std_name', $user->std_name) }}" required>
                        @error('std_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="std_nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('std_nisn') is-invalid @enderror" 
                               id="std_nisn" name="std_nisn" value="{{ old('std_nisn', $user->std_nisn) }}" required>
                        @error('std_nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="std_npsn" class="form-label">NPSN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('std_npsn') is-invalid @enderror" 
                               id="std_npsn" name="std_npsn" value="{{ old('std_npsn', $user->std_npsn) }}" required>
                        @error('std_npsn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="std_gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('std_gender') is-invalid @enderror" 
                                    id="std_gender" name="std_gender">
                                <option value="">Pilih</option>
                                <option value="L" {{ old('std_gender', $user->std_gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('std_gender', $user->std_gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('std_gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="std_dob" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('std_dob') is-invalid @enderror" 
                                   id="std_dob" name="std_dob" value="{{ old('std_dob', $user->std_dob?->format('Y-m-d')) }}">
                            @error('std_dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-school"></i> Data Sekolah</h5>
                    
                    <div class="mb-3">
                        <label for="sch_code" class="form-label">Kode Sekolah</label>
                        <input type="text" class="form-control @error('sch_code') is-invalid @enderror" 
                               id="sch_code" name="sch_code" value="{{ old('sch_code', $user->sch_code) }}">
                        @error('sch_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="std_school" class="form-label">Nama Sekolah</label>
                        <input type="text" class="form-control @error('std_school') is-invalid @enderror" 
                               id="std_school" name="std_school" value="{{ old('std_school', $user->std_school) }}">
                        @error('std_school')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="std_class" class="form-label">Kelas</label>
                        <input type="text" class="form-control @error('std_class') is-invalid @enderror" 
                               id="std_class" name="std_class" value="{{ old('std_class', $user->std_class) }}">
                        @error('std_class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-address-book"></i> Kontak & Kompetensi</h5>
                    
                    <div class="mb-3">
                        <label for="std_email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('std_email') is-invalid @enderror" 
                               id="std_email" name="std_email" value="{{ old('std_email', $user->std_email) }}">
                        @error('std_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="std_phone" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control @error('std_phone') is-invalid @enderror" 
                               id="std_phone" name="std_phone" value="{{ old('std_phone', $user->std_phone) }}">
                        @error('std_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kompetensi_keahlian" class="form-label">Kompetensi Keahlian</label>
                        <input type="text" class="form-control @error('kompetensi_keahlian') is-invalid @enderror" 
                               id="kompetensi_keahlian" name="kompetensi_keahlian" value="{{ old('kompetensi_keahlian', $user->kompetensi_keahlian) }}">
                        @error('kompetensi_keahlian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="program_keahlian" class="form-label">Program Keahlian</label>
                        <input type="text" class="form-control @error('program_keahlian') is-invalid @enderror" 
                               id="program_keahlian" name="program_keahlian" value="{{ old('program_keahlian', $user->program_keahlian) }}">
                        @error('program_keahlian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
                        <input type="text" class="form-control @error('bidang_keahlian') is-invalid @enderror" 
                               id="bidang_keahlian" name="bidang_keahlian" value="{{ old('bidang_keahlian', $user->bidang_keahlian) }}">
                        @error('bidang_keahlian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
