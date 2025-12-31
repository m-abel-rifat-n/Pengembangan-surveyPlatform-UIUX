<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class UserSelectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $dataUser1 = [];

        foreach ($categories as $category) {
            $dataUser1[] = [
                'user_id' => 1,
                'category_id' => $category->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $dataUser2 = [
            [
                'user_id' => 2,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $dataUser3 = [
            [
                'user_id' => 3,
                'category_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'category_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'category_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        $dataUser4 = [
            [
                'user_id' => 4,
                'category_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'category_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'category_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('user_select_category')->insert($dataUser1);
        DB::table('user_select_category')->insert($dataUser2);
        DB::table('user_select_category')->insert($dataUser3);
        DB::table('user_select_category')->insert($dataUser4);
    }
}
