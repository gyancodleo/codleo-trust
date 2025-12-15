<?php

namespace Database\Seeders;

use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClientUser;
use Illuminate\Support\Facades\Hash;

class ClientUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientUser::create([
            'name' => 'Gyanender',
            'email' => 'gyancodleo@gmail.com',
            'password' => Hash::make('gy1n@codleo'),
            'is_2fa_enabled' => true,
        ]);
    }
}
