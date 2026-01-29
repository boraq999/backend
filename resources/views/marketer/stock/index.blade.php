@extends('layouts.app')

@section('title', 'مخزوني')

@section('content')
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: #d1e7dd;">
                <i class="bi bi-box-seam" style="color: #0f5132;"></i>
            </div>
            <div class="stats-content">
                <h3>{{ number_format($actualStock->sum('quantity')) }}</h3>
                <p>إجمالي المخزون الفعلي</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: #fff3cd;">
                <i class="bi bi-hourglass-split" style="color: #856404;"></i>
            </div>
            <div class="stats-content">
                <h3>{{ number_format($reservedStock->sum('quantity')) }}</h3>
                <p>إجمالي المخزون المحجوز</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: #d1f4e0;">
                <i class="bi bi-cash-stack" style="color: #28a745;"></i>
            </div>
            <div class="stats-content">
                <h3>{{ number_format($actualStock->sum('total_value'), 2) }}</h3>
                <p>القيمة الإجمالية (دينار)</p>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#actual" type="button" role="tab">
            <i class="bi bi-box-seam"></i> المخزون الفعلي ({{ $actualStock->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reserved" type="button" role="tab">
            <i class="bi bi-hourglass-split"></i> المخزون المحجوز ({{ $reservedStock->count() }})
        </button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="actual">
        <div class="card">
            <div class="card-body">
                <!-- Desktop Table -->
                <div class="modern-table d-none d-md-block">
                    <table>
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>القيمة الإجمالية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($actualStock as $item)
                            <tr>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td><span class="qty-badge qty-actual">{{ number_format($item->quantity) }}</span></td>
                                <td>{{ number_format($item->current_price, 2) }} دينار</td>
                                <td><strong>{{ number_format($item->total_value, 2) }} دينار</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="empty-cell">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>لا يوجد مخزون فعلي</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="stock-cards d-md-none">
                    @forelse($actualStock as $item)
                        <div class="stock-card">
                            <div class="stock-card-header">
                                <div class="stock-card-title">
                                    <i class="bi bi-box-seam"></i>
                                    {{ $item->name }}
                                </div>
                                <span class="qty-badge qty-actual">{{ number_format($item->quantity) }}</span>
                            </div>
                            <div class="stock-card-body">
                                <div class="stock-info">
                                    <span class="label">السعر:</span>
                                    <span class="value">{{ number_format($item->current_price, 2) }} دينار</span>
                                </div>
                                <div class="stock-info">
                                    <span class="label">القيمة الإجمالية:</span>
                                    <span class="value"><strong>{{ number_format($item->total_value, 2) }} دينار</strong></span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>لا يوجد مخزون فعلي</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="reserved">
        <div class="card">
            <div class="card-body">
                <!-- Desktop Table -->
                <div class="modern-table d-none d-md-block">
                    <table>
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>القيمة الإجمالية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservedStock as $item)
                            <tr>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td><span class="qty-badge qty-reserved">{{ number_format($item->quantity) }}</span></td>
                                <td>{{ number_format($item->current_price, 2) }} دينار</td>
                                <td><strong>{{ number_format($item->total_value, 2) }} دينار</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="empty-cell">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>لا يوجد مخزون محجوز</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="stock-cards d-md-none">
                    @forelse($reservedStock as $item)
                        <div class="stock-card">
                            <div class="stock-card-header">
                                <div class="stock-card-title">
                                    <i class="bi bi-hourglass-split"></i>
                                    {{ $item->name }}
                                </div>
                                <span class="qty-badge qty-reserved">{{ number_format($item->quantity) }}</span>
                            </div>
                            <div class="stock-card-body">
                                <div class="stock-info">
                                    <span class="label">السعر:</span>
                                    <span class="value">{{ number_format($item->current_price, 2) }} دينار</span>
                                </div>
                                <div class="stock-info">
                                    <span class="label">القيمة الإجمالية:</span>
                                    <span class="value"><strong>{{ number_format($item->total_value, 2) }} دينار</strong></span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>لا يوجد مخزون محجوز</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.stats-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: var(--text-heading);
}

.stats-content p {
    margin: 4px 0 0 0;
    font-size: 14px;
    color: var(--text-muted);
}

.nav-tabs {
    border-bottom: 1px solid var(--border);
}

.nav-tabs .nav-link {
    color: var(--text-main);
    border: none;
    border-bottom: 3px solid transparent;
    background: none;
    padding: 12px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: var(--primary-light);
    color: var(--primary);
}

.nav-tabs .nav-link.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.tab-content {
    margin-top: 0;
}

.tab-pane .card {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.modern-table {
    background: var(--bg-card);
    border-radius: 12px;
    overflow: hidden;
}

.modern-table table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: var(--bg-secondary);
}

.modern-table th {
    padding: 16px 20px;
    text-align: right;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
}

.modern-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background-color 0.2s;
}

.modern-table tbody tr:hover {
    background-color: var(--bg-hover);
}

.modern-table tbody tr:last-child {
    border-bottom: none;
}

.modern-table td {
    padding: 16px 20px;
    text-align: right;
    font-size: 14px;
    color: var(--text-main);
    vertical-align: middle;
}

.qty-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.qty-actual {
    background: #d1e7dd;
    color: #0f5132;
}

.qty-reserved {
    background: #fff3cd;
    color: #856404;
}

.empty-cell {
    padding: 60px 20px !important;
}

.empty-state {
    text-align: center;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}

/* Mobile Cards */
.stock-cards {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.stock-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stock-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-secondary);
}

.stock-card-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--text-heading);
    font-size: 14px;
}

.stock-card-body {
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.stock-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
}

.stock-info .label {
    color: var(--text-muted);
}

.stock-info .value {
    color: var(--text-main);
}
</style>
@endsection
