<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TaqniaSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء المستخدمين الأساسيين
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'password_hash' => Hash::make('admin123'),
                'full_name' => 'مدير النظام',
                'role' => 'admin',
                'phone' => '0501234567',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'keeper1',
                'password_hash' => Hash::make('keeper123'),
                'full_name' => 'أمين المخزن الأول',
                'role' => 'warehouse_keeper',
                'phone' => '0507654321',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'salesman1',
                'password_hash' => Hash::make('sales123'),
                'full_name' => 'المسوق الأول',
                'role' => 'salesman',
                'phone' => '0509876543',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // إنشاء منتجات تجريبية
        DB::table('products')->insert([
            [
                'name' => 'منتج أ',
                'barcode' => '1234567890123',
                'description' => 'منتج عالي الجودة من فئة أ',
                'category' => 'فئة أ',
                'current_price' => 100.00,
                'cost_price' => 75.00,
                'min_stock_alert' => 50,
                'unit' => 'قطعة',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'منتج ب',
                'barcode' => '1234567890124',
                'description' => 'منتج ممتاز من فئة ب',
                'category' => 'فئة ب',
                'current_price' => 150.00,
                'cost_price' => 110.00,
                'min_stock_alert' => 30,
                'unit' => 'علبة',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'منتج ج',
                'barcode' => '1234567890125',
                'description' => 'منتج فاخر من فئة ج',
                'category' => 'فئة ج',
                'current_price' => 200.00,
                'cost_price' => 140.00,
                'min_stock_alert' => 20,
                'unit' => 'صندوق',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // إنشاء متاجر تجريبية
        DB::table('stores')->insert([
            [
                'name' => 'متجر الشرق',
                'owner_name' => 'أحمد محمد',
                'phone' => '0501234567',
                'location' => 'الرياض - حي الملز',
                'address' => 'شارع الملك فهد، مجمع الملز التجاري',
                'credit_limit' => 50000.00,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'متجر الغرب',
                'owner_name' => 'سالم العتيبي',
                'phone' => '0507654321',
                'location' => 'جدة - حي الصفا',
                'address' => 'طريق الملك عبدالعزيز، برج الصفا',
                'credit_limit' => 75000.00,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'متجر الشمال',
                'owner_name' => 'فهد الشمري',
                'phone' => '0509876543',
                'location' => 'الدمام - حي الفيصلية',
                'address' => 'شارع الأمير محمد بن فهد',
                'credit_limit' => 30000.00,
                'is_active' => true,
                'created_at' => now(),
            ]
        ]);

        // إنشاء مخزون رئيسي أولي
        DB::table('main_stock')->insert([
            [
                'product_id' => 1,
                'quantity' => 1000,
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'quantity' => 800,
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'quantity' => 600,
                'updated_at' => now(),
            ]
        ]);
    }
}