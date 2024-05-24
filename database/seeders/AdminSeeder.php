<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => 'admin@truckdata.com',
            'password' => 'password'
        ]);
        
        $superAdmin->assignRole('super_admin');
    }
}
