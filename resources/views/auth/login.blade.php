<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة التوزيع</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #eff6ff;
            --primary-glow: rgba(59, 130, 246, 0.15);
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #475569;
            --text-heading: #0f172a;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
        }

        html[data-theme="dark"] {
            --primary: #60a5fa;
            --primary-light: #1e3a8a;
            --primary-glow: rgba(96, 165, 250, 0.15);
            --bg-main: #0f172a;
            --bg-card: #1e293b;
            --text-main: #cbd5e1;
            --text-heading: #f1f5f9;
            --text-muted: #64748b;
            --border: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--bg-main) 0%, var(--bg-main) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), #1e40af);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.8em;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        @media (prefers-color-scheme: dark) {
            .logo-icon {
                background: linear-gradient(135deg, #60a5fa, #3b82f6);
            }
        }

        .login-header h1 {
            font-size: 1.8em;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.95em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: var(--text-heading);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        .form-control {
            background: var(--bg-main);
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95em;
        }

        .form-control:focus {
            background: var(--bg-main);
            border-color: var(--primary);
            color: var(--text-main);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9em;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .form-check-label {
            cursor: pointer;
            color: var(--text-main);
            margin: 0;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        @media (prefers-color-scheme: dark) {
            .forgot-link:hover {
                color: #60a5fa;
            }
        }

        .btn-login {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 700;
            font-size: 0.95em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px var(--primary-glow);
            margin-bottom: 15px;
        }

        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        @media (prefers-color-scheme: dark) {
            .btn-login {
                background: #3b82f6;
            }

            .btn-login:hover {
                background: #60a5fa;
            }
        }

        .signup-link {
            text-align: center;
            color: var(--text-main);
            font-size: 0.9em;
        }

        .signup-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-left: 4px solid #ef4444;
        }

        @media (prefers-color-scheme: dark) {
            .alert-danger {
                background: rgba(248, 113, 113, 0.1);
                color: #f87171;
                border-left-color: #f87171;
            }
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 42px;
            height: 42px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            border-radius: 10px;
            cursor: pointer;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .theme-toggle:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 1.5em;
            }

            .theme-toggle {
                top: 15px;
                left: 15px;
            }
        }
    </style>
</head>
<body>
    <button class="theme-toggle" id="themeToggle" title="تبديل الوضع الداكن">
        <i class="bi bi-moon-stars"></i>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-icon">
                    <i class="bi bi-stack"></i>
                </div>
                <h1>TDM System</h1>
                <p>نظام إدارة التوزيع والديون</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">اسم المستخدم أو البريد الإلكتروني</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="أدخل اسم المستخدم أو بريدك الإلكتروني" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">كلمة المرور</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="أدخل كلمة المرور" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">هل نسيت كلمة المرور؟</a>
                    @endif
                </div>

                <div class="alert alert-info" style="font-size: 0.85em;">
                    <strong><i class="bi bi-info-circle"></i> بيانات تجريبية:</strong><br>
                    <small>
                        <strong>مسوق:</strong> salesman / password<br>
                        <strong>أمين مخزن:</strong> warehouse / password<br>
                        <strong>مسؤول:</strong> admin / password
                    </small>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> تسجيل الدخول
                </button>

                @if (Route::has('register'))
                    <div class="signup-link">
                        ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';

        function setTheme(theme) {
            if (theme === 'dark') {
                html.setAttribute('data-theme', 'dark');
                themeToggle.innerHTML = '<i class="bi bi-sun"></i>';
            } else {
                html.removeAttribute('data-theme');
                themeToggle.innerHTML = '<i class="bi bi-moon-stars"></i>';
            }
            localStorage.setItem('theme', theme);
        }

        setTheme(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    </script>
</body>
</html>
