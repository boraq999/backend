<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TDM | نظام إدارة التوزيع')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --primary-glow: rgba(37, 99, 235, 0.15);
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #475569;
            --text-heading: #0f172a;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --radius-lg: 20px;
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Cairo', 'Outfit', sans-serif;
            background: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 900px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 60px;
        }

        .welcome-section {
            flex: 1;
        }

        .logo-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8em;
            color: white;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .logo-text {
            font-size: 2.5em;
            font-weight: 800;
            color: var(--text-heading);
            font-family: 'Outfit';
            letter-spacing: -2px;
            margin: 0;
        }

        .logo-subtitle {
            color: var(--text-muted);
            font-size: 1.2em;
            font-weight: 400;
            margin-bottom: 30px;
        }

        .welcome-text {
            color: var(--text-main);
            font-size: 1.1em;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .demo-accounts {
            background: linear-gradient(to right, var(--primary-light), #ffffff);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 25px;
            border-left: 4px solid var(--primary);
        }

        .demo-accounts h6 {
            color: var(--text-heading);
            font-weight: 700;
            margin-bottom: 18px;
            font-size: 1.1em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .demo-accounts h6::before {
            content: '';
            width: 4px;
            height: 20px;
            background: var(--primary);
            border-radius: 2px;
        }

        .account-item {
            color: var(--text-main);
            font-size: 0.95em;
            margin-bottom: 12px;
            font-family: 'Outfit';
            padding: 8px 0;
            border-bottom: 1px solid rgba(37, 99, 235, 0.1);
        }

        .account-item:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .account-item strong {
            color: var(--text-heading);
            font-weight: 600;
        }

        .login-section {
            width: 400px;
            flex-shrink: 0;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .card-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 25px 30px 15px;
            text-align: center;
        }

        .card-header h4 {
            color: var(--text-heading);
            font-weight: 600;
            margin: 0;
            font-size: 1.3em;
        }

        .card-body {
            padding: 25px 30px 30px;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 12px 16px;
            transition: var(--transition);
            font-size: 1em;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 20px;
            transition: var(--transition);
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
        }

        .alert-info {
            background: linear-gradient(to right, #eff6ff, #ffffff);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                gap: 30px;
                text-align: center;
            }

            .login-section {
                width: 100%;
                max-width: 400px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="welcome-section">
            <div class="logo-header">
                <div class="logo-icon">
                    <i class="bi bi-stack"></i>
                </div>
                <div class="logo-text">TDM</div>
            </div>
            <div class="logo-subtitle">نظام إدارة التوزيع</div>
            <div class="welcome-text">
                مرحباً بك في نظام TDM لإدارة التوزيع. نظام متكامل لإدارة سلسلة التوزيع والديون مع توثيق كامل لكل عملية.
            </div>
            
            <div class="demo-accounts">
                <h6>حسابات تجريبية:</h6>
                <div class="account-item">
                    <strong>مدير:</strong> admin / password
                </div>
                <div class="account-item">
                    <strong>أمين مخزن:</strong> warehouse / password
                </div>
                <div class="account-item">
                    <strong>مسوق:</strong> salesman / password
                </div>
            </div>
        </div>

        <div class="login-section">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>