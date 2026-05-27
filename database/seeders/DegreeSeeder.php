<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    public function run(): void
    {
        Degree::updateOrCreate(
            ['name' => 'BSIT'],
            []
        );
    }
}
