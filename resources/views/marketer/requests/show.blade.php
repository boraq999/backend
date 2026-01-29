@extends('layouts.app')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="row">
    <!-- Mobile Actions (First on mobile) -->
    <div class="col-md-4 order-md-2 order-first">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-gear"></i> الإجراءات</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('marketer.requests.index') }}" class="btn btn-secondary w-100 mb-2">
                    <i class="bi bi-arrow-right"></i> رجوع للقائمة
                </a>
                
                @if($request->status && $request->status != 'pending')
                <a href="{{ route('marketer.requests.print', $request->id) }}" class="btn btn-primary w-100 mb-2" target="_blank">
                    <i class="bi bi-printer"></i> طباعة الفاتورة
                </a>
                @endif
                
                @if(!$request->status || $request->status == 'pending')
                    <button type="button" class="btn btn-danger w-100" onclick="cancelRequest({{ $request->id }})">
                        <i class="bi bi-x-circle"></i> إلغاء الطلب
                    </button>
                @endif
            </div>
        </div>
        
        @if($request->status && is_object($request->status) && $request->status->status == 'approved')
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h6><i class="bi bi-check-circle"></i> معلومات الموافقة</h6>
                </div>
                <div class="card-body info-card-body">
                    <p><strong>تم الموافقة في:</strong> {{ $request->status->updated_at }}</p>
                    <p><strong>أمين المخزن:</strong> {{ $request->status->keeper->full_name ?? 'غير محدد' }}</p>
                    <div class="alert alert-success">
                        <small><i class="bi bi-check-circle"></i> تم نقل البضاعة إلى مخزون الحجز</small>
                    </div>
                </div>
            </div>
        @endif
        
        @if($request->status && is_object($request->status) && $request->status->status == 'rejected')
            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h6><i class="bi bi-x-circle"></i> معلومات الرفض</h6>
                </div>
                <div class="card-body info-card-body">
                    <p><strong>تم الرفض في:</strong> {{ $request->status->updated_at }}</p>
                    <p><strong>أمين المخزن:</strong> {{ $request->status->keeper->full_name ?? 'غير محدد' }}</p>
                    @if($request->status->reason)
                        <p><strong>السبب:</strong> {{ $request->status->reason }}</p>
                    @endif
                </div>
            </div>
        @endif
        
        @if($request->status && is_object($request->status) && $request->status->status == 'cancelled')
            <div class="card mt-3">
                <div class="card-header bg-warning text-white">
                    <h6><i class="bi bi-ban"></i> معلومات الإلغاء</h6>
                </div>
                <div class="card-body info-card-body">
                    <p><strong>تم الإلغاء في:</strong> {{ $request->status->updated_at }}</p>
                    <p><strong>أمين المخزن:</strong> {{ $request->status->keeper->full_name ?? 'غير محدد' }}</p>
                    @if($request->status->reason)
                        <p><strong>السبب:</strong> {{ $request->status->reason }}</p>
                    @endif
                    <div class="alert alert-info">
                        <small><i class="bi bi-arrow-return-left"></i> تم إرجاع البضاعة للمخزن الرئيسي</small>
                    </div>
                </div>
            </div>
        @endif
        
        @if($documentInfo)
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6><i class="bi bi-image"></i> معلومات التوثيق</h6>
                </div>
                <div class="card-body info-card-body">
                    <p><strong>تم التوثيق في:</strong> {{ $documentInfo->confirmed_at }}</p>
                    <p class="mb-3"><strong>بواسطة:</strong> أمين المخزن</p>
                    
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="loadRequestImage()">
                            <i class="bi bi-eye"></i> عرض الصورة
                        </button>
                    </div>
                    
                    <div class="alert alert-info">
                        <small><i class="bi bi-check-circle"></i> تم نقل البضاعة إلى المخزن الفعلي</small>
                    </div>
                </div>
            </div>
            
            <!-- Modal لعرض الصورة -->
            <div class="modal fade" id="imageModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">صورة الفاتورة المختومة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="requestImage" src="" 
                                 class="img-fluid" 
                                 alt="صورة الفاتورة"
                                 style="max-height: 70vh; display: none;">
                            <div id="imageLoader" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a id="openImageLink" href="" 
                               target="_blank" 
                               class="btn btn-primary"
                               style="display: none;">
                                <i class="bi bi-box-arrow-up-right"></i> فتح في نافذة جديدة
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Main Content -->
    <div class="col-md-8 order-md-1 order-last">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-file-earmark-text"></i> تفاصيل الطلب #{{ $request->id }} - 
                    <span class="badge fs-6
                        @if(!$request->status || $request->status == 'pending') bg-warning
                        @elseif($request->status == 'approved') bg-success
                        @elseif($request->status == 'rejected') bg-danger
                        @elseif($request->status == 'cancelled') bg-warning
                        @else bg-danger
                        @endif">
                        @if(!$request->status || $request->status == 'pending') في انتظار الموافقة
                        @elseif($request->status == 'approved') موافق عليه
                        @elseif($request->status == 'rejected') مرفوض
                        @elseif($request->status == 'cancelled') ملغى
                        @else مرفوض
                        @endif
                    </span>
                </h4>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-person"></i> المسوق:</strong> {{ $request->marketer->full_name ?? 'غير محدد' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-calendar"></i> تاريخ الطلب:</strong> {{ $request->created_at }}</p>
                    </div>
                </div>

                @if($request->notes)
                    <div class="alert alert-info">
                        <strong><i class="bi bi-sticky"></i> ملاحظات:</strong> {{ $request->notes }}
                    </div>
                @endif

                <h5><i class="bi bi-boxes"></i> المنتجات المطلوبة</h5>
                
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية المطلوبة</th>
                                <th>الوحدة</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($request->items as $item)
                                @php 
                                    $itemTotal = $item->quantity * $item->product->current_price;
                                    $total += $itemTotal;
                                @endphp
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>قطعة</td>
                                    <td>{{ number_format($item->product->current_price, 2) }} ريال</td>
                                    <td>{{ number_format($item->quantity * $item->product->current_price, 2) }} ريال</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <th colspan="4">الإجمالي</th>
                                <th>{{ number_format($total, 2) }} ريال</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="items-cards d-md-none">
                    @php $total = 0; @endphp
                    @foreach($request->items as $item)
                        @php 
                            $itemTotal = $item->quantity * $item->product->current_price;
                            $total += $itemTotal;
                        @endphp
                        <div class="item-card">
                            <div class="item-name">{{ $item->product->name }}</div>
                            <div class="item-row">
                                <span class="item-label">الكمية:</span>
                                <span class="item-value">{{ $item->quantity }} قطعة</span>
                            </div>
                            <div class="item-row">
                                <span class="item-label">السعر:</span>
                                <span class="item-value">{{ number_format($item->product->current_price, 2) }} ريال</span>
                            </div>
                            <div class="item-row item-total">
                                <span class="item-label">الإجمالي:</span>
                                <span class="item-value">{{ number_format($itemTotal, 2) }} ريال</span>
                            </div>
                        </div>
                    @endforeach
                    <div class="total-card">
                        <span class="total-label">الإجمالي الكلي:</span>
                        <span class="total-value">{{ number_format($total, 2) }} ريال</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.items-cards {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.item-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 12px;
}

.item-name {
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 8px;
    font-size: 0.95em;
}

.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.9em;
}

.item-row:last-of-type {
    border-bottom: none;
}

.item-row.item-total {
    border-top: 1px solid var(--border);
    border-bottom: none;
    margin-top: 4px;
    padding-top: 8px;
    font-weight: 600;
    color: var(--primary);
}

.item-label {
    color: var(--text-main);
}

.item-value {
    color: var(--text-heading);
    font-weight: 500;
}

.total-card {
    background: var(--primary);
    color: white;
    border-radius: var(--radius-md);
    padding: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    margin-top: 8px;
}

.total-label {
    font-size: 0.95em;
}

.total-value {
    font-size: 1.1em;
}

.info-card-body {
    padding: 12px;
}

.info-card-body p {
    margin: 0 0 8px 0;
    font-size: 0.9em;
}

.info-card-body p:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .info-card-body {
        padding: 10px;
    }
    
    .info-card-body p {
        font-size: 0.85em;
    }
}
</style>

<script>
function cancelRequest(id) {
    if(confirm('هل أنت متأكد من إلغاء هذا الطلب؟')) {
        window.location.href = `/marketer/requests/${id}/cancel`;
    }
}

let imageLoaded = false;

function loadRequestImage() {
    if (!imageLoaded) {
        const requestImage = document.getElementById('requestImage');
        const imageLoader = document.getElementById('imageLoader');
        const openImageLink = document.getElementById('openImageLink');
        const imagePath = "{{ $documentInfo ? asset('storage/' . $documentInfo->signed_image) : '' }}";
        
        if (!imagePath) return;
        
        requestImage.onload = function() {
            imageLoader.style.display = 'none';
            requestImage.style.display = 'block';
            openImageLink.style.display = 'inline-block';
            imageLoaded = true;
        };
        
        requestImage.src = imagePath;
        openImageLink.href = imagePath;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endsection
