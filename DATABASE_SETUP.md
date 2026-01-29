# إعداد قاعدة البيانات - Taqnia Distribution Manager

## الجداول المُنشأة (26 جدول)

### 1. الجداول الأساسية (3)
- `users` - المستخدمين
- `products` - المنتجات  
- `stores` - المتاجر

### 2. جداول المخزون (5)
- `main_stock` - المخزن الرئيسي
- `marketer_reserved_stock` - مخزون الحجز
- `marketer_actual_stock` - مخزون المسوق الفعلي
- `store_actual_stock` - مخزون المتجر الفعلي
- `store_pending_stock` - مخزون المتجر المرحلي

### 3. جداول الفواتير (6)
- `factory_invoices` - فواتير المصنع
- `factory_invoice_items` - تفاصيل فواتير المصنع
- `marketer_requests` - طلبات المسوقين
- `marketer_request_items` - تفاصيل طلبات المسوقين
- `sales_invoices` - فواتير البيع
- `sales_invoice_items` - تفاصيل فواتير البيع

### 4. جداول التوثيق والحالة (6)
- `marketer_request_status` - حالة الطلب
- `delivery_confirmation` - توثيق الاستلام
- `sales_confirmation` - توثيق المبيعات
- `sales_return_confirmation` - توثيق الإرجاع
- `warehouse_stock_logs` - سجل حركات المخزن

### 5. جداول المالية (5)
- `store_debt_ledger` - دفتر الذمم
- `store_payments` - تسديدات المتاجر
- `marketer_commissions` - عمولات المسوقين
- `marketer_withdrawal_requests` - طلبات سحب الأرباح
- `marketer_withdrawals` - سحوبات الأرباح

### 6. جداول الإرجاع (3)
- `sales_returns` - إرجاعات المبيعات
- `sales_return_items` - تفاصيل الإرجاعات

## كيفية الإعداد

### الطريقة السريعة
```bash
# تشغيل ملف الإعداد التلقائي
setup_database.bat
```

### الطريقة اليدوية
```bash
# تشغيل الـ migrations
php artisan migrate:fresh

# إدخال البيانات الأولية
php artisan db:seed --class=TaqniaSeeder
```

## المستخدمين الافتراضيين

بعد تشغيل الـ seeder، سيتم إنشاء المستخدمين التاليين:

| الدور | اسم المستخدم | كلمة المرور | الاسم الكامل |
|-------|-------------|------------|-------------|
| Admin | admin | admin123 | مدير النظام |
| Warehouse Keeper | keeper1 | keeper123 | أمين المخزن الأول |
| Salesman | salesman1 | sales123 | المسوق الأول |

## البيانات التجريبية

- 3 منتجات (منتج أ، منتج ب، منتج ج)
- 3 متاجر (متجر الشرق، متجر الغرب، متجر الشمال)
- مخزون رئيسي أولي للمنتجات

## ملاحظات مهمة

1. **التوثيق الإلزامي**: جميع العمليات تتطلب توثيق بالصور
2. **المبدأ الأساسي**: لا توجد عمليات شفهية - كل شيء موثق
3. **المخزون المرحلي**: نظام جديد لتتبع المبيعات غير المؤكدة
4. **نظام العمولات**: حساب تلقائي للعمولات عند التسديد

## الخطوات التالية

1. إنشاء Models للجداول
2. إنشاء Controllers للـ APIs
3. إنشاء Routes للواجهات
4. تطوير واجهات المستخدم

---
**تاريخ الإنشاء**: ديسمبر 2024  
**الإصدار**: v4.0