@extends('layouts.app')

@section('title', 'رفع فاتورة الإرجاع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">
        <i class="bi bi-cloud-upload"></i> رفع فاتورة الإرجاع
    </h2>
    <a href="{{ route('warehouse.returns.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>رفع صورة الفاتورة المختومة</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('warehouse.returns.store-document', $return->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">صورة الفاتورة المختومة</label>
                        <input type="file" name="stamped_image" class="form-control" accept="image/*" required>
                        @error('stamped_image')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        بعد رفع الصورة، سيتم تنفيذ حركة المخزون تلقائياً (خصم من مخزون المسوق وإضافة للمخزن الرئيسي)
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> توثيق الإرجاع
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>تفاصيل الطلب</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>رقم الفاتورة:</strong>
                    <p>{{ $return->invoice_number }}</p>
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
                    <strong>المنتجات:</strong>
                    <ul class="list-unstyled">
                        @foreach($return->items as $item)
                            <li>{{ $item->product->name }} - {{ $item->quantity }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
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
</style>
@endsection
