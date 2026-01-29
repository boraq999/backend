@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8em;
        flex-shrink: 0;
    }

    .stat-icon.primary {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .stat-icon.success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-icon.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .stat-icon.danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    @media (prefers-color-scheme: dark) {
        .stat-card {
            background: #1e293b;
            border-color: #334155;
        }

        .stat-icon.primary {
            background: rgba(96, 165, 250, 0.1);
            color: #60a5fa;
        }

        .stat-icon.success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .stat-icon.warning {
            background: rgba(251, 146, 60, 0.1);
            color: #fb923c;
        }

        .stat-icon.danger {
            background: rgba(248, 113, 113, 0.1);
            color: #f87171;
        }
    }

    .stat-content h3 {
        font-size: 0.9em;
        color: var(--text-muted);
        margin: 0 0 8px 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-content .value {
        font-size: 1.8em;
        font-weight: 800;
        color: var(--text-heading);
        margin: 0;
    }

    .section-title {
        font-size: 1.3em;
        font-weight: 800;
        color: var(--text-heading);
        margin: 40px 0 25px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: var(--primary);
        font-size: 1.2em;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 40px;
    }

    .action-btn {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px 20px;
        text-align: center;
        text-decoration: none;
        color: var(--text-main);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    .action-btn:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    .action-btn i {
        font-size: 1.5em;
    }

    @media (prefers-color-scheme: dark) {
        .action-btn {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        .action-btn:hover {
            background: rgba(96, 165, 250, 0.1);
            border-color: #60a5fa;
            color: #60a5fa;
        }
    }

    .recent-table {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    @media (prefers-color-scheme: dark) {
        .recent-table {
            background: #1e293b;
            border-color: #334155;
        }
    }

    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3em;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 1.1em;
    }
</style>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="stat-content">
            <h3>المخزن الرئيسي</h3>
            <p class="value">{{ $mainStockCount ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>الطلبات المعتمدة</h3>
            <p class="value">{{ $approvedRequests ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-clock-history"></i>
        </div>
        <div class="stat-content">
            <h3>الطلبات المعلقة</h3>
            <p class="value">{{ $pendingRequests ?? 0 }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="stat-content">
            <h3>إجمالي الديون</h3>
            <p class="value">{{ number_format($totalDebts ?? 0, 0) }}</p>
        </div>
    </div>
</div>

<h2 class="section-title">
    <i class="bi bi-lightning-fill"></i>
    الإجراءات السريعة
</h2>

<div class="quick-actions">
    @auth
        @if(Auth::user()->isWarehouseKeeper())
            <a href="#" class="action-btn">
                <i class="bi bi-plus-circle"></i>
                <span>إضافة فاتورة مصنع</span>
            </a>
            <a href="#" class="action-btn">
                <i class="bi bi-check2-square"></i>
                <span>الموافقة على الطلبات</span>
            </a>
        @endif

        @if(Auth::user()->isSalesman())
            <a href="{{ route('requests.create') }}" class="action-btn">
                <i class="bi bi-plus-circle"></i>
                <span>طلب بضاعة جديد</span>
            </a>
            <a href="#" class="action-btn">
                <i class="bi bi-receipt"></i>
                <span>إنشاء فاتورة بيع</span>
            </a>
        @endif

        @if(Auth::user()->isAdmin())
            <a href="#" class="action-btn">
                <i class="bi bi-person-plus"></i>
                <span>إضافة مستخدم</span>
            </a>
            <a href="#" class="action-btn">
                <i class="bi bi-box-seam"></i>
                <span>إدارة المنتجات</span>
            </a>
        @endif
    @endauth
</div>

<h2 class="section-title">
    <i class="bi bi-list-ul"></i>
    آخر الطلبات
</h2>

<div class="recent-table">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>النوع</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentRequests ?? [] as $request)
                <tr>
                    <td>#{{ $request->id }}</td>
                    <td>{{ $request->type ?? 'عام' }}</td>
                    <td>
                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ $request->status === 'approved' ? 'معتمد' : ($request->status === 'pending' ? 'معلق' : 'مرفوض') }}
                        </span>
                    </td>
                    <td>{{ $request->created_at?->format('Y-m-d') ?? '-' }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">عرض</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>لا توجد طلبات حالياً</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
