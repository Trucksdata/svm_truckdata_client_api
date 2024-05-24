<?php

namespace Database\Seeders;

use App\Models\EnergySource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnergySourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Petrol'],
            ['name' => 'Diesel'],
            ['name' => 'CNG'],
            ['name' => 'Electric'],
            ['name' => 'Hydrogen'],
        ];
        EnergySource::insert($data);
    }
}
