<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mailinator.com',
            'password' => app('hash')->make(12345),
            'role' => 'Admin',
            'created_by' => 'Admin',
            'deleted_by' => "-",
        ]);
    }
}
