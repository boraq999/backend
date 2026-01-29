@extends('layouts.app')

@section('title', 'طلبات إرجاع البضاعة')

@section('content')
<!-- المخزن الرئيسي -->
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <div class="icon-box bg-primary">
                <i class="bi bi-building"></i>
            </div>
            <h6 class="mb-0">المخزن الرئيسي</h6>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($mainStock as $stock)
                <div class="col-md-3 mb-3">
                    <div class="stock-item">
                        <div class="stock-info">
                            <div class="stock-name">{{ $stock->name }}</div>
                            <div class="stock-price">{{ number_format($stock->current_price, 2) }} دينار</div>
                        </div>
                        <div class="stock-quantity">{{ $stock->quantity }}</div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted mb-0">لا يوجد مخزون</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">
        <i class="bi bi-arrow-return-left"></i> طلبات إرجاع البضاعة
    </h2>
    <a href="#" class="btn btn-secondary">
        <i class="bi bi-building"></i> المخزن الرئيسي
    </a>
</div>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
            <i class="bi bi-hourglass-split"></i> في انتظار الموافقة ({{ $pendingCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
            <i class="bi bi-clipboard-check"></i> في انتظار التوثيق ({{ $approvedCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documented" type="button" role="tab">
            <i class="bi bi-check-circle"></i> موثق ({{ $documentedCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
            <i class="bi bi-x-circle"></i> مرفوضة ({{ $rejectedCount }})
        </button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="pending" role="tabpanel">
        @include('warehouse.returns.partials.returns-table', ['returns' => $pendingReturns, 'type' => 'pending'])
    </div>
    
    <div class="tab-pane fade" id="approved" role="tabpanel">
        @include('warehouse.returns.partials.returns-table', ['returns' => $approvedReturns, 'type' => 'approved'])
    </div>
    
    <div class="tab-pane fade" id="documented" role="tabpanel">
        @include('warehouse.returns.partials.returns-table', ['returns' => $documentedReturns, 'type' => 'documented'])
    </div>
    
    <div class="tab-pane fade" id="rejected" role="tabpanel">
        @include('warehouse.returns.partials.returns-table', ['returns' => $rejectedReturns, 'type' => 'rejected'])
    </div>
</div>

<style>
.page-title {
    color: var(--text-heading);
    font-weight: 700;
    font-size: 1.5em;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
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

.icon-box {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1em;
}

.stock-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    transition: var(--transition);
}

.stock-item:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.stock-info {
    flex: 1;
}

.stock-name {
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 2px;
}

.stock-price {
    font-size: 0.85em;
    color: var(--text-muted);
}

.stock-quantity {
    background: var(--primary);
    color: white;
    padding: 6px 12px;
    border-radius: var(--radius-sm);
    font-weight: 700;
    font-size: 0.9em;
}
</style>
@endsection
