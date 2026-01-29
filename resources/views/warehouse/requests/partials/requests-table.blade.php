<div class="table-wrapper">
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
                @forelse($requests as $request)
                    <tr>
                        <td><strong>#{{ $request->id }}</strong></td>
                        <td><code>{{ $request->invoice_number }}</code></td>
                        <td>{{ $request->marketer->full_name ?? 'غير محدد' }}</td>
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
                                <a href="{{ route('warehouse.requests.show', $request->id) }}" class="btn-action btn-view" title="عرض التفاصيل">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($type == 'pending')
                                    <button class="btn-action btn-approve" onclick="approveRequest({{ $request->id }})" title="موافقة">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button class="btn-action btn-reject" onclick="rejectRequest({{ $request->id }})" title="رفض">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                @elseif($type == 'waiting-doc')
                                    <a href="{{ route('warehouse.requests.upload-document', $request->id) }}" class="btn-action btn-upload" title="توثيق">
                                        <i class="bi bi-camera"></i>
                                    </a>
                                @endif
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

.btn-approve {
    background: #d1f4e0;
    color: #28a745;
}

[data-theme="dark"] .btn-approve {
    background: rgba(40, 167, 69, 0.2);
    color: #5cd67f;
}

.btn-approve:hover {
    background: #b8ecd0;
}

[data-theme="dark"] .btn-approve:hover {
    background: rgba(40, 167, 69, 0.3);
}

.btn-reject {
    background: #ffe7e7;
    color: #dc3545;
}

[data-theme="dark"] .btn-reject {
    background: rgba(220, 53, 69, 0.2);
    color: #ff6b6b;
}

.btn-reject:hover {
    background: #ffcccc;
}

[data-theme="dark"] .btn-reject:hover {
    background: rgba(220, 53, 69, 0.3);
}

.btn-upload {
    background: #fff3cd;
    color: #856404;
}

[data-theme="dark"] .btn-upload {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.btn-upload:hover {
    background: #ffe69c;
}

[data-theme="dark"] .btn-upload:hover {
    background: rgba(255, 193, 7, 0.3);
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
</style>
