<?php

namespace Database\Seeders;

use App\Models\SpecificationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecificationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ["name" => "Vehicle Dimensions"],
            ["name" => "Engine Specification"],
            ["name" => "Transmission"],
            ["name" => "Chassis & Suspension"],
            ["name" => "Steering & Braking"],
            ["name" => "Tyre Size"],
            ["name" => "Cabin & Electrical"],
            ["name" => "Other Details"],
            ["name" => "Endurance"],
        ];
        
        
        SpecificationCategory::insert($data);
    }
}
