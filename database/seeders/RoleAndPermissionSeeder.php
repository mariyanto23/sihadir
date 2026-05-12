<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use App\Support\Role as RoleName;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage students',
            'manage attendance',
            'manage leave requests',
            'manage holidays',
            'manage settings',
            'manage users',
            'view reports',
            'submit attendance',
            'submit leave request',
            'view children',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => RoleName::ADMIN]);
        $student = Role::firstOrCreate(['name' => RoleName::STUDENT]);
        $parent = Role::firstOrCreate(['name' => RoleName::PARENT]);

        $admin->syncPermissions($permissions);
        $student->syncPermissions(['submit attendance', 'submit leave request']);
        $parent->syncPermissions(['view children']);

        $user = User::query()->firstOrCreate(
            ['email' => 'admin@hadirku.test'],
            ['name' => 'Administrator HadirKu', 'password' => Hash::make('password')],
        );
        $user->assignRole(RoleName::ADMIN);

        Profile::query()->updateOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name],
        );
    }
}
