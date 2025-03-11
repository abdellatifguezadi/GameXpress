<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard permissions
            'view_dashboard',
            
            // Product permissions
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            
            // Category permissions
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            
            // User permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Product Manager
        $productManagerRole = Role::create(['name' => 'product_manager']);
        $productManagerRole->givePermissionTo([
            'view_dashboard',
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
        ]);

        // User Manager
        $userManagerRole = Role::create(['name' => 'user_manager']);
        $userManagerRole->givePermissionTo([
            'view_dashboard',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
        ]);
    }
}
