<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name'              => 'Admin',
            'password'          => \Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $tenant = Tenant::firstOrCreate([
            'name' => 'Tenant 1',
        ]);

        $tenant->users()->syncWithoutDetaching($admin);
    }
}
