<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Web', 'image' => 'image.png', 'slug' => 'web', 'status' => 'Public']);
        Category::create(['name' => 'Mobile', 'image' => 'image.png', 'slug' => 'mobile', 'status' => 'Public']);
        Category::create(['name' => 'Desktop', 'image' => 'image.png', 'slug' => 'desktop', 'status' => 'Public']);
        Category::create(['name' => 'Pendidikan', 'image' => 'image.png', 'slug' => 'pendidikan', 'status' => 'Public']);
        Category::create(['name' => 'Kesehatan', 'image' => 'image.png', 'slug' => 'kesehatan', 'status' => 'Public']);
        //5
        Category::create(['name' => 'Teknologi', 'image' => 'image.png', 'slug' => 'teknologi', 'status' => 'Public']);
        Category::create(['name' => 'Keuangan', 'image' => 'image.png', 'slug' => 'keuangan', 'status' => 'Public']);
        Category::create(['name' => 'Lingkungan', 'image' => 'image.png', 'slug' => 'lingkungan', 'status' => 'Public']);
        Category::create(['name' => 'Pariwisata', 'image' => 'image.png', 'slug' => 'pariwisata', 'status' => 'Public']);
        Category::create(['name' => 'Hiburan', 'image' => 'image.png', 'slug' => 'hiburan', 'status' => 'Public']);
        //10
        Category::create(['name' => 'Olahraga', 'image' => 'image.png', 'slug' => 'olahraga', 'status' => 'Public']);
        Category::create(['name' => 'Komunitas', 'image' => 'image.png', 'slug' =>' komunitas', 'status' => 'Public']);
        Category::create(['name' => 'Berita', 'image' => 'image.png', 'slug' => 'berita', 'status' => 'Public']);
        Category::create(['name' => 'Pemerintahan', 'image' => 'image.png', 'slug' => 'pemerintahan', 'status' => 'Public']);
        Category::create(['name' => 'Bisnis', 'image' => 'image.png', 'slug' => 'bisnis', 'status' => 'Public']);
        //15
        Category::create(['name' => 'Hukum', 'image' => 'image.png', 'slug' => 'hukum', 'status' => 'Public']);
        Category::create(['name' => 'Seni dan Budaya', 'image' => 'image.png', 'slug' =>'seni', 'status' => 'Public']);
        Category::create(['name' => 'Sains', 'image' => 'image.png', 'slug' => 'sains', 'status' => 'Public']);
        Category::create(['name' => 'Otomotif', 'image' => 'image.png', 'slug' => 'otomotif', 'status' => 'Public']);
        Category::create(['name' => 'Permainan', 'image' => 'image.png', 'slug' => 'permainan']);
        //20
        Category::create(['name' => 'Makanan dan Minuman', 'image' => 'image.png', 'slug' =>'makanan', 'status' => 'Public']);
        Category::create(['name' => 'Transportasi', 'image' => 'image.png', 'slug' => 'transportasi', 'status' => 'Public']);
        Category::create(['name' => 'Pertanian', 'image' => 'image.png', 'slug' => 'pertanian', 'status' => 'Public']);
        Category::create(['name' => 'Manufaktur', 'image' => 'image.png', 'slug' => 'manufaktur', 'status' => 'Public']);
    }
}
