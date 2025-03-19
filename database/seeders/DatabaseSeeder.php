<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'Mike',
            'last_name' => 'Higgins',
            'email' => 'mike@hhm.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
            'special_rights' => 'full_rights',
            'company_id' => 1,
            'department_id' => 1,
            'business_unit_id' => 1,
            'is_active' => true
        ]);
    }
}
