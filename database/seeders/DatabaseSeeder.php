<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $business = Business::firstOrCreate([
            'name_company'  => 'Sultan Java',
            'email'         => 'SultanJava@gmail.com',
            'phone'         => 6283838402905,
            'address'        => 'Pasar Kembangsari, Kec. Tengaran, Kabupaten Semarang, Jawa Tengah',
        ]); 
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'business_id' => 1, 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'business_id' => 1, 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'business_id' => 1, 'guard_name' => 'web']);

         $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Arfan',
                'password' => bcrypt('13456789'),
            ]
        );

        $employee = User::firstOrCreate(
            ['email' => 'employee@gmail.com'],
            [
                'name' => 'Budi',
                'password' => bcrypt('13456789'),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Aly',
                'password' => bcrypt('13456789'),
            ]
        );

        // Assign role ke user
        $admin->assignRole($adminRole);
        $employee->assignRole($employeeRole);
        $customer->assignRole($customerRole);
    }
}
