<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\MainStock;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'أرز بسمتي', 'description' => 'أرز بسمتي درجة أولى', 'current_price' => 25.50, 'quantity' => 100],
            ['name' => 'سكر أبيض', 'description' => 'سكر أبيض مكرر', 'current_price' => 15.75, 'quantity' => 80],
            ['name' => 'زيت دوار الشمس', 'description' => 'زيت دوار الشمس نقي', 'current_price' => 12.25, 'quantity' => 60],
            ['name' => 'دقيق أبيض', 'description' => 'دقيق أبيض فاخر', 'current_price' => 18.00, 'quantity' => 120],
            ['name' => 'شاي أحمر', 'description' => 'شاي أحمر سيلاني', 'current_price' => 8.50, 'quantity' => 200],
            ['name' => 'معكرونة', 'description' => 'معكرونة إيطالية', 'current_price' => 6.75, 'quantity' => 150],
            ['name' => 'عدس أحمر', 'description' => 'عدس أحمر مقشر', 'current_price' => 9.25, 'quantity' => 90],
            ['name' => 'حمص حب', 'description' => 'حمص حب درجة أولى', 'current_price' => 11.50, 'quantity' => 70]
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