<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
        User::create([
            'id'=>'3dd4d7c0-5c41-44e1-b888-e40c3435782b',
            'name'=>"User",
        'email'=>'user@fcpms.com',
        'password'=>Hash::make("12345678"),
        ]);
    }
}
