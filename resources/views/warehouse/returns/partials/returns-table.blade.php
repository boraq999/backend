<!-- Desktop Table -->
<div class="table-wrapper d-none d-md-block">
    <div class="modern-table">
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">رقم الطلب</th>
                    <th style="width: 20%;">رقم الفاتورة</th>
                    <th>المسوق</th>
                    <th>التاريخ</th>
                    <th>عدد المنتجات</th>
                    <th>عدد الأصناف</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                    <tr>
                        <td><strong>#{{ $return->id }}</strong></td>
                        <td><code>{{ $return->invoice_number }}</code></td>
                        <td>{{ $return->marketer->name }}</td>
                        <td>{{ $return->created_at }}</td>
                        <td>{{ $return->items->sum('quantity') }}</td>
                        <td>{{ $return->items->count() }}</td>
                        <td>
                            <span class="badge-status 
                                @if($type == 'pending') badge-warning
                                @elseif($type == 'approved') badge-info
                                @elseif($type == 'documented') badge-success
                                @else badge-danger
                                @endif">
                                @if($type == 'pending') في انتظار الموافقة
                                @elseif($type == 'approved') موافق عليه
                                @elseif($type == 'documented') موثق
                                @else مرفوض
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                @if($type == 'pending')
                                    <form action="{{ route('warehouse.returns.approve', $return->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action btn-approve" title="موافقة">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('warehouse.returns.reject', $return->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action btn-reject" title="رفض">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @elseif($type == 'approved')
                                    <a href="{{ route('warehouse.returns.upload-document', $return->id) }}" class="btn-action btn-upload" title="رفع الفاتورة">
                                        <i class="bi bi-cloud-upload"></i>
                                    </a>
                                @endif
                                <a href="{{ route('warehouse.returns.show', $return->id) }}" class="btn-action btn-view" title="عرض التفاصيل">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-cell">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>لا توجد طلبات في هذه الفئة</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Cards -->
<div class="returns-cards d-md-none">
    @forelse($returns as $return)
        <div class="return-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="bi bi-arrow-return-left"></i>
                    الطلب #{{ $return->id }}
                </div>
                <span class="badge-status 
                    @if($type == 'pending') badge-warning
                    @elseif($type == 'approved') badge-info
                    @elseif($type == 'documented') badge-success
                    @else badge-danger
                    @endif">
                    @if($type == 'pending') في انتظار الموافقة
                    @elseif($type == 'approved') موافق عليه
                    @elseif($type == 'documented') موثق
                    @else مرفوض
                    @endif
                </span>
            </div>
            <div class="card-body">
                <div class="card-info">
                    <i class="bi bi-person"></i>
                    <span>{{ $return->marketer->name }}</span>
                </div>
                <div class="card-info">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ $return->created_at }}</span>
                </div>
                <div class="card-info">
                    <i class="bi bi-boxes"></i>
                    <span>{{ $return->items->count() }} منتج</span>
                </div>
            </div>
            <div class="card-footer">
                @if($type == 'pending')
                    <form action="{{ route('warehouse.returns.approve', $return->id) }}" method="POST" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success w-100">
                            <i class="bi bi-check-circle"></i> موافقة
                        </button>
                    </form>
                    <form action="{{ route('warehouse.returns.reject', $return->id) }}" method="POST" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-danger w-100">
                            <i class="bi bi-x-circle"></i> رفض
                        </button>
                    </form>
                @elseif($type == 'approved')
                    <a href="{{ route('warehouse.returns.upload-document', $return->id) }}" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-cloud-upload"></i> رفع الفاتورة
                    </a>
                @endif
                <a href="{{ route('warehouse.returns.show', $return->id) }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-eye"></i> عرض
                </a>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>لا توجد طلبات في هذه الفئة</p>
        </div>
    @endforelse
</div>

<style>
.modern-table {
    background: var(--bg-card);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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

.modern-table td strong {
    color: var(--text-heading);
    font-weight: 600;
}

.modern-table td code {
    background: #e9ecef;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-family: 'Courier New', monospace;
    color: #495057;
    font-weight: 500;
}

[data-theme="dark"] .modern-table td code {
    background: rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-view {
    background: #e7f3ff;
    color: #0066cc;
}

[data-theme="dark"] .btn-view {
    background: rgba(0, 102, 204, 0.2);
    color: #66b3ff;
}

.btn-approve {
    background: #d1f4e0;
    color: #10b981;
}

[data-theme="dark"] .btn-approve {
    background: rgba(16, 185, 129, 0.2);
    color: #6ee7b7;
}

.btn-reject {
    background: #ffe7e7;
    color: #dc3545;
}

[data-theme="dark"] .btn-reject {
    background: rgba(220, 53, 69, 0.2);
    color: #ff6b6b;
}

.btn-upload {
    background: #fff3cd;
    color: #f59e0b;
}

[data-theme="dark"] .btn-upload {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
}

.badge-status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
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

.returns-cards {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.return-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-secondary);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--text-heading);
    font-size: 14px;
}

.card-body {
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.card-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-main);
    font-size: 13px;
}

.card-info i {
    color: var(--text-muted);
    width: 16px;
}

.card-footer {
    display: flex;
    gap: 8px;
    padding: 12px 16px;
    border-top: 1px solid var(--border);
}

.card-footer .btn {
    font-size: 13px;
}
</style>
