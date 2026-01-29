@extends('layouts.app')

@section('title', 'طلباتي')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">
        <i class="bi bi-list-ul"></i> طلباتي
    </h2>
    
    <a href="{{ route('marketer.requests.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> طلب جديد
    </a>
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
        @include('marketer.requests.partials.requests-table', ['requests' => $documentedRequests, 'type' => 'documented'])
    </div>
    
    <!-- في انتظار التوثيق -->
    <div class="tab-pane fade" id="waiting-doc" role="tabpanel">
        @include('marketer.requests.partials.requests-table', ['requests' => $waitingDocRequests, 'type' => 'waiting-doc'])
    </div>
    
    <!-- في انتظار الموافقة -->
    <div class="tab-pane fade" id="pending" role="tabpanel">
        @include('marketer.requests.partials.requests-table', ['requests' => $pendingRequests, 'type' => 'pending'])
    </div>
    
    <!-- مرفوضة -->
    <div class="tab-pane fade" id="rejected" role="tabpanel">
        @include('marketer.requests.partials.requests-table', ['requests' => $rejectedRequests, 'type' => 'rejected'])
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

.date-info, .items-count {
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

.empty-state p {
    margin: 0;
    font-size: 1.1em;
}
</style>

<script>
function cancelRequest(id) {
    if(confirm('هل أنت متأكد من إلغاء هذا الطلب؟')) {
        window.location.href = `/marketer/requests/${id}/cancel`;
    }
}
</script>
@endsection