<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akun Saya - Viera Tryout</title>
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

        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .profile-header {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .profile-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 32px;
            margin: 0 auto 16px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 150px;
            color: #6b7280;
            font-weight: 600;
            font-size: 14px;
        }

        .info-value {
            flex: 1;
            color: #111827;
            font-size: 14px;
        }

        .session-card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .session-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .icon-phone {
            background: #dbeafe;
            color: #1e40af;
        }

        .icon-laptop {
            background: #fef3c7;
            color: #92400e;
        }

        .session-info {
            flex: 1;
        }

        .session-name {
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .session-meta {
            font-size: 13px;
            color: #6b7280;
        }

        .badge-current {
            background: #ecfdf5;
            color: var(--primary-color);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-logout-device {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout-device:hover {
            background: #fecaca;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-box {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
        }

        .stat-label {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
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

            .profile-header {
                padding: 24px;
            }

            .profile-avatar-large {
                width: 64px;
                height: 64px;
                font-size: 24px;
            }

            .card {
                padding: 16px;
            }

            .info-row {
                flex-direction: column;
                padding: 10px 0;
            }

            .info-label {
                width: 100%;
                margin-bottom: 4px;
            }

            .session-card {
                flex-wrap: wrap;
                padding: 12px;
            }

            .session-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .session-info {
                min-width: calc(100% - 56px);
            }

            .btn-logout-device {
                width: 100%;
                margin-top: 12px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-box {
                padding: 16px;
            }

            .stat-value {
                font-size: 28px;
            }

            h3 {
                font-size: 1.3rem;
            }

            h4 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .profile-header h3 {
                font-size: 1.2rem;
            }

            .stat-value {
                font-size: 24px;
            }

            .session-meta {
                font-size: 11px;
            }

            .badge-current {
                font-size: 10px;
                padding: 3px 8px;
            }
        }
    </style>
</head>
<body>
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
            <a href="{{ route('user.welcome') }}" class="nav-link">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('user.profile') }}" class="nav-link active">
                <i class="fas fa-user"></i>
                <span>Akun saya</span>
            </a>
        </nav>

        <div class="user-profile">
            <div class="user-avatar">
                {{ strtoupper(substr($user->std_name ?? $user->username, 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ $user->std_name ?? $user->username }}</div>
                <div class="user-email">{{ $user->username }}</div>
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
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-large">
                {{ strtoupper(substr($user->std_name ?? $user->username, 0, 2)) }}
            </div>
            <h3 style="margin-bottom: 8px;">{{ $user->std_name ?? $user->username }}</h3>
            <p style="opacity: 0.9; margin: 0;">{{ $user->username }}</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ $user->tryoutQuota->quota ?? 0 }}</div>
                <div class="stat-label">Kuota Tersisa</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $user->examResults->where('is_view_only', false)->count() }}</div>
                <div class="stat-label">Total Tryout</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $user->sessions->count() }}</div>
                <div class="stat-label">Device Aktif</div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="card">
            <h4 style="margin-bottom: 20px; font-weight: 700; color: #111827;">Informasi Profil</h4>
            <div class="info-row">
                <div class="info-label">Username</div>
                <div class="info-value">{{ $user->username }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $user->std_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Student Code</div>
                <div class="info-value">{{ $user->std_code }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NISN</div>
                <div class="info-value">{{ $user->std_nisn ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->std_email ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    @if($user->is_banned)
                        <span class="badge bg-danger">Banned</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Device Sessions -->
        <div class="card">
            <h4 style="margin-bottom: 16px; font-weight: 700; color: #111827;">Sesi Login Aktif</h4>
            <p style="color: #6b7280; margin-bottom: 20px; font-size: 14px;">
                Kelola device yang sedang login ke akun Anda. Maksimal 1 phone dan 1 laptop.
            </p>

            @php
                $currentDeviceId = md5(request()->userAgent() . request()->ip());
            @endphp

            @forelse($user->sessions as $session)
            <div class="session-card">
                <div class="session-icon {{ $session->device_type == 'phone' ? 'icon-phone' : 'icon-laptop' }}">
                    <i class="fas fa-{{ $session->device_type == 'phone' ? 'mobile-alt' : 'laptop' }}"></i>
                </div>
                <div class="session-info">
                    <div class="session-name">
                        {{ $session->device_name }}
                        @if($session->device_id === $currentDeviceId)
                            <span class="badge-current">Device Ini</span>
                        @endif
                    </div>
                    <div class="session-meta">
                        <i class="fas fa-network-wired"></i> {{ $session->ip_address }} &nbsp;•&nbsp;
                        <i class="fas fa-clock"></i> Terakhir aktif: {{ $session->last_activity->diffForHumans() }}
                    </div>
                </div>
                @if($session->device_id !== $currentDeviceId)
                <form action="{{ route('user.sessions.delete', $session->id) }}" method="POST" onsubmit="return confirm('Logout device ini? Device tersebut harus login ulang.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-logout-device">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
                @endif
            </div>
            @empty
            <div style="text-align: center; padding: 40px; color: #6b7280;">
                <i class="fas fa-mobile-alt" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px;"></i>
                <p>Tidak ada sesi aktif</p>
            </div>
            @endforelse
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    </script>
</body>
</html>
