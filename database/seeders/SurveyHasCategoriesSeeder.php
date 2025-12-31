<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyHasCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('survey_has_categories')->insert([
            [
                'survey_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 1,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 1,
                'category_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 1,
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 1,
                'category_id' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'category_id' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'category_id' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'category_id' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'category_id' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 2,
                'category_id' => 21,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'category_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'category_id' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'category_id' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 3,
                'category_id' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 4,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 4,
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'survey_id' => 4,
                'category_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
