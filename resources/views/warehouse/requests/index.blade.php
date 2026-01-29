@extends('layouts.app')

@section('title', 'طلبات المسوقين')

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
                            <div class="stock-price">{{ number_format($stock->current_price, 2) }} ريال</div>
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
        <i class="bi bi-list-ul"></i> طلبات المسوقين
    </h2>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="requestTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="documented-tab" data-bs-toggle="tab" data-bs-target="#documented" type="button" role="tab">
            <i class="bi bi-check-circle"></i> موثق ({{ $documentedCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="waiting-doc-tab" data-bs-toggle="tab" data-bs-target="#waiting-doc" type="button" role="tab">
            <i class="bi bi-clock"></i> في انتظار التوثيق ({{ $waitingDocCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
            <i class="bi bi-hourglass-split"></i> في انتظار الموافقة ({{ $pendingCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
            <i class="bi bi-x-circle"></i> مرفوضة ({{ $rejectedCount }})
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="requestTabsContent">
    <!-- موثق -->
    <div class="tab-pane fade show active" id="documented" role="tabpanel">
        @include('warehouse.requests.partials.requests-table', ['requests' => $documentedRequests, 'type' => 'documented'])
    </div>
    
    <!-- في انتظار التوثيق -->
    <div class="tab-pane fade" id="waiting-doc" role="tabpanel">
        @include('warehouse.requests.partials.requests-table', ['requests' => $waitingDocRequests, 'type' => 'waiting-doc'])
    </div>
    
    <!-- في انتظار الموافقة -->
    <div class="tab-pane fade" id="pending" role="tabpanel">
        @include('warehouse.requests.partials.requests-table', ['requests' => $pendingRequests, 'type' => 'pending'])
    </div>
    
    <!-- مرفوضة -->
    <div class="tab-pane fade" id="rejected" role="tabpanel">
        @include('warehouse.requests.partials.requests-table', ['requests' => $rejectedRequests, 'type' => 'rejected'])
    </div>
</div>

<style>
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

.page-title {
    color: var(--text-heading);
    font-weight: 700;
    font-size: 1.5em;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.request-id {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--text-heading);
}

.user-info, .date-info, .items-count {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-main);
}

.status-badge {
    padding: 6px 12px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.85em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.action-buttons {
    display: flex;
    gap: 6px;
}

.action-buttons .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state {
    color: var(--text-muted);
    text-align: center;
}

.empty-state i {
    font-size: 3em;
    margin-bottom: 15px;
    opacity: 0.5;
}

.status-documented {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
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



.nav-tabs {
    border-bottom: 1px solid var(--border);
}
</style>

<script>
function approveRequest(id) {
    if(confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/warehouse/requests/${id}/approve`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PATCH">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectRequest(id) {
    const reason = prompt('اكتب سبب الرفض:');
    if(reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/warehouse/requests/${id}/reject`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="rejection_reason" value="${reason}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection