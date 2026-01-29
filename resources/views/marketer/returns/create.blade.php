@extends('layouts.app')

@section('title', 'طلب إرجاع جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">
        <i class="bi bi-arrow-return-left"></i> طلب إرجاع جديد
    </h2>
    <a href="{{ route('marketer.returns.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('marketer.returns.store') }}" method="POST" id="returnForm">
            @csrf
            
            <div class="mb-4">
                <h5>اختر المنتجات للإرجاع</h5>
                <p class="text-muted">يمكنك إرجاع المنتجات من مخزونك الفعلي فقط</p>
            </div>

            <div id="productsContainer">
                <div class="product-row mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">المنتج</label>
                            <select name="products[0][product_id]" class="form-select product-select" required>
                                <option value="">اختر المنتج</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-max="{{ $product->available_quantity }}">
                                        {{ $product->name }} (متوفر: {{ $product->available_quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">الكمية</label>
                            <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-product" style="display: none;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-4" id="addProduct">
                <i class="bi bi-plus-circle"></i> إضافة منتج آخر
            </button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> إنشاء طلب الإرجاع
                </button>
                <a href="{{ route('marketer.returns.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
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

.product-row {
    padding: 15px;
    background: var(--bg-secondary);
    border-radius: 8px;
}
</style>

<script>
let productIndex = 1;

document.getElementById('addProduct').addEventListener('click', function() {
    const container = document.getElementById('productsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'product-row mb-3';
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">المنتج</label>
                <select name="products[${productIndex}][product_id]" class="form-select product-select" required>
                    <option value="">اختر المنتج</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-max="{{ $product->available_quantity }}">
                            {{ $product->name }} (متوفر: {{ $product->available_quantity }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
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

document.getElementById('productsContainer').addEventListener('click', function(e) {
    if (e.target.closest('.remove-product')) {
        e.target.closest('.product-row').remove();
        updateRemoveButtons();
    }
});

document.getElementById('productsContainer').addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const option = e.target.options[e.target.selectedIndex];
        const max = option.dataset.max;
        const quantityInput = e.target.closest('.row').querySelector('.quantity-input');
        quantityInput.max = max;
        quantityInput.value = '';
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.product-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-product');
        if (rows.length > 1) {
            removeBtn.style.display = 'block';
        } else {
            removeBtn.style.display = 'none';
        }
    });
}
</script>
@endsection
