<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Full system access'],
            ['name' => 'Customer', 'slug' => 'customer', 'description' => 'Registers for and manages garbage collection service'],
            ['name' => 'Garbage Collector', 'slug' => 'collector', 'description' => 'Performs assigned waste collection tasks'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
