<!-- Desktop Table -->
<div class="table-wrapper d-none d-md-block">
    <div class="modern-table">
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">رقم الطلب</th>
                    <th style="width: 20%;">رقم الفاتورة</th>
                    <th>التاريخ</th>
                    <th>عدد المنتجات</th>
                    <th>عدد الأصناف</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td><strong>#{{ $request->id }}</strong></td>
                        <td><code>{{ $request->invoice_number }}</code></td>
                        <td>{{ $request->created_at }}</td>
                        <td>{{ $request->items->sum('quantity') }}</td>
                        <td>{{ $request->items->count() }}</td>
                        <td>
                            <span class="badge-status 
                                @if($type == 'pending') badge-warning
                                @elseif($type == 'waiting-doc') badge-info
                                @elseif($type == 'documented') badge-success
                                @else badge-danger
                                @endif">
                                @if($type == 'pending') في انتظار الموافقة
                                @elseif($type == 'waiting-doc') في انتظار التوثيق
                                @elseif($type == 'documented') موثق
                                @else مرفوض
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('marketer.requests.show', $request->id) }}" class="btn-action btn-view" title="عرض التفاصيل">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($type == 'pending')
                                    <button class="btn-action btn-delete" onclick="cancelRequest({{ $request->id }})" title="إلغاء الطلب">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-cell">
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
<div class="requests-cards d-md-none">
    @forelse($requests as $request)
        <div class="request-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="bi bi-file-earmark-text"></i>
                    الطلب #{{ $request->id }}
                </div>
                <span class="badge-status 
                    @if($type == 'pending') badge-warning
                    @elseif($type == 'waiting-doc') badge-info
                    @elseif($type == 'documented') badge-success
                    @else badge-danger
                    @endif">
                    @if($type == 'pending') في انتظار الموافقة
                    @elseif($type == 'waiting-doc') في انتظار التوثيق
                    @elseif($type == 'documented') موثق
                    @else مرفوض
                    @endif
                </span>
            </div>
            <div class="card-body">
                <div class="card-info">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ $request->created_at }}</span>
                </div>
                <div class="card-info">
                    <i class="bi bi-boxes"></i>
                    <span>{{ $request->items->count() }} منتج</span>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('marketer.requests.show', $request->id) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye"></i> عرض
                </a>
                @if($type == 'pending')
                    <button class="btn btn-sm btn-danger" onclick="cancelRequest({{ $request->id }})">
                        <i class="bi bi-x-circle"></i> إلغاء
                    </button>
                @endif
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
/* Modern Table Design */
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

/* Action Buttons */
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

.btn-view:hover {
    background: #cce5ff;
}

[data-theme="dark"] .btn-view:hover {
    background: rgba(0, 102, 204, 0.3);
}

.btn-delete {
    background: #ffe7e7;
    color: #dc3545;
}

[data-theme="dark"] .btn-delete {
    background: rgba(220, 53, 69, 0.2);
    color: #ff6b6b;
}

.btn-delete:hover {
    background: #ffcccc;
}

[data-theme="dark"] .btn-delete:hover {
    background: rgba(220, 53, 69, 0.3);
}

/* Status Badges */
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
    color: #fff;
}

.badge-danger {
    background: #f8d7da;
    color: #fff;
}

/* Empty State */
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
.requests-cards {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.request-card {
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
    flex: 1;
    font-size: 13px;
}
</style>
