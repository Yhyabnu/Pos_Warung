<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .login-container:hover {
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .brand-logo {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 3px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .brand-logo:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.2);
        }
        
        .login-body {
            padding: 2.5rem;
            position: relative;
        }
        
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
            background: white;
        }
        
        .input-group {
            border-radius: 14px;
            overflow: hidden;
        }
        
        .input-group-text {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 14px 0 0 14px;
            padding: 1rem 1.25rem;
            color: #64748b;
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .form-control.border-start-0 {
            border-left: none;
            border-radius: 0 14px 14px 0;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .alert {
            border-radius: 14px;
            border: none;
            padding: 1rem 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
            color: #dc2626;
            border-left: 4px solid var(--danger);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #bbf7d0 100%);
            color: #16a34a;
            border-left: 4px solid var(--success);
        }
        
        .footer-text {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-list li {
            padding: 0.75rem 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .feature-list li:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .feature-list li i {
            color: var(--warning);
            margin-right: 0.75rem;
            font-size: 1.2rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .feature-list li:hover i {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }
        
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.15em;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-check-label {
            color: #4b5563;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .form-check-label:hover {
            color: var(--primary);
        }
        
        .password-toggle {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-left: none;
            border-radius: 0 14px 14px 0;
            color: #64748b;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .password-toggle:hover {
            background: #f1f5f9;
            color: var(--primary);
        }
        
        .features-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .features-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                margin: 10px;
            }
            
            .login-body {
                padding: 2rem 1.5rem;
            }
            
            .features-section {
                padding: 2rem 1.5rem !important;
            }
            
            .brand-logo {
                width: 70px;
                height: 70px;
            }
        }
        
        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="login-container">
                    <div class="row g-0">
                        <!-- Login Form -->
                        <div class="col-md-6">
                            <div class="login-body h-100 d-flex flex-column">
                                <div class="text-center mb-4">
                                    <h3 class="fw-bold text-dark mb-2">Login Kasir</h3>
                                    <p class="text-muted">Masukkan kredensial Anda untuk mengakses sistem</p>
                                </div>

                                @if($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                            <strong class="fs-6">Login Gagal!</strong>
                                        </div>
                                        <div class="mt-2">
                                            @foreach($errors->all() as $error)
                                                <div class="small">• {{ $error }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(session('status'))
                                    <div class="alert alert-success mb-4">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger mb-4">
                                        <i class="bi bi-x-circle-fill me-2"></i>
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('kasir.login') }}" id="loginForm">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold text-dark">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control border-start-0" id="email" name="email" 
                                                   value="{{ old('email', Cookie::get('kasir_remember_email') ?? '') }}" 
                                                   required autofocus 
                                                   placeholder="masukkan.email@contoh.com">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold text-dark">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control border-start-0" id="password" 
                                                   name="password" required 
                                                   placeholder="Masukkan password Anda"
                                                   value="{{ Cookie::get('kasir_remember_password') ?? '' }}">
                                            <button type="button" class="btn password-toggle" id="togglePassword">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                                   {{ Cookie::get('kasir_remember_email') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                <i class="bi bi-check2-square me-1"></i>
                                                Ingat saya di perangkat ini
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            Centang untuk menyimpan email dan password
                                        </small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-login mb-4" id="loginButton">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        <span id="loginText">Masuk ke Kasir</span>
                                        <div class="loading d-none" id="loginLoading"></div>
                                    </button>
                                </form>

                                <div class="text-center mt-auto pt-3 border-top">
                                    <small class="footer-text">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Sistem Kasir &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Pastikan Anda memiliki akses kasir yang valid
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Features Section -->
                        <div class="col-md-6 features-section">
                            <div class="p-4 h-100 d-flex flex-column justify-content-center position-relative">
                                <div class="text-center mb-4">
                                    <div class="brand-logo">
                                        <i class="bi bi-cart3" style="font-size: 2.2rem;"></i>
                                    </div>
                                    <h4 class="fw-bold mb-2">{{ config('app.name', 'Sistem Kasir') }}</h4>
                                    <p class="opacity-90 mb-0">Point of Sale Modern & Terpercaya</p>
                                </div>
                                
                                <ul class="feature-list mb-4">
                                    <li>
                                        <i class="bi bi-lightning-fill"></i>
                                        Transaksi Super Cepat
                                    </li>
                                    <li>
                                        <i class="bi bi-graph-up-arrow"></i>
                                        Laporan Real-time
                                    </li>
                                    <li>
                                        <i class="bi bi-shield-lock"></i>
                                        Keamanan Terjamin
                                    </li>
                                    <li>
                                        <i class="bi bi-printer"></i>
                                        Cetak Struk Otomatis
                                    </li>
                                    <li>
                                        <i class="bi bi-phone"></i>
                                        Responsif Semua Device
                                    </li>
                                    <li>
                                        <i class="bi bi-cloud-check"></i>
                                        Data Tersimpan Aman
                                    </li>
                                </ul>
                                
                                <div class="text-center mt-auto">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="opacity-75 d-block">
                                                <i class="bi bi-cpu me-1"></i>
                                                Laravel {{ app()->version() }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="opacity-75 d-block">
                                                <i class="bi bi-heart-fill me-1 text-danger"></i>
                                                Made with Love
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loginText = document.getElementById('loginText');
            const loginLoading = document.getElementById('loginLoading');
            const rememberCheckbox = document.getElementById('remember');

            // Auto focus on email field
            if (emailField) {
                emailField.focus();
                emailField.select();
            }

            // Toggle password visibility
            if (togglePassword && passwordField) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    this.innerHTML = type === 'password' 
                        ? '<i class="bi bi-eye"></i>' 
                        : '<i class="bi bi-eye-slash"></i>';
                });
            }

            // Form submission with loading state
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const email = emailField.value.trim();
                    const password = passwordField.value.trim();
                    
                    // Basic validation
                    if (!email || !password) {
                        e.preventDefault();
                        return;
                    }
                    
                    // Show loading state
                    loginText.classList.add('d-none');
                    loginLoading.classList.remove('d-none');
                    loginButton.disabled = true;
                    
                    // Simulate minimum loading time for better UX
                    setTimeout(() => {
                        loginText.classList.remove('d-none');
                        loginLoading.classList.add('d-none');
                        loginButton.disabled = false;
                    }, 2000);
                });
            }

            // Auto-save remember me preference
            if (rememberCheckbox) {
                rememberCheckbox.addEventListener('change', function() {
                    // This will be handled by the backend via cookies
                });
            }

            // Add input animations
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.classList.remove('focused');
                });
            });

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + Enter to submit form
                if (e.ctrlKey && e.key === 'Enter') {
                    e.preventDefault();
                    loginForm.dispatchEvent(new Event('submit'));
                }
                
                // Escape to clear form
                if (e.key === 'Escape') {
                    emailField.value = '';
                    passwordField.value = '';
                    emailField.focus();
                }
            });

            // Check if credentials are remembered
            const rememberedEmail = '{{ Cookie::get("kasir_remember_email") }}';
            if (rememberedEmail && rememberedEmail !== '') {
                rememberCheckbox.checked = true;
            }

            console.log('Login Kasir System Ready 🚀');
        });
    </script>
</body>
</html>