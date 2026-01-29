@extends('layouts.app')

@section('title', 'توثيق الطلب')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-camera"></i> توثيق الطلب #{{ $request->id }}</h4>
            </div>
            
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    قم برفع صورة الفاتورة المختومة لتوثيق العملية ونقل البضاعة للمخزن الفعلي للمسوق
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>المسوق:</strong> {{ $request->marketer->full_name }}</p>
                        <p><strong>عدد المنتجات:</strong> {{ $request->items->count() }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>تاريخ الطلب:</strong> {{ $request->created_at }}</p>
                        <p><strong>الحالة:</strong> <span class="badge bg-info">{{ $request->status_text }}</span></p>
                    </div>
                </div>

                <form action="{{ route('warehouse.requests.store-document', $request->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="document_image" class="form-label">صورة الفاتورة المختومة</label>
                        <input type="file" class="form-control @error('document_image') is-invalid @enderror" 
                               id="document_image" name="document_image" accept="image/*" required>
                        @error('document_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">الحد الأقصى: 2MB - الصيغ المدعومة: JPG, PNG</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('warehouse.requests.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-right"></i> رجوع
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-upload"></i> رفع الصورة وتوثيق الطلب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection