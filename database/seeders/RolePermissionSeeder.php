<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Permissions ───
        $permissions = [
            // Dashboard
            'view_dashboard',

            // Medicines
            'manage_medicines',
            'view_medicines',
            'import_medicines',
            'export_medicines',

            // Inventory
            'manage_inventory',
            'view_inventory',
            'stock_in',
            'stock_out',
            'adjust_stock',
            'transfer_stock',

            // POS
            'manage_pos',
            'view_pos',
            'create_sales',

            // Sales
            'view_sales',
            'view_sale_details',

            // Customers
            'manage_customers',
            'view_customers',

            // Suppliers
            'manage_suppliers',
            'view_suppliers',

            // Purchases
            'manage_purchases',
            'view_purchases',
            'receive_purchases',

            // Reports
            'view_reports',
            'export_reports',

            // Expiry
            'manage_expiry',
            'view_expiry',

            // Notifications
            'view_notifications',
            'mark_notifications',

            // Users
            'manage_users',
            'view_users',

            // Settings
            'manage_settings',
            'view_settings',

            // Subscription
            'manage_subscription',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ─── Roles ───
        $roles = [
            'super-admin' => $permissions,
            'admin' => [
                'view_dashboard',
                'manage_medicines', 'view_medicines', 'import_medicines', 'export_medicines',
                'manage_inventory', 'view_inventory', 'stock_in', 'stock_out', 'adjust_stock', 'transfer_stock',
                'manage_pos', 'view_pos', 'create_sales',
                'view_sales', 'view_sale_details',
                'manage_customers', 'view_customers',
                'manage_suppliers', 'view_suppliers',
                'manage_purchases', 'view_purchases', 'receive_purchases',
                'view_reports', 'export_reports',
                'manage_expiry', 'view_expiry',
                'view_notifications', 'mark_notifications',
                'view_users',
            ],
            'pharmacist' => [
                'view_dashboard',
                'view_medicines',
                'view_inventory',
                'manage_pos', 'view_pos', 'create_sales',
                'view_sales', 'view_sale_details',
                'manage_customers', 'view_customers',
                'view_expiry',
                'view_notifications', 'mark_notifications',
            ],
            'store-keeper' => [
                'view_dashboard',
                'view_medicines',
                'manage_inventory', 'view_inventory', 'stock_in', 'stock_out', 'adjust_stock', 'transfer_stock',
                'view_suppliers',
                'view_purchases',
                'view_expiry',
                'view_notifications', 'mark_notifications',
            ],
            'accountant' => [
                'view_dashboard',
                'view_sales',
                'view_reports',
                'view_purchases',
                'view_notifications',
            ],
            'viewer' => [
                'view_dashboard',
                'view_medicines',
                'view_inventory',
                'view_sales',
                'view_reports',
                'view_notifications',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }

        // ─── Create Super Admin User ───
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@pharmaflow.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Optionally create a demo pharmacist
        $pharmacist = User::firstOrCreate(
            ['email' => 'pharmacist@pharmaflow.com'],
            [
                'name' => 'Demo Pharmacist',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $pharmacist->assignRole('pharmacist');
    }
}