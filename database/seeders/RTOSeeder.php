<?php

namespace Database\Seeders;

use App\Models\RTO;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RTOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RTO::create([
            'name' => 'First RTO',
            'email' => 'rto1@test.com',
            'password' => Hash::make('password')
        ]);
    }
}
