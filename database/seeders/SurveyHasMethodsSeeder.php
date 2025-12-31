<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyHasMethodsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('survey_has_methods')->insert([
            [
                'survey_id' => 1,
                'method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 1,
                'method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 4,
                'method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 4,
                'method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 5,
                'method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 6,
                'method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 7,
                'method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 8,
                'method_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 9,
                'method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 10,
                'method_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
