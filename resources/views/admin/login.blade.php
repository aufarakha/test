<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Viera Tryout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        .login-card {
            max-width: 450px;
            width: 100%;
        }
        .card {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 16px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .app-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }
        .app-subtitle {
            color: #6b7280;
            font-size: 14px;
        }
        .admin-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-control {
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
        }
        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            font-size: 15px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        .form-check-input:checked {
            background-color: #10b981;
            border-color: #10b981;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .copyright {
            text-align: center;
            margin-top: 24px;
            color: #9ca3af;
            font-size: 13px;
        }
        
        /* Mobile responsive */
        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem !important;
            }
            .app-title {
                font-size: 24px;
            }
            .app-subtitle {
                font-size: 13px;
            }
            .logo-icon {
                width: 64px;
                height: 64px;
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="app-title">Viera Tryout</div>
                    <p class="app-subtitle">Administrator Portal</p>
                    <span class="admin-badge">
                        <i class="fas fa-crown"></i> Admin Access
                    </span>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul class="mb-0" style="padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.login') }}" method="POST" id="adminLoginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan username admin">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password">
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" checked>
                        <label class="form-check-label" for="remember" style="color: #6b7280; font-weight: 400;">
                            Ingat username saya
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Login as Admin
                    </button>
                </form>

                <div class="copyright">
                    © 2026 Viera Tryout System
                </div>
            </div>
        </div>
    </div>

    <script>
        // Force reload to get fresh CSRF token if coming from cached page
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        // Load saved username
        document.addEventListener('DOMContentLoaded', function() {
            const savedUsername = localStorage.getItem('viera_admin_username');
            if (savedUsername) {
                document.getElementById('username').value = savedUsername;
                document.getElementById('password').focus();
            }
        });

        // Save username on form submit and handle CSRF token refresh
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const remember = document.getElementById('remember').checked;
            const username = document.getElementById('username').value;
            
            if (remember) {
                localStorage.setItem('viera_admin_username', username);
            } else {
                localStorage.removeItem('viera_admin_username');
            }
            
            // Update CSRF token from meta tag
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                const token = csrfMeta.getAttribute('content');
                const csrfInput = document.querySelector('input[name="_token"]');
                if (csrfInput) {
                    csrfInput.value = token;
                }
            }
        });

        // Handle 419 error with automatic retry
        const loginForm = document.getElementById('adminLoginForm');
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Loading...';
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    redirect: 'follow'
                });
                
                if (response.status === 419) {
                    // CSRF token expired, reload page to get new token
                    window.location.reload();
                    return;
                }
                
                // If successful, redirect
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    // Handle error response
                    const html = await response.text();
                    document.open();
                    document.write(html);
                    document.close();
                }
            } catch (error) {
                console.error('Login error:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    </script>
</body>
</html>
