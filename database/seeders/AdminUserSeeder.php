<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccount;
use App\Models\Teacher;
use App\Models\Student;
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

        $teacherAccount = UserAccount::updateOrCreate(
            ['username' => 'teacher1'],
            [
                'email' => 'teacher1@example.com',
                'Password' => Hash::make('secret123'),
                'Role' => 'teacher',
                'is_active' => 1,
            ]
        );

        Teacher::updateOrCreate(
            ['user_account_id' => $teacherAccount->id],
            []
        );

        $degree = Degree::updateOrCreate(
            ['name' => 'BSIT'],
            []
        );

        $studentAccount = UserAccount::updateOrCreate(
            ['username' => 'student1'],
            [
                'email' => 'student1@example.com',
                'Password' => Hash::make('12345678'),
                'Role' => 'student',
                'is_active' => 1,
            ]
        );

        Student::updateOrCreate(
            ['user_account_id' => $studentAccount->id],
            [
                'Fname' => 'Juan',
                'Mname' => 'S',
                'Lname' => 'Dela Cruz',
                'Address' => 'Railway Seed',
                'Email' => 'student1@example.com',
                'Contactno' => '09123456789',
                'degree_id' => $degree->id,
            ]
        );
    }
}
