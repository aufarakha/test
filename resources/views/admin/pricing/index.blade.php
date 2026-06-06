@extends('admin.layout')

@section('title', 'Konfigurasi Harga')

@section('content')
<h1 class="mb-4">Konfigurasi Harga Kuota</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Setting Harga</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pricing.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Harga 1x Tryout (Kuota):</label>
                        <input type="number" name="tryout_quota_cost" class="form-control" value="{{ $pricing->tryout_quota_cost }}" min="1" required>
                        <small class="text-muted">Berapa kuota yang dibutuhkan untuk 1x mengerjakan tryout</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga 1x Lihat Jawaban (Kuota):</label>
                        <input type="number" name="view_answer_quota_cost" class="form-control" value="{{ $pricing->view_answer_quota_cost }}" min="1" required>
                        <small class="text-muted">Berapa kuota yang dibutuhkan untuk 1x melihat jawaban tanpa mengerjakan</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <h6><i class="fas fa-info-circle"></i> Cara Kerja:</h6>
                <ul>
                    <li>Setiap user harus memiliki kuota untuk mengerjakan tryout</li>
                    <li>Ketika user mengerjakan tryout, kuota akan berkurang sesuai <strong>Harga Tryout</strong></li>
                    <li>Ketika user hanya ingin melihat jawaban (tanpa mengerjakan), kuota akan berkurang sesuai <strong>Harga Lihat Jawaban</strong></li>
                    <li>User tidak bisa mengerjakan tryout atau melihat jawaban jika kuota tidak mencukupi</li>
                </ul>

                <hr>

                <h6><i class="fas fa-chart-line"></i> Konfigurasi Saat Ini:</h6>
                <table class="table">
                    <tr>
                        <td>1x Tryout</td>
                        <td class="text-end"><strong>{{ $pricing->tryout_quota_cost }} kuota</strong></td>
                    </tr>
                    <tr>
                        <td>1x Lihat Jawaban</td>
                        <td class="text-end"><strong>{{ $pricing->view_answer_quota_cost }} kuota</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
