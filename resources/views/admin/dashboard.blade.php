@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="font-weight: 700; color: #111827; margin-bottom: 4px;">Dashboard</h2>
        <p style="color: #6b7280; margin: 0;">Ringkasan sistem Viera Tryout</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <p style="opacity: 0.9; margin: 0; font-size: 14px;">Total User</p>
                        <h2 style="font-weight: 700; margin: 8px 0 0;">{{ $totalUsers }}</h2>
                    </div>
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <p style="opacity: 0.9; margin: 0; font-size: 14px;">Total Ujian</p>
                        <h2 style="font-weight: 700; margin: 8px 0 0;">{{ $totalExams }}</h2>
                    </div>
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clipboard-list" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <p style="opacity: 0.9; margin: 0; font-size: 14px;">View Jawaban</p>
                        <h2 style="font-weight: 700; margin: 8px 0 0;">{{ $totalViews }}</h2>
                    </div>
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-eye" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <p style="opacity: 0.9; margin: 0; font-size: 14px;">Harga Tryout</p>
                        <h2 style="font-weight: 700; margin: 8px 0 0;">{{ $pricing->tryout_quota_cost ?? 1 }}</h2>
                    </div>
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-ticket-alt" style="font-size: 20px;"></i>
                    </div>
                </div>
                <p style="opacity: 0.9; margin: 0; font-size: 12px;">kuota per tryout</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0" style="padding: 20px 24px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 style="font-weight: 700; color: #111827; margin: 0;">Ujian Terbaru</h5>
                <p style="color: #6b7280; font-size: 13px; margin: 4px 0 0;">Daftar ujian yang baru saja dikerjakan</p>
            </div>
            <i class="fas fa-clipboard-list" style="color: var(--primary-color); font-size: 24px;"></i>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 14px;">
                <thead style="background: #f9fafb; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 12px 24px; font-weight: 600; color: #6b7280;">Nama</th>
                        <th style="padding: 12px 24px; font-weight: 600; color: #6b7280;">STD Code</th>
                        <th style="padding: 12px 24px; font-weight: 600; color: #6b7280; text-align: center;">Listening</th>
                        <th style="padding: 12px 24px; font-weight: 600; color: #6b7280; text-align: center;">Reading</th>
                        <th style="padding: 12px 24px; font-weight: 600; color: #6b7280; text-align: center;">Total</th>
                        <th style="padding: 12px 24px; font-weight: 600; color: #6b7280;">Device</th>
                        <th style="padding: 12px 24px; font-weight: 600; color: #6b7280;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentExams as $exam)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 16px 24px; color: #111827; font-weight: 500;">{{ $exam->full_name }}</td>
                            <td style="padding: 16px 24px; color: #6b7280;">{{ $exam->std_code }}</td>
                            <td style="padding: 16px 24px; text-align: center;">
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 13px;">
                                    {{ $exam->listening_score }}
                                </span>
                            </td>
                            <td style="padding: 16px 24px; text-align: center;">
                                <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 13px;">
                                    {{ $exam->reading_score }}
                                </span>
                            </td>
                            <td style="padding: 16px 24px; text-align: center;">
                                <span style="background: #ecfdf5; color: #065f46; padding: 4px 12px; border-radius: 6px; font-weight: 700; font-size: 14px;">
                                    {{ $exam->total_score }}
                                </span>
                            </td>
                            <td style="padding: 16px 24px; color: #6b7280;">
                                <i class="fas fa-{{ strpos(strtolower($exam->device), 'android') !== false || strpos(strtolower($exam->device), 'ios') !== false ? 'mobile-alt' : 'laptop' }}" style="margin-right: 6px;"></i>
                                {{ $exam->device }}
                            </td>
                            <td style="padding: 16px 24px; color: #6b7280;">{{ $exam->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 48px 24px; text-align: center; color: #9ca3af;">
                                <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
                                Belum ada ujian yang dikerjakan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 12px;
        }
        .table td, .table th {
            padding: 12px 16px !important;
        }
        .card-body h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
