<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminProject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Admin::create([
            'id' => '0d81b5a8-2faf-4803-a234-86e7f076dd43',
            'name'=>"Admin",
        'email'=>'admin@fcpms.com',
        'password'=>Hash::make("12345678"),
        ]);

    }
}
