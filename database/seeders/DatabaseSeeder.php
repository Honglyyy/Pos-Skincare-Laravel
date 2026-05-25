<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call ShieldSeeder to create permissions first
        $this->call([
            ShieldSeeder::class,
        ]);

        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => '123',
        ]);

        $role = Role::firstOrCreate([
            'name' => 'super_admin',
        ]);

        $user->assignRole($role);

        // Assign all permissions to super_admin role
        $role->syncPermissions(Permission::all());
    }
}
