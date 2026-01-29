<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'مدير عام',
                'description' => 'صلاحيات كاملة لإدارة النظام',
                'is_active' => true,
            ],
            [
                'name' => 'warehouse_keeper',
                'display_name' => 'أمين المخزن',
                'description' => 'إدارة المخزون والمنتجات',
                'is_active' => true,
            ],
            [
                'name' => 'salesman',
                'display_name' => 'مسوق',
                'description' => 'إدارة المبيعات والطلبات',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert(array_merge($role, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
