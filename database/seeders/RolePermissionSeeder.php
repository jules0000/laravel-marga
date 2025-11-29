<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Can create, edit, and delete users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'description' => 'Can create, edit, and delete roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'description' => 'Can create, edit, and delete permissions'],
            ['name' => 'Manage Webpages', 'slug' => 'manage-webpages', 'description' => 'Can create, edit, and delete webpages'],
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'description' => 'Can view the dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create Roles
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full system access',
        ]);

        $editorRole = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
            'description' => 'Can manage webpages',
        ]);

        $viewerRole = Role::create([
            'name' => 'Viewer',
            'slug' => 'viewer',
            'description' => 'Can only view content',
        ]);

        // Assign all permissions to admin
        $adminRole->permissions()->attach(Permission::all()->pluck('id'));

        // Assign specific permissions to editor
        $editorRole->permissions()->attach(
            Permission::whereIn('slug', ['manage-webpages', 'view-dashboard'])->pluck('id')
        );

        // Assign view permission to viewer
        $viewerRole->permissions()->attach(
            Permission::where('slug', 'view-dashboard')->pluck('id')
        );

        // Create default admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole($adminRole);
    }
}

