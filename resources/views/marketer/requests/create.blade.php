@extends('layouts.app')

@section('title', 'طلب بضاعة جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-plus"></i> طلب بضاعة جديد</h4>
            </div>
            
            <div class="card-body">
                <form action="{{ route('marketer.requests.store') }}" method="POST" id="requestForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">المنتجات المطلوبة</label>
                        <div id="products-container">
                            <div class="product-row border rounded p-3 mb-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="form-label">المنتج</label>
                                        <select name="products[0][product_id]" class="form-select product-select" required>
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-stock="{{ $product->mainStock->quantity ?? 0 }}">
                                                    {{ $product->name }} (متوفر: {{ $product->mainStock->quantity ?? 0 }}) - {{ number_format($product->current_price, 2) }} ريال
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">الكمية</label>
                                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-product" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="mb-3">
                        <button type="button" id="add-product" class="btn btn-outline-primary">
                            <i class="bi bi-plus"></i> إضافة منتج آخر
                        </button>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('marketer.requests.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-right"></i> رجوع
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> إرسال الطلب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let productIndex = 1;

document.getElementById('add-product').addEventListener('click', function() {
    const container = document.getElementById('products-container');
    const newRow = document.createElement('div');
    newRow.className = 'product-row border rounded p-3 mb-2';
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-8">
                <label class="form-label">المنتج</label>
                <select name="products[${productIndex}][product_id]" class="form-select product-select" required>
                    <option value="">اختر المنتج</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->mainStock->quantity ?? 0 }}">
                            {{ $product->name }} (متوفر: {{ $product->mainStock->quantity ?? 0 }}) - {{ number_format($product->current_price, 2) }} ريال
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الكمية</label>
                <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" min="1" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-product">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    productIndex++;
    updateRemoveButtons();
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product') || e.target.parentElement.classList.contains('remove-product')) {
        const row = e.target.closest('.product-row');
        row.remove();
        updateRemoveButtons();
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.product-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-product');
        removeBtn.disabled = rows.length === 1;
    });
}

// التحقق من الكمية المتاحة
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('quantity-input')) {
        const row = e.target.closest('.product-row');
        const productSelect = row.querySelector('.product-select');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (selectedOption && selectedOption.dataset.stock) {
            const availableStock = parseInt(selectedOption.dataset.stock);
            const requestedQuantity = parseInt(e.target.value);
            
            if (requestedQuantity > availableStock) {
                alert(`الكمية المطلوبة (${requestedQuantity}) أكبر من المتوفر (${availableStock})`);
                e.target.value = availableStock;
            }
        }
    }
});
</script>
@endsection