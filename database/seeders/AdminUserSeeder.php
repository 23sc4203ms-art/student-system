<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccount;
use App\Models\Teacher;
use App\Models\Degree;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserAccount::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@example.com',
                'Password' => Hash::make('12345678'),
                'Role' => 'admin',
                'is_active' => 1,
            ]
        );

        $teacher = UserAccount::updateOrCreate(
            ['username' => 'teacher1'],
            [
                'email' => 'teacher1@example.com',
                'Password' => Hash::make('secret123'),
                'Role' => 'teacher',
                'is_active' => 1,
            ]
        );

        Teacher::updateOrCreate(
            ['user_account_id' => $teacher->id],
            []
        );

        Degree::updateOrCreate(
            ['name' => 'BSIT'],
            ['description' => 'Bachelor of Science in Information Technology']
        );
    }
}
