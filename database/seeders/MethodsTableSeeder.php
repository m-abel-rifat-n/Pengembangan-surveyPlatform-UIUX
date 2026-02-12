<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Method;

class MethodsTableSeeder extends Seeder
{
    public function run(): void
    {
        Method::create(['name' => 'SUS', 'slug' => 'sus']);
        Method::create(['name' => 'TAM', 'slug' => 'tam']);
        Method::create(['name' => 'A/B Testing', 'slug' => 'ab-testing']);
        Method::create(['name' => 'WCAG Testing', 'slug' => 'wcag-testing']);
        Method::create(['name' => 'Raw NASA-TLX', 'slug' => 'nasa-tlx']);
        Method::create(['name' => 'VisAWI-S', 'slug' => 'visawi-s']);
        // Method::create(['name' => 'UTAUT2', 'slug' => 'utaut2']);
    }
}
