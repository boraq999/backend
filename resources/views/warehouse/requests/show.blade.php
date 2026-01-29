@extends('layouts.app')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-file-earmark-text"></i> تفاصيل الطلب #{{ $request->id }} - 
                    <span class="badge fs-6
                        @if(!$request->status || $request->status->status == 'pending') bg-warning
                        @elseif($request->status->status == 'rejected') bg-danger
                        @elseif($request->status->status == 'cancelled') bg-warning
                        @else bg-danger
                        @endif">
                        @if(!$request->status || $request->status->status == 'pending') قيد المراجعة
                        @elseif($request->status->status == 'approved') موافق عليه
                        @elseif($request->status->status == 'rejected') مرفوض
                        @elseif($request->status->status == 'cancelled') ملغى
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
                <div class="table-responsive">
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
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-gear"></i> الإجراءات</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('warehouse.requests.index') }}" class="btn btn-secondary w-100 mb-2">
                    <i class="bi bi-arrow-right"></i> رجوع للقائمة
                </a>
                
                @if(!$request->status || $request->status->status == 'pending')
                    <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="bi bi-check"></i> موافقة
                    </button>
                    
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x"></i> رفض
                    </button>
                @endif
                
                @if($request->status && $request->status->status == 'approved' && !$documentInfo)
                    <a href="{{ route('warehouse.requests.upload-document', $request->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-camera"></i> توثيق الفاتورة
                    </a>
                    
                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="bi bi-ban"></i> إلغاء الطلب
                    </button>
                @endif
            </div>
        </div>
        
        @if($request->status && $request->status->status == 'rejected')
            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h6><i class="bi bi-x-circle"></i> معلومات الرفض</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>تم الرفض في:</strong></p>
                    <p>{{ $request->status->updated_at }}</p>
                    <p class="mb-1"><strong>أمين المخزن:</strong></p>
                    <p>{{ $request->status->keeper->full_name ?? 'غير محدد' }}</p>
                    @if($request->status->reason)
                        <p class="mb-1"><strong>سبب الرفض:</strong></p>
                        <div class="alert alert-danger">
                            {{ $request->status->reason }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        @if($request->status && $request->status->status == 'cancelled')
            <div class="card mt-3">
                <div class="card-header bg-warning text-white">
                    <h6><i class="bi bi-ban"></i> معلومات الإلغاء</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>تم الإلغاء في:</strong></p>
                    <p>{{ $request->status->updated_at }}</p>
                    <p class="mb-1"><strong>أمين المخزن:</strong></p>
                    <p>{{ $request->status->keeper->full_name ?? 'غير محدد' }}</p>
                    @if($request->status->reason)
                        <p class="mb-1"><strong>سبب الإلغاء:</strong></p>
                        <div class="alert alert-warning">
                            {{ $request->status->reason }}
                        </div>
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
                <div class="card-body">
                    <p class="mb-2"><strong>تم التوثيق في:</strong> {{ $documentInfo->confirmed_at }}</p>
                    <p class="mb-3"><strong>بواسطة:</strong> أمين المخزن</p>
                    
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary" onclick="loadWarehouseRequestImage()">
                            <i class="bi bi-eye"></i> عرض صورة الفاتورة
                        </button>
                    </div>
                    
                    <div class="alert alert-info">
                        <small><i class="bi bi-check-circle"></i> تم نقل البضاعة إلى المخزن الفعلي للمسوق</small>
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
                            <img id="warehouseRequestImage" src="" 
                                 class="img-fluid" 
                                 alt="صورة الفاتورة"
                                 style="max-height: 70vh; display: none;">
                            <div id="warehouseImageLoader" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a id="warehouseOpenImageLink" href="" 
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
</div>

<!-- Modal للموافقة -->
@if(!$request->status || $request->status->status == 'pending')
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الموافقة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من الموافقة على هذا الطلب؟</p>
                <p class="text-warning"><i class="bi bi-exclamation-triangle"></i> سيتم خصم الكميات من المخزن الرئيسي ونقلها للمخزن المحجوز للمسوق.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('warehouse.requests.approve', $request->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">تأكيد الموافقة</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal للرفض -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفض الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('warehouse.requests.reject', $request->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">سبب الرفض</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required placeholder="اكتب سبب رفض الطلب..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal لإلغاء الطلب -->
@if($request->status && $request->status->status == 'approved' && !$documentInfo)
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إلغاء الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('warehouse.requests.cancel', $request->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        <strong>تحذير:</strong> سيتم إلغاء الطلب وإرجاع الكمية من مخزن المسوق المحجوز إلى المخزن الرئيسي.
                    </div>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">سبب الإلغاء</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3" required placeholder="اكتب سبب إلغاء الطلب..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">تأكيد الإلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif

<script>
let warehouseImageLoaded = false;

function loadWarehouseRequestImage() {
    if (!warehouseImageLoaded) {
        const requestImage = document.getElementById('warehouseRequestImage');
        const imageLoader = document.getElementById('warehouseImageLoader');
        const openImageLink = document.getElementById('warehouseOpenImageLink');
        const imagePath = "{{ asset('storage/' . $documentInfo->signed_image) }}";
        
        requestImage.onload = function() {
            imageLoader.style.display = 'none';
            requestImage.style.display = 'block';
            openImageLink.style.display = 'inline-block';
            warehouseImageLoaded = true;
        };
        
        requestImage.src = imagePath;
        openImageLink.href = imagePath;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endsection