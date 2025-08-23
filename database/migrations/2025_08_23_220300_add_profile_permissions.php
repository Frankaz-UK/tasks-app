<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{

    private array $permissions = [
        'profile-delete',
        'profile-update',
    ];

    private array $adminPermissionsExcept = [
        'task-delete',
        'user-delete',
    ];

    private array $userPermissionsExcept = [
        'task-create',
        'task-delete',
        'task-update',
        'user-create',
        'user-delete',
        'user-list',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->updatePermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::destroy(['name' => $permission]);
        }

        $this->updatePermissions();
    }

    private function updatePermissions(): void
    {
        $superAdminRole = Role::query()->where('name', 'super-admin')->first();
        $superAdminPermissions = Permission::query()->pluck('id','name')->all();
        $superAdminRole->syncPermissions($superAdminPermissions);

        $adminRole = Role::query()->where('name', 'admin')->first();
        $adminPermissions = Permission::query()->whereNotIn('name', $this->adminPermissionsExcept)->pluck('id','name');
        $adminRole->syncPermissions($adminPermissions);

        $userRole = Role::query()->where('name', 'user')->first();
        $userPermissions = Permission::query()->whereNotIn('name', $this->userPermissionsExcept)->pluck('id','name');
        $userRole->syncPermissions($userPermissions);
    }
};
