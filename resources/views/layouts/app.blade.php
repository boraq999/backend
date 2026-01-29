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
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #eff6ff;
            --primary-glow: rgba(59, 130, 246, 0.15);
            --bg-main: #f8fafc;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --text-main: #475569;
            --text-heading: #0f172a;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html[data-theme="dark"] {
            --primary: #60a5fa;
            --primary-light: #1e3a8a;
            --primary-glow: rgba(96, 165, 250, 0.15);
            --bg-main: #0f172a;
            --bg-sidebar: #1e293b;
            --bg-card: #1e293b;
            --text-main: #cbd5e1;
            --text-heading: #f1f5f9;
            --text-muted: #64748b;
            --border: #334155;
            --border-hover: #475569;
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            font-family: 'Cairo', 'Outfit', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            line-height: 1.6;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        .app-container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: var(--bg-sidebar);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px 10px;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), #1e40af);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.3em;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .logo-text {
            font-size: 1.6em;
            font-weight: 800;
            color: var(--text-heading);
            font-family: 'Outfit';
            letter-spacing: -1px;
            display: flex;
            flex-direction: row-reverse;
            align-items: baseline;
            gap: 2px;
        }

        .logo-text span {
            color: var(--primary);
            font-size: 1.1em;
            font-weight: 700;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 15px 5px;
            display: flex;
            flex-direction: column;
        }

        .nav-group {
            font-size: 0.75em;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            margin: 25px 8px 10px;
            letter-spacing: 0.1em;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav ul li {
            padding: 0 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 8px;
            color: var(--text-main);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 0.9em;
            position: relative;
            border: 1px solid transparent;
        }

        .nav-link i {
            font-size: 1.25em;
            opacity: 0.6;
        }

        .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-glow);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        .nav-link.active i {
            opacity: 1;
            transform: scale(1.1);
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--bg-main);
        }

        .top-bar {
            height: 70px;
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 50;
        }

        .top-bar-start {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .top-bar h1 {
            font-size: 1.1em;
            font-weight: 800;
            color: var(--text-heading);
            margin: 0;
        }

        .top-bar-end {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-main);
            font-weight: 600;
        }

        .user-role {
            font-size: 0.85em;
            color: var(--text-muted);
            background: var(--primary-light);
            padding: 2px 8px;
            border-radius: var(--radius-sm);
        }

        .icon-btn {
            width: 42px;
            height: 42px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            border-radius: 12px;
            cursor: pointer;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
            text-decoration: none;
        }

        .icon-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .theme-toggle {
            width: 42px;
            height: 42px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            border-radius: 12px;
            cursor: pointer;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
            text-decoration: none;
        }

        .theme-toggle:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .content-viewer {
            flex: 1;
            overflow-y: auto;
            padding: 50px 40px;
            scroll-behavior: smooth;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border);
            padding: 20px 30px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .card-body {
            padding: 30px;
        }

        .btn {
            border-radius: var(--radius-md);
            font-weight: 600;
            padding: 10px 20px;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            box-shadow: 0 4px 12px var(--primary-glow);
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .btn-danger {
            background: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }

        .btn-secondary {
            background: var(--text-muted);
            color: white;
        }

        /* Override Bootstrap Table Styles */
        .table {
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
        }

        .table thead {
            background-color: var(--bg-main) !important;
        }

        .table thead th {
            background-color: var(--bg-main) !important;
            color: var(--text-muted) !important;
            border-color: var(--border) !important;
        }

        .table tbody td {
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
            border-color: var(--border) !important;
        }

        .table tbody tr:hover {
            background-color: var(--primary-light) !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--bg-card) !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: var(--bg-card) !important;
        }

        .table-hover tbody tr:hover {
            background-color: var(--primary-light) !important;
            color: var(--text-main) !important;
        }

        /* Text and Content Styles */
        p, span, div, li, label, h1, h2, h3, h4, h5, h6 {
            color: var(--text-main);
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--text-heading);
        }

        a {
            color: var(--primary);
        }

        a:hover {
            color: #2563eb;
        }

        /* Detail View Styles */
        .detail-section {
            background: var(--bg-card);
            color: var(--text-main);
        }

        .detail-label {
            color: var(--text-muted);
            font-weight: 600;
        }

        .detail-value {
            color: var(--text-main);
        }

        /* Badge and Status Styles */
        .badge {
            background-color: var(--primary) !important;
            color: white !important;
        }

        .badge-success {
            background-color: #10b981 !important;
        }

        .badge-warning {
            background-color: #f59e0b !important;
        }

        .badge-danger {
            background-color: #ef4444 !important;
        }

        .badge {
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.8em;
        }

        .bg-warning {
            background: #f59e0b !important;
        }

        .bg-success {
            background: #10b981 !important;
        }

        .bg-danger {
            background: #ef4444 !important;
        }

        .alert {
            border-radius: var(--radius-md);
            border: none;
            padding: 16px 20px;
            margin-bottom: 25px;
        }

        .alert-success {
            background: linear-gradient(to right, #ecfdf5, #ffffff);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: linear-gradient(to right, #fef2f2, #ffffff);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .form-control, .form-select {
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: 12px 16px;
            background: var(--bg-card);
            color: var(--text-main);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            background: var(--bg-card);
            color: var(--text-main);
        }

        .modal-content {
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            background: var(--bg-card);
        }

        .modal-header {
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border);
            padding: 20px 30px;
        }

        .modal-body {
            padding: 30px;
        }

        /* Tabs Styling */
        .nav-tabs {
            border-bottom: none;
            gap: 0;
            display: flex;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        .nav-tabs::-webkit-scrollbar {
            height: 4px;
        }

        .nav-tabs::-webkit-scrollbar-track {
            background: transparent;
        }

        .nav-tabs::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        .nav-tabs::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        .nav-tabs .nav-link {
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 12px 16px;
            font-weight: 500;
            margin: 0 4px 0 0;
            border-radius: 8px;
            background: transparent;
            white-space: nowrap;
            flex-shrink: 0;
            min-width: fit-content;
        }

        .nav-tabs .nav-link:hover {
            background: var(--bg-main);
            color: var(--text-main);
            border-color: var(--border);
        }

        .nav-tabs .nav-link.active {
            background: var(--primary) ;
            color: white !important;
            border-color: var(--primary);
            box-shadow: none;
        }

        .tab-content {
            padding: 20px 0;
        }

        .tab-pane {
            animation: fadeIn 0.2s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .nav-tabs .nav-link {
                padding: 10px 12px;
                font-size: 0.85em;
                border: solid 2px #5d9ff1;
                border-radius: 20px;
                margin: 0 8px 0 0;
            }

            .nav-tabs {
                flex-wrap: nowrap;
                padding-left: 0;
                padding-right: 0;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                right: -260px;
                top: 0;
                bottom: 0;
                width: 260px;
                box-shadow: -20px 0 60px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                transition: right 0.3s ease;
            }

            .sidebar.open {
                right: 0;
            }

            .app-container {
                position: relative;
            }

            .main-content {
                width: 100%;
            }

            .top-bar {
                padding: 0 20px;
            }

            .content-viewer {
                padding: 20px 15px;
            }

            .card {
                margin-bottom: 15px;
            }

            .col-md-8, .col-md-4 {
                width: 100% !important;
                margin-bottom: 15px;
            }

            .table {
                font-size: 0.85em;
            }

            .btn {
                padding: 8px 12px;
                font-size: 0.85em;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon"><i class="bi bi-stack"></i></div>
                    <div class="logo-text">TDM<span>System</span></div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-group">الرئيسية</div>
                <ul>
                    <li><a href="{{ route('requests.index') }}" class="nav-link {{ request()->routeIs('marketer.requests.*', 'warehouse.requests.*') ? 'active' : '' }}"><i class="bi bi-list-ul"></i> <span>@auth @if(Auth::user()->isSalesman()) التعبئة و الطلبات السابقة @else الطلبات @endif @else الطلبات @endauth</span></a></li>
                    @auth
                        @if(Auth::user()->isSalesman())
                        <li><a href="{{ route('marketer.stock') }}" class="nav-link {{ request()->routeIs('marketer.stock') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> <span>مخزوني</span></a></li>
                        <li><a href="{{ route('marketer.returns.index') }}" class="nav-link {{ request()->routeIs('marketer.returns.*') ? 'active' : '' }}"><i class="bi bi-arrow-return-left"></i> <span>إرجاع بضاعة</span></a></li>
                        @endif
                    @endauth
                </ul>

                @auth
                    @if(Auth::user()->isWarehouseKeeper())
                    <div class="nav-group">إدارة المخزن</div>
                    <ul>
                        <li><a href="#" class="nav-link"><i class="bi bi-building"></i> <span>المخزن الرئيسي</span></a></li>
                        <li><a href="#" class="nav-link"><i class="bi bi-truck"></i> <span>فواتير المصنع</span></a></li>
                        <li><a href="{{ route('warehouse.returns.index') }}" class="nav-link {{ request()->routeIs('warehouse.returns.*') ? 'active' : '' }}"><i class="bi bi-arrow-return-left"></i> <span>طلبات الإرجاع</span></a></li>
                    </ul>
                    @endif

                    @if(Auth::user()->isAdmin())
                    <div class="nav-group">الإدارة</div>
                    <ul>
                        <li><a href="#" class="nav-link"><i class="bi bi-people"></i> <span>المستخدمين</span></a></li>
                        <li><a href="#" class="nav-link"><i class="bi bi-box-seam"></i> <span>المنتجات</span></a></li>
                        <li><a href="#" class="nav-link"><i class="bi bi-shop"></i> <span>المتاجر</span></a></li>
                    </ul>
                    @endif
                @endauth

                @auth
                <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--border);">
                    <div class="user-info d-md-none" style="padding: 15px 0; border-bottom: 1px solid var(--border); margin-bottom: 15px;">
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <span style="font-weight: 600;">{{ Auth::user()->name }}</span>
                            <span class="user-role">{{ Auth::user()->role->name }}</span>
                        </div>
                    </div>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link d-md-none" style="color: #ef4444;">
                        <i class="bi bi-box-arrow-right"></i> <span>تسجيل الخروج</span>
                    </a>
                </div>
                @endauth
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <div class="top-bar-start">
                    <button type="button" class="btn-menu d-md-none" id="sidebarToggle" style="background: none; border: none; color: var(--text-main); font-size: 1.3em; cursor: pointer;">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1>@yield('title', 'نظام إدارة التوزيع')</h1>
                </div>
                <div class="top-bar-end">
                    <button type="button" class="theme-toggle d-flex" id="themeToggle" title="تبديل الوضع الداكن">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    @auth
                        <div class="user-info d-none d-md-flex">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="user-role">{{ Auth::user()->role->name }}</span>
                        </div>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="icon-btn d-none d-md-flex" title="خروج">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endauth
                </div>
            </header>

            <div class="content-viewer">
                <div class="container">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
            
            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });
        }

        // Theme Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('theme') || 'light';

            function setTheme(theme) {
                if (theme === 'dark') {
                    html.setAttribute('data-theme', 'dark');
                    if (themeToggle) themeToggle.innerHTML = '<i class="bi bi-sun"></i>';
                } else {
                    html.removeAttribute('data-theme');
                    if (themeToggle) themeToggle.innerHTML = '<i class="bi bi-moon-stars"></i>';
                }
                localStorage.setItem('theme', theme);
            }

            setTheme(savedTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const currentTheme = html.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
