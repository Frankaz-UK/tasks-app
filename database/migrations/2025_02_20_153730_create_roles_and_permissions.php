<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'task-create',
            'task-delete',
            'task-update',
            'task-complete',
            'task-list',
            'task-show',
            'user-create',
            'user-delete',
            'user-update',
            'user-list',
            'user-show',
        ];

        $roles = [
            'super-admin',
            'admin',
            'user',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $superAdminRole = Role::query()->where('name', 'super-admin')->first();
        $superAdminPermissions = Permission::query()->pluck('id','name')->all();
        $superAdminRole->syncPermissions($superAdminPermissions);

        $adminPermissionsExcept = [
            'task-delete',
            'user-delete',
        ];
        $adminRole = Role::query()->where('name', 'admin')->first();
        $adminPermissions = Permission::query()->whereNotIn('name', $adminPermissionsExcept)->pluck('id','name');
        $adminRole->syncPermissions($adminPermissions);

        $userPermissionsExcept = [
            'task-create',
            'task-delete',
            'task-update',
            'user-create',
            'user-delete',
            'user-list',
        ];
        $userRole = Role::query()->where('name', 'user')->first();
        $userPermissions = Permission::query()->whereNotIn('name', $userPermissionsExcept)->pluck('id','name');
        $userRole->syncPermissions($userPermissions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::query()->truncate();
        Role::query()->truncate();
    }
};
