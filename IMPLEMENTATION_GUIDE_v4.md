# 🛠️ دليل التنفيذ التفصيلي v4 - Taqnia Distribution Manager

## 📋 القاعدة الأساسية المحدثة

### 🧠 المبدأ الأساسي
**أي عملية داخل النظام يجب أن تكون:**
- ✅ مرتبطة بفاتورة أو طلب رسمي
- ✅ موثقة بصورة (ختم / توقيع)
- ✅ معتمدة من أمين المخزن

**ممنوع منعاً باتاً:**
- ❌ الاعتماد الشفهي
- ❌ التعديل المباشر
- ❌ الحساب المالي بدون توثيق

---

## 🗃️ تصميم قاعدة البيانات المحدث (26 جدول)

### 1. الجداول الأساسية (3)

#### users (المستخدمين)
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'warehouse_keeper', 'salesman') NOT NULL,
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### products (المنتجات)
```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    current_price DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### stores (المتاجر)
```sql
CREATE TABLE stores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. جداول المخزون (5)

#### main_stock (المخزن الرئيسي)
```sql
CREATE TABLE main_stock (
    product_id INT PRIMARY KEY,
    quantity INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### marketer_reserved_stock (مخزون الحجز)
```sql
CREATE TABLE marketer_reserved_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marketer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (marketer_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### marketer_actual_stock (مخزون المسوق الفعلي)
```sql
CREATE TABLE marketer_actual_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marketer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (marketer_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### store_actual_stock (مخزون المتجر الفعلي)
```sql
CREATE TABLE store_actual_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    store_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (store_id) REFERENCES stores(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### store_pending_stock (مخزون المتجر المرحلي) - جديد
```sql
CREATE TABLE store_pending_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    store_id INT NOT NULL,
    sales_invoice_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (store_id) REFERENCES stores(id),
    FOREIGN KEY (sales_invoice_id) REFERENCES sales_invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

### 3. جداول الفواتير (6)

#### factory_invoices (فواتير المصنع)
```sql
CREATE TABLE factory_invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    keeper_id INT NOT NULL,
    factory_manager_id INT,
    stamped_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (keeper_id) REFERENCES users(id),
    FOREIGN KEY (factory_manager_id) REFERENCES users(id)
);
```

#### factory_invoice_items (تفاصيل فواتير المصنع)
```sql
CREATE TABLE factory_invoice_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES factory_invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### marketer_requests (طلبات المسوقين)
```sql
CREATE TABLE marketer_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    marketer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marketer_id) REFERENCES users(id)
);
```

#### marketer_request_items (تفاصيل طلبات المسوقين)
```sql
CREATE TABLE marketer_request_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (request_id) REFERENCES marketer_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### sales_invoices (فواتير البيع)
```sql
CREATE TABLE sales_invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    marketer_id INT NOT NULL,
    store_id INT NOT NULL,
    status ENUM('pending', 'approved', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marketer_id) REFERENCES users(id),
    FOREIGN KEY (store_id) REFERENCES stores(id)
);
```

#### sales_invoice_items (تفاصيل فواتير البيع)
```sql
CREATE TABLE sales_invoice_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES sales_invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

### 4. جداول التوثيق والحالة (6)

#### marketer_request_status (حالة الطلب)
```sql
CREATE TABLE marketer_request_status (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    marketer_id INT NOT NULL,
    keeper_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES marketer_requests(id),
    FOREIGN KEY (marketer_id) REFERENCES users(id),
    FOREIGN KEY (keeper_id) REFERENCES users(id)
);
```

#### delivery_confirmation (توثيق الاستلام)
```sql
CREATE TABLE delivery_confirmation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    keeper_id INT NOT NULL,
    signed_image VARCHAR(255),
    confirmed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES marketer_requests(id),
    FOREIGN KEY (keeper_id) REFERENCES users(id)
);
```

#### sales_confirmation (توثيق المبيعات)
```sql
CREATE TABLE sales_confirmation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sales_invoice_id INT NOT NULL,
    keeper_id INT NOT NULL,
    stamped_invoice_image VARCHAR(255) NOT NULL,
    confirmed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sales_invoice_id) REFERENCES sales_invoices(id),
    FOREIGN KEY (keeper_id) REFERENCES users(id)
);
```

#### sales_return_confirmation (توثيق الإرجاع) - جديد
```sql
CREATE TABLE sales_return_confirmation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    return_id INT NOT NULL,
    keeper_id INT NOT NULL,
    stamped_image VARCHAR(255),
    confirmed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (return_id) REFERENCES sales_returns(id),
    FOREIGN KEY (keeper_id) REFERENCES users(id)
);
```

#### warehouse_stock_logs (سجل حركات المخزن)
```sql
CREATE TABLE warehouse_stock_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_type ENUM('factory', 'marketer_request', 'sales_return') NOT NULL,
    invoice_id INT NOT NULL,
    keeper_id INT NOT NULL,
    action ENUM('add', 'withdraw', 'return') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (keeper_id) REFERENCES users(id)
);
```

### 5. جداول المالية (5)

#### store_debt_ledger (دفتر الذمم)
```sql
CREATE TABLE store_debt_ledger (
    id INT PRIMARY KEY AUTO_INCREMENT,
    store_id INT NOT NULL,
    entry_type ENUM('sale', 'return') NOT NULL,
    reference_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (store_id) REFERENCES stores(id)
);
```

#### store_payments (تسديدات المتاجر)
```sql
CREATE TABLE store_payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    payment_number VARCHAR(50) UNIQUE NOT NULL,
    store_id INT NOT NULL,
    marketer_id INT NOT NULL,
    keeper_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    receipt_image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (store_id) REFERENCES stores(id),
    FOREIGN KEY (marketer_id) REFERENCES users(id),
    FOREIGN KEY (keeper_id) REFERENCES users(id)
);
```

#### marketer_commissions (عمولات المسوقين) - جديد
```sql
CREATE TABLE marketer_commissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marketer_id INT NOT NULL,
    payment_id INT NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    commission_amount DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marketer_id) REFERENCES users(id),
    FOREIGN KEY (payment_id) REFERENCES store_payments(id)
);
```

#### marketer_withdrawal_requests (طلبات سحب الأرباح) - جديد
```sql
CREATE TABLE marketer_withdrawal_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marketer_id INT NOT NULL,
    requested_amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marketer_id) REFERENCES users(id)
);
```

#### marketer_withdrawals (سحوبات الأرباح) - جديد
```sql
CREATE TABLE marketer_withdrawals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    withdrawal_request_id INT NOT NULL,
    marketer_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    keeper_id INT NOT NULL,
    signed_receipt_image VARCHAR(255) NOT NULL,
    confirmed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (withdrawal_request_id) REFERENCES marketer_withdrawal_requests(id),
    FOREIGN KEY (marketer_id) REFERENCES users(id),
    FOREIGN KEY (keeper_id) REFERENCES users(id)
);
```

### 6. جداول الإرجاع (3)

#### sales_returns (إرجاعات المبيعات)
```sql
CREATE TABLE sales_returns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sales_invoice_id INT NOT NULL,
    store_id INT NOT NULL,
    marketer_id INT NOT NULL,
    status ENUM('pending', 'approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sales_invoice_id) REFERENCES sales_invoices(id),
    FOREIGN KEY (store_id) REFERENCES stores(id),
    FOREIGN KEY (marketer_id) REFERENCES users(id)
);
```

#### sales_return_items (تفاصيل الإرجاعات)
```sql
CREATE TABLE sales_return_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    return_id INT NOT NULL,
    sales_invoice_item_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (return_id) REFERENCES sales_returns(id) ON DELETE CASCADE,
    FOREIGN KEY (sales_invoice_item_id) REFERENCES sales_invoice_items(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## 🔄 العمليات الجديدة والمحدثة

### العملية الجديدة: إدارة الأرباح والعمولات

#### 1. حساب العمولة عند التسديد
```php
public function calculateCommission($paymentId)
{
    $payment = StorePayment::find($paymentId);
    $commissionRate = 0.05; // 5% مثال
    $commissionAmount = $payment->amount * $commissionRate;
    
    MarketerCommission::create([
        'marketer_id' => $payment->marketer_id,
        'payment_id' => $paymentId,
        'commission_rate' => $commissionRate,
        'commission_amount' => $commissionAmount
    ]);
}
```

#### 2. طلب سحب الأرباح
```php
public function requestWithdrawal($marketerId, $amount)
{
    // التحقق من الرصيد المتاح
    $availableBalance = $this->getAvailableBalance($marketerId);
    
    if ($amount > $availableBalance) {
        throw new Exception('المبلغ المطلوب أكبر من الرصيد المتاح');
    }
    
    return MarketerWithdrawalRequest::create([
        'marketer_id' => $marketerId,
        'requested_amount' => $amount,
        'status' => 'pending'
    ]);
}
```

#### 3. توثيق سحب الأرباح
```php
public function confirmWithdrawal($requestId, $keeperId, $signedImage)
{
    $request = MarketerWithdrawalRequest::find($requestId);
    
    // تسجيل السحب الفعلي
    MarketerWithdrawal::create([
        'withdrawal_request_id' => $requestId,
        'marketer_id' => $request->marketer_id,
        'amount' => $request->requested_amount,
        'keeper_id' => $keeperId,
        'signed_receipt_image' => $signedImage
    ]);
    
    // تحديث حالة الطلب
    $request->update(['status' => 'approved']);
}
```

### العملية المحدثة: المخزون المرحلي

#### إنشاء فاتورة بيع مع المخزون المرحلي
```php
public function createSalesInvoice($salesData)
{
    $invoice = SalesInvoice::create([
        'invoice_number' => $this->generateInvoiceNumber(),
        'marketer_id' => $salesData['marketer_id'],
        'store_id' => $salesData['store_id'],
        'status' => 'pending'
    ]);

    foreach ($salesData['items'] as $item) {
        $product = Product::find($item['product_id']);
        
        // إضافة تفاصيل البيع
        SalesInvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $product->current_price,
            'total_price' => $item['quantity'] * $product->current_price
        ]);

        // خصم من مخزون المسوق
        $this->updateMarketerStock($salesData['marketer_id'], $item['product_id'], $item['quantity'], 'withdraw');
        
        // إضافة للمخزون المرحلي (غير مُلزم)
        StorePendingStock::create([
            'store_id' => $salesData['store_id'],
            'sales_invoice_id' => $invoice->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity']
        ]);
    }

    return $invoice;
}
```

---

## 🎯 APIs الجديدة المطلوبة

### APIs الأرباح والعمولات
```php
// عرض أرباح المسوق
Route::get('/api/marketer/{id}/commissions', [CommissionController::class, 'getMarketerCommissions']);

// طلب سحب أرباح
Route::post('/api/marketer/withdrawal-request', [WithdrawalController::class, 'createRequest']);

// عرض طلبات السحب
Route::get('/api/withdrawal-requests', [WithdrawalController::class, 'getRequests']);

// موافقة على طلب سحب
Route::put('/api/withdrawal-requests/{id}/approve', [WithdrawalController::class, 'approveRequest']);

// توثيق استلام الأرباح
Route::post('/api/withdrawals/{id}/confirm', [WithdrawalController::class, 'confirmWithdrawal']);

// عرض تاريخ السحوبات
Route::get('/api/marketer/{id}/withdrawals', [WithdrawalController::class, 'getMarketerWithdrawals']);
```

### APIs المخزون المرحلي
```php
// عرض المخزون المرحلي للمتجر
Route::get('/api/store/{id}/pending-stock', [StockController::class, 'getStorePendingStock']);

// عرض إجمالي المخزون المرحلي
Route::get('/api/pending-stock/summary', [StockController::class, 'getPendingStockSummary']);

// تقرير تحويل Pending إلى Approved
Route::get('/api/reports/pending-conversion', [ReportController::class, 'getPendingConversionReport']);
```

---

## 📊 التقارير الجديدة

### تقارير الأرباح
- إجمالي العمولات المستحقة لكل مسوق
- العمولات المسحوبة شهرياً
- الرصيد المتاح للسحب
- معدل السحب الشهري

### تقارير المخزون المرحلي
- إجمالي المخزون المرحلي لكل متجر
- معدل تحويل Pending إلى Approved
- المخزون المرحلي حسب المنتج
- تقرير الفواتير المعلقة

---

## 🚀 خطة التنفيذ المحدثة

### المرحلة 1: إعداد الجداول الجديدة (أسبوع 1)
- إنشاء migrations للجداول الـ 4 الجديدة
- تحديث الجداول الموجودة
- إنشاء Models وتحديث العلاقات

### المرحلة 2: APIs الأساسية (أسبوع 2-3)
- APIs المخزون المرحلي
- APIs العمولات الأساسية
- تحديث APIs البيع الموجودة

### المرحلة 3: نظام الأرباح (أسبوع 4-5)
- APIs طلبات السحب
- APIs توثيق السحب
- حساب الأرصدة المتاحة

### المرحلة 4: الواجهات والتقارير (أسبوع 6-7)
- واجهات إدارة الأرباح
- تقارير المخزون المرحلي
- تقارير الأرباح والعمولات

---

**تاريخ الإنشاء**: ديسمبر 2024  
**الإصدار**: v4.0  
**الحالة**: جاهز للتنفيذ