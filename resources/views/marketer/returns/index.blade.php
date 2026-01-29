@extends('layouts.app')

@section('title', 'إرجاع بضاعة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">
        <i class="bi bi-arrow-return-left"></i> إرجاع بضاعة
    </h2>
    
    <a href="{{ route('marketer.returns.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> طلب إرجاع جديد
    </a>
</div>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#documented" type="button" role="tab">
            <i class="bi bi-check-circle"></i> موثق ({{ $documentedCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
            <i class="bi bi-clipboard-check"></i> في انتظار التوثيق ({{ $approvedCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
            <i class="bi bi-hourglass-split"></i> في انتظار الموافقة ({{ $pendingCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
            <i class="bi bi-x-circle"></i> مرفوضة ({{ $rejectedCount }})
        </button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="documented" role="tabpanel">
        @include('marketer.returns.partials.returns-table', ['returns' => $documentedReturns, 'type' => 'documented'])
    </div>
    
    <div class="tab-pane fade" id="approved" role="tabpanel">
        @include('marketer.returns.partials.returns-table', ['returns' => $approvedReturns, 'type' => 'approved'])
    </div>
    
    <div class="tab-pane fade" id="pending" role="tabpanel">
        @include('marketer.returns.partials.returns-table', ['returns' => $pendingReturns, 'type' => 'pending'])
    </div>
    
    <div class="tab-pane fade" id="rejected" role="tabpanel">
        @include('marketer.returns.partials.returns-table', ['returns' => $rejectedReturns, 'type' => 'rejected'])
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
</style>
@endsection
