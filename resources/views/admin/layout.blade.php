<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Viera Tryout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #10b981;
            --sidebar-width: 260px;
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
            padding: 24px 20px;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .sidebar-logo i {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .sidebar-subtitle {
            font-size: 12px;
            opacity: 0.9;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px;
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
            margin-bottom: 8px;
            border: none;
            width: 100%;
            text-align: left;
            background: transparent;
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

        .nav-link.logout {
            color: #dc2626;
            margin-top: 16px;
        }

        .nav-link.logout:hover {
            background: #fee2e2;
            color: #991b1b;
        }

        .admin-profile {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-avatar {
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

        .admin-info {
            flex: 1;
            min-width: 0;
        }

        .admin-name {
            font-weight: 600;
            color: #111827;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-role {
            color: #6b7280;
            font-size: 12px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: #059669;
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
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth('admin')
    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="hamburger" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div style="font-weight: 700; color: #111827;">
            <i class="fas fa-shield-alt" style="color: var(--primary-color);"></i> Viera Admin
        </div>
        <div style="width: 30px;"></div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <div class="sidebar-title">Viera Admin</div>
                    <div class="sidebar-subtitle">Management Panel</div>
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i>
                <span>Manajemen User</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.pricing.*') ? 'active' : '' }}" href="{{ route('admin.pricing.index') }}">
                <i class="fas fa-dollar-sign"></i>
                <span>Harga Kuota</span>
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <div class="admin-profile">
            <div class="admin-avatar">
                {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
            </div>
            <div class="admin-info">
                <div class="admin-name">{{ auth('admin')->user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="main-content">
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

        @yield('content')
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
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && !this.classList.contains('logout')) {
                    toggleSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
