<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name'=> 'Gyanender',
            'email'=>'gyanender@codleo.com',
            'password'=>Hash::make('gy1n@codleo'),
            'role'=>'super_admin',
            'is_2fa_enabled'=>true,
        ]);
    }
}
