<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Beranda - Viera Tryout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #10b981;
            --sidebar-width: 240px;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f9fafb;
        }

        /* Mobile Menu Toggle */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 16px;
            z-index: 1001;
            align-items: center;
            justify-content: space-between;
        }

        .hamburger {
            width: 30px;
            height: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
        }

        .hamburger span {
            width: 100%;
            height: 3px;
            background: #111827;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .sidebar-logo i {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-nav {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #6b7280;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .nav-link:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .nav-link.active {
            background: #ecfdf5;
            color: var(--primary-color);
            font-weight: 600;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .user-profile {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            color: #111827;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            color: #6b7280;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }

        .quota-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .quota-value {
            font-size: 56px;
            font-weight: 700;
            margin: 10px 0;
        }

        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .result-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            background: white;
        }

        .score-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            margin-right: 8px;
        }

        .score-listening { background: #dbeafe; color: #1e40af; }
        .score-reading { background: #fef3c7; color: #92400e; }
        .score-total { background: #ecfdf5; color: #065f46; font-size: 16px; }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary-custom:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
        }

        .btn-secondary-custom {
            background: white;
            color: #6b7280;
            border: 1px solid var(--border-color);
        }

        .btn-secondary-custom:hover {
            background: #f9fafb;
            color: #374151;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 64px;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .tryout-card {
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 30px;
            background: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding: 76px 16px 16px;
            }

            .quota-card {
                padding: 24px;
            }

            .quota-value {
                font-size: 42px;
            }

            .card {
                padding: 16px;
            }

            .result-card {
                padding: 16px;
            }

            .score-badge {
                font-size: 11px;
                padding: 4px 8px;
                margin-bottom: 8px;
            }

            .score-total {
                font-size: 14px;
            }

            .btn-custom {
                padding: 8px 16px;
                font-size: 13px;
                width: 100%;
                margin-bottom: 8px;
            }

            .d-flex.gap-10 {
                flex-direction: column !important;
                gap: 0 !important;
            }

            .tryout-card {
                padding: 20px;
            }

            .empty-state {
                padding: 40px 20px;
            }

            .empty-icon {
                font-size: 48px;
            }
        }

        @media (max-width: 576px) {
            .quota-value {
                font-size: 36px;
            }

            h4 {
                font-size: 1.1rem;
            }

            h5 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    @if(!session('user_data'))
        <script>window.location.href = '/user/login';</script>
    @endif

    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="hamburger" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="sidebar-logo">
            <i class="fas fa-graduation-cap"></i>
            <span>Viera</span>
        </div>
        <div style="width: 30px;"></div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-graduation-cap"></i>
                <span>Viera</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('user.welcome') }}" class="nav-link active">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('user.profile') }}" class="nav-link">
                <i class="fas fa-user"></i>
                <span>Akun saya</span>
            </a>
        </nav>

        <div class="user-profile">
            <div class="user-avatar">
                {{ strtoupper(substr(session('user_data.std_name') ?? session('user_data.username'), 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ session('user_data.std_name') ?? session('user_data.username') }}</div>
                <div class="user-email">{{ session('user_data.username') }}</div>
            </div>
            <form action="{{ route('user.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #6b7280; cursor: pointer; padding: 0;" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Quota Card -->
        <div class="quota-card">
            <div style="font-size: 16px; opacity: 0.9;">Kuota Tryout Tersisa</div>
            <div class="quota-value">{{ session('user_data.quota') }}</div>
            <div style="font-size: 14px; opacity: 0.8;">Gunakan kuota untuk mengerjakan tryout</div>
        </div>

        @php
            $user = \App\Models\User::find(session('user_data.id'));
            $results = $user ? $user->examResults()->where('is_view_only', false)->latest()->get() : collect([]);
        @endphp

        <!-- Riwayat Tryout -->
        <div class="card">
            <h4 style="margin-bottom: 20px; font-weight: 700; color: #111827;">Riwayat Tryout</h4>
            
            @forelse($results as $result)
            <div class="result-card">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                    <div style="margin-bottom: 10px;">
                        <h5 style="font-weight: 700; margin-bottom: 5px; color: #111827;">Tryout Viera - English Test</h5>
                        <small style="color: #6b7280;">
                            <i class="fas fa-calendar"></i> {{ $result->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                </div>

                <div style="margin-bottom: 15px; color: #6b7280; font-size: 14px;">
                    <i class="fas fa-check-circle"></i> Tryout selesai dikerjakan
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @if($result->is_locked)
                        <a href="{{ route('user.exam.review', $result->id) }}" class="btn-custom btn-secondary-custom">
                            <i class="fas fa-lock"></i> Buka Review (Perlu Kuota)
                        </a>
                    @else
                        <a href="{{ route('user.exam.review', $result->id) }}" class="btn-custom btn-secondary-custom">
                            <i class="fas fa-unlock"></i> Lihat Jawaban
                        </a>
                    @endif
                    <a href="{{ route('user.exam.dashboard') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-redo"></i> Tryout Ulang
                    </a>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h5 style="color: #374151; margin-bottom: 8px;">Belum Ada Riwayat Tryout</h5>
                <p style="margin-bottom: 24px;">Mulai tryout pertama Anda sekarang!</p>
                <a href="{{ route('user.exam.dashboard') }}" class="btn-custom btn-primary-custom">
                    <i class="fas fa-play-circle"></i> Mulai Tryout
                </a>
            </div>
            @endforelse
        </div>

        <!-- Tryout Info (hanya tampil jika belum ada riwayat) -->
        @if($results->isNotEmpty())
        <div class="tryout-card">
            <div style="text-align: center;">
                <span class="badge bg-success mb-3" style="font-size: 14px;">English Test</span>
                <h4 style="font-weight: 700; margin-bottom: 16px; color: #111827;">Tryout Viera</h4>
                <div style="color: #6b7280; margin-bottom: 24px; line-height: 1.8;">
                    <div><i class="fas fa-question-circle" style="color: var(--primary-color);"></i> 100 Soal (50 Listening + 50 Reading)</div>
                    <div><i class="fas fa-clock" style="color: var(--primary-color);"></i> Waktu: 60 menit</div>
                    <div><i class="fas fa-ticket-alt" style="color: var(--primary-color);"></i> Biaya: 1 kuota per tryout</div>
                </div>
                <a href="{{ route('user.exam.dashboard') }}" class="btn-custom btn-primary-custom" style="padding: 14px 32px; font-size: 15px;">
                    <i class="fas fa-play-circle"></i> Mulai Tryout
                </a>
            </div>
        </div>
        @endif
    </main>

    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        // Close sidebar when clicking nav link on mobile
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });

        // Store user data in sessionStorage for exam pages
        const userData = @json(session('user_data'));
        const apiToken = "{{ session('api_token') }}";
        
        if (userData && apiToken) {
            sessionStorage.setItem("std_code", userData.std_code);
            sessionStorage.setItem("api_token", apiToken);
            localStorage.setItem("vieraData::" + userData.std_code, JSON.stringify(userData));
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
