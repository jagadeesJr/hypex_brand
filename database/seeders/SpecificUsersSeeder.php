<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CreatorsAuth;
use App\Models\BrandAuthentication;
class SpecificUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CreatorsAuth::create([
            'email' => 'creater@gmail.com',
            'first_name' => 'Creater',
            'last_name' => 's',
            'phone_number' => '9025936214',
            'country' => 'India',
            'location' => 'Salem',
            'password' => bcrypt('12345678'),
            'status' => 1,
        ]);

        // Insert a specific record for brand_users
        BrandAuthentication::create([
            'email' => 'brand@gmail.com',
            'brand_name' => 'Brand',
            'first_name' => 'Brand',
            'last_name' => 's',
            'phone_number' => '9025936214',
            'country' => 'India',
            'location' => 'Salem',
            'password' => bcrypt('12345678'),
            'status' => 1,
        ]);
    }
}
