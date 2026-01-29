<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\MainStock;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الأدوار
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'مدير', 'description' => 'مدير النظام']);
        $warehouseKeeperRole = Role::create(['name' => 'warehouse_keeper', 'display_name' => 'أمين مخزن', 'description' => 'أمين المخزن']);
        $salesmanRole = Role::create(['name' => 'salesman', 'display_name' => 'مسوق', 'description' => 'مسوق']);

        // إنشاء المستخدمين
        User::create([
            'username' => 'admin',
            'full_name' => 'أحمد المدير',
            'password_hash' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'phone' => '0501234567',
            'is_active' => true
        ]);

        User::create([
            'username' => 'warehouse',
            'full_name' => 'محمد أمين المخزن',
            'password_hash' => Hash::make('password'),
            'role_id' => $warehouseKeeperRole->id,
            'phone' => '0507654321',
            'is_active' => true
        ]);

        User::create([
            'username' => 'salesman',
            'full_name' => 'علي المسوق',
            'password_hash' => Hash::make('password'),
            'role_id' => $salesmanRole->id,
            'phone' => '0509876543',
            'is_active' => true
        ]);

        // إنشاء المنتجات
        $products = [
            ['name' => 'أرز بسمتي', 'description' => 'أرز بسمتي درجة أولى', 'current_price' => 25.50, 'quantity' => 100],
            ['name' => 'سكر أبيض', 'description' => 'سكر أبيض مكرر', 'current_price' => 15.75, 'quantity' => 80],
            ['name' => 'زيت دوار الشمس', 'description' => 'زيت دوار الشمس نقي', 'current_price' => 12.25, 'quantity' => 60],
            ['name' => 'دقيق أبيض', 'description' => 'دقيق أبيض فاخر', 'current_price' => 18.00, 'quantity' => 120],
            ['name' => 'شاي أحمر', 'description' => 'شاي أحمر سيلاني', 'current_price' => 8.50, 'quantity' => 200]
        ];

        foreach ($products as $productData) {
            $quantity = $productData['quantity'];
            unset($productData['quantity']);
            
            $product = Product::create($productData + ['is_active' => true]);
            
            MainStock::create([
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }
    }
}