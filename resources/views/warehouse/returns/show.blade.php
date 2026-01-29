@extends('layouts.app')

@section('title', 'تفاصيل طلب الإرجاع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">
        <i class="bi bi-arrow-return-left"></i> تفاصيل طلب الإرجاع #{{ $return->id }}
    </h2>
    <a href="{{ route('warehouse.returns.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>المنتجات</h5>
            </div>
            <div class="card-body">
                <div class="modern-table">
                    <table>
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($return->items as $item)
                            <tr>
                                <td><strong>{{ $item->product->name }}</strong></td>
                                <td><span class="qty-badge">{{ $item->quantity }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($return->stamped_image)
        <div class="card">
            <div class="card-header">
                <h5>الفاتورة المختومة</h5>
            </div>
            <div class="card-body">
                <div id="imageContainer" style="display: none;">
                    <img id="stampedImage" src="" alt="الفاتورة المختومة" class="img-fluid">
                </div>
                <button id="loadImageBtn" class="btn btn-primary w-100" onclick="loadImage()">
                    <i class="bi bi-image"></i> عرض الفاتورة المختومة
                </button>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5>معلومات الطلب</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>رقم الفاتورة:</strong>
                    <p><code>{{ $return->invoice_number }}</code></p>
                </div>
                <div class="mb-3">
                    <strong>المسوق:</strong>
                    <p>{{ $return->marketer->name }}</p>
                </div>
                <div class="mb-3">
                    <strong>التاريخ:</strong>
                    <p>{{ $return->created_at }}</p>
                </div>
                <div class="mb-3">
                    <strong>الحالة:</strong>
                    <p>
                        <span class="badge-status 
                            @if($return->status == 'pending') badge-warning
                            @elseif($return->status == 'approved') badge-info
                            @elseif($return->status == 'documented') badge-success
                            @else badge-danger
                            @endif">
                            @if($return->status == 'pending') في انتظار الموافقة
                            @elseif($return->status == 'approved') موافق عليه
                            @elseif($return->status == 'documented') موثق
                            @else مرفوض
                            @endif
                        </span>
                    </p>
                </div>
                @if($return->keeper)
                <div class="mb-3">
                    <strong>أمين المخزن:</strong>
                    <p>{{ $return->keeper->full_name ?? $return->keeper->username }}</p>
                </div>
                @endif
                <div class="mb-3">
                    <strong>إجمالي الكميات:</strong>
                    <p>{{ $return->items->sum('quantity') }}</p>
                </div>
            </div>
        </div>
        
        @if($return->approvedBy || $return->documentedBy)
        <div class="card mt-3">
            <div class="card-header">
                <h5>معلومات المعالجة</h5>
            </div>
            <div class="card-body">
                @if($return->approvedBy)
                <div class="mb-3">
                    <strong>وافق عليه:</strong>
                    <p>{{ $return->approvedBy->full_name ?? $return->approvedBy->username }}</p>
                </div>
                @endif
                @if($return->documentedBy)
                <div class="mb-3">
                    <strong>وثقه:</strong>
                    <p>{{ $return->documentedBy->full_name ?? $return->documentedBy->username }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($return->status == 'pending')
        <div class="card">
            <div class="card-body">
                <form action="{{ route('warehouse.returns.approve', $return->id) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle"></i> موافقة
                    </button>
                </form>
                <form action="{{ route('warehouse.returns.reject', $return->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-x-circle"></i> رفض
                    </button>
                </form>
            </div>
        </div>
        @elseif($return->status == 'approved')
        <div class="card">
            <div class="card-body">
                <a href="{{ route('warehouse.returns.upload-document', $return->id) }}" class="btn btn-primary w-100">
                    <i class="bi bi-cloud-upload"></i> رفع الفاتورة المختومة
                </a>
            </div>
        </div>
        @endif
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
}

.modern-table tbody tr:last-child {
    border-bottom: none;
}

.modern-table td {
    padding: 16px 20px;
    text-align: right;
    font-size: 14px;
    color: var(--text-main);
}

.qty-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    background: #d1e7dd;
    color: #0f5132;
}

.badge-status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}

.badge-info {
    background: #cfe2ff;
    color: #084298;
}

.badge-success {
    background: #d1e7dd;
    color: #0f5132;
}

.badge-danger {
    background: #f8d7da;
    color: #842029;
}

code {
    background: #e9ecef;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-family: 'Courier New', monospace;
    color: #495057;
}

[data-theme="dark"] code {
    background: rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
}

@media (max-width: 768px) {
    .row {
        margin-bottom: 60px;
    }
}
</style>

<script>
function loadImage() {
    const imageContainer = document.getElementById('imageContainer');
    const stampedImage = document.getElementById('stampedImage');
    const loadBtn = document.getElementById('loadImageBtn');
    
    stampedImage.src = "{{ asset('storage/' . $return->stamped_image) }}";
    imageContainer.style.display = 'block';
    loadBtn.style.display = 'none';
}
</script>
@endsection
