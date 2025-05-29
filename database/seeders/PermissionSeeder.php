<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Car management
            'view cars',
            'create cars',
            'edit cars',
            'delete cars',
            
            // Booking management
            'view all bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            'approve bookings',
            'cancel bookings',
            
            // Dashboard and reports
            'view admin dashboard',
            'view reports',
            
            // User permissions
            'make booking',
            'view own bookings',
            'cancel own booking',
            'edit profile',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // User gets limited permissions
        $userRole->givePermissionTo([
            'make booking',
            'view own bookings',
            'cancel own booking',
            'edit profile',
        ]);
    }
}
